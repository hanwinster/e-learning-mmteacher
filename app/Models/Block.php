<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Block extends Model implements HasMedia
{
    use Sortable, HasMediaTrait;

    protected $fillable = [
        'title', 'second_title', 'published', 'body', 'conditions', 'region', 'weight',
        'file_path', 'file_name', 'hide_title'
    ];

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

    public function registerMediaCollections()
    {
        $this->addMediaCollection('blocks')
            // ->singleFile()
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
