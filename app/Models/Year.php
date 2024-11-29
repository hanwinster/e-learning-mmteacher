<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Cviebrock\EloquentSluggable\Sluggable;

class Year extends Model
{
    use Sluggable, Sortable;

    protected $table = 'years';

    protected $fillable = ['title', 'slug', 'description', 'published'];

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
                'source' => 'title'
            ]
        ];
    }

    public function resources()
    {
        return $this->belongsToMany('App\Models\Resource', 'resource_year', 'resource_id', 'year_id');
    }

    public function scopeIsPublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeWithSearch($query, $search)
    {
        return $query->where('title', 'LIKE', "%$search%");
    }
}
