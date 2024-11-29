<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use Sortable;

    protected $table = 'feedbacks';

    protected $dates = ['deleted_at'];

    public $sortable = [
        'id',
        'resource_id',
        'user_id',
        'rating',
        'comment',
        'created_at',
        'updated_at'
    ];

    protected $fillable = ['resource_id', 'user_id', 'rating', 'comment'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($review) {
            $review->resource->rating = $review->resource->averageRating();
            $review->resource->save();
        });

        static::deleted(function ($review) {
            $review->resource->rating = $review->resource->averageRating();
            $review->resource->save();
        });
    }

    public function resource()
    {
        return $this->belongsTo('App\Models\Resource');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeIsPublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeWithSearch($query, $search)
    {
        return $query->where('comment', 'LIKE', "%$search%");
    }
}
