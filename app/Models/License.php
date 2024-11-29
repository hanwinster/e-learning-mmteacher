<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
//use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class License extends Model implements HasMedia
{
    use Sortable, HasMediaTrait; //Sluggable,

    protected $table = 'licenses';

    protected $fillable = ['title', 'published', 'description'];

	public $sortable = [
        'id',
        'title',
        'created_at',
        'updated_at'
    ];
	
    public function getRouteKeyName()
    {
        return 'id'; //'slug';
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    /*
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
    */

    public function resources()
    {
        return $this->hasMany('App\Models\Resource', 'license_id');
    }


    public function scopeIsPublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeWithSearch($query, $search)
    {
        if ($search) {
            return $query->where('title', 'LIKE', "%$search%");
        } else {
            return $query;
        }
    }

    public function path()
    {
        //return route('license.show', $this->slug);
        return route('license.show', $this->id);
    }
}
