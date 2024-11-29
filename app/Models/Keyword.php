<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Traits\Unicodeable;

class Keyword extends Model
{
    use Sortable, Sluggable, Unicodeable;

    protected $table = 'keywords';

    public $sortable = [
        'id',
        'keyword',
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'keyword', 'slug', 'published', 'user_id',
    ];

    public $unicodeFields = [
        'keyword'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'keyword'
            ]
        ];
    }

    public function scopeIsPublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeWithSearch($query, $search)
    {
        return $query->where('keyword', 'LIKE', "%$search%");
    }

    public function path()
    {
        return route('keyword.show', $this->slug);
    }

    public function resources()
    {
        return $this->belongsToMany(Resource::class)->withPivot('provided_by', 'user_id');
    }

    public static function tagResource($resource, $keywords, $providedBy = 'creator')
    {
        if ($keywords) {
            $sycnData = [];

            foreach ($keywords as $keyword) {
                if ($keyword) {
                    $newKeyword = self::firstOrNew(['keyword' => $keyword]);
                    $newKeyword->keyword = $keyword;
                    $newKeyword->save();

                    $sycnData[$newKeyword->id] = ['user_id' => auth()->user()->id, 'provided_by' => $providedBy];
                }
            }

            $resource->keywords()->sync($sycnData);
        }
    }
}
