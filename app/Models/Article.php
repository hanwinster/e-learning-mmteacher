<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Traits\Unicodeable;

class Article extends Model implements HasMedia
{
    use Sortable, Sluggable, HasMediaTrait, Unicodeable;

    protected $fillable = [
        'title', 'title_mm', 'category_id', 'hyperlink', 'slug', 'published', 'body', 'body_mm', 'user_id'
    ];

    public $unicodeFields = [
        'title',
        'title_mm',
        'body',
        'body_mm'
    ];

    public $sortable = [
        'id',
        'title',
        'title_mm',
        'category_id',
        'weight',
        'created_at',
        'updated_at'
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
                'source' => 'title'
            ]
        ];
    }

    public function category()
    {
        return $this->belongsTo('App\Models\ArticleCategory', 'category_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function scopeIsPublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeIsCategory($query, $category_id)
    {
        return $query->where('category_id', $category_id);
    }

    public function scopeWithCategory($query, $category_id)
    {
        if ($category_id) {
            return $query->where('category_id', $category_id);
        } else {
            return $query;
        }
    }

    public function scopeWithSlug($query, $slug)
    {
        if ($slug) {
            return $query->where('slug', 'LIKE', $slug); 
        } else {
            return $query;
        }
    }

    public function scopeWithoutCurrentArticle($query, $slug)
    {
        if ($slug) {
            return $query->where('slug', '<>', $slug);
        } else {
            return $query;
        }
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
        return route('article.show', $this->slug);
    }

    public function getThumbnailPath()
    {
        return optional($this->getMedia('articles')->first())->getUrl('thumb');
    }

    public function getMediumPath()
    {
        return optional($this->getMedia('articles')->first())->getUrl('medium');
    }

    public function getImagePath()
    {
        return optional($this->getMedia('articles')->first())->getUrl('large');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('articles')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(100)
                    ->height(100);

                $this
                    ->addMediaConversion('medium')
                    ->width(400)
                    ->height(400);

                $this
                    ->addMediaConversion('large')
                    ->width(800)
                    ->height(800);
            });
    }
}
