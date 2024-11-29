<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Cviebrock\EloquentSluggable\Sluggable;

class Format extends Model
{
    use Sluggable, Sortable;

    protected $table = 'formats';

    protected $dates = ['deleted_at'];

    public $sortable = [
        'id',
        'name',
        'created_at',
        'updated_at'
    ];

    protected $fillable = ['name', 'slug'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function resources()
    {
        return $this->belongsToMany(Resource::class);
    }

    public function scopeIsPublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeWithSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%$search%");
    }
}
