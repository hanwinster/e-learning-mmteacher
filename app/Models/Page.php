<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Page extends Model implements HasMedia
{
    use Sortable, Sluggable, HasMediaTrait;

    protected $fillable = [
        'title', 'title_mm', 'slug', 'published', 'body','body_mm', 'user_id'
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

    public function path()
    {
        return $this->slug;
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

    public function getThumbnailPath()
    {
        return optional($this->getMedia('pages')->first())->getUrl('thumb');
    }

    public function getMediumPath()
    {
        return optional($this->getMedia('pages')->first())->getUrl('medium');
    }

    public function getImagePath()
    {
        return optional($this->getMedia('pages')->first())->getUrl('large');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('pages')
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
