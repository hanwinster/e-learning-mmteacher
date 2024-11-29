<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Slide extends Model implements HasMedia
{
    use Sortable, HasMediaTrait;

    protected $fillable = [
        'title', 'description', 'published', 'weight'
    ];

    public $unicodeFields = [
        'title',
        'description',
    ];

    public $sortable = [
        'id',
        'title',
        'weight',
        'created_at',
        'updated_at'
    ];

    public function getRouteKeyName()
    {
        return 'id';
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
        return optional($this->getMedia('slides')->first())->getUrl('thumb');
    }
	
	public function getMediumPath()
    {
        return optional($this->getMedia('slides')->first())->getUrl('medium');
    }

    public function getImagePath()
    {
        return optional($this->getMedia('slides')->first())->getUrl('large');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('slides')
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
