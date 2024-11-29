<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class ArticleCategory extends Model implements HasMedia
{
    use Sluggable, Sortable, HasMediaTrait;

    protected $table = 'article_categories';

    /* KHK Start */
    protected $fillable = ['title', 'slug', 'body', 'weight', 'published'];
	/* KHK End */

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

    public function articles()
    {
        return $this->hasMany('App\Models\Article', 'category_id');
    }

    public function scopeIsPublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeWithSearch($query, $search)
    {
        return $query->where('title', 'LIKE', "%$search%");
    }

    public function path()
    {
        return route('article.category', $this->slug);
		/* KHK Start */
		//return 'article/category/' . $this->slug;
		/* KHK End */
    }
}
