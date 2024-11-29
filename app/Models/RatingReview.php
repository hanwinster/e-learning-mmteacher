<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingReview extends Model
{
    protected $fillable = [
        'id',
        'rating',
        'remark',
        'user_id',
        'course_id'
    ];

    public $unicodeFields = [
        'review'
    ];

    public $sortable = [
        'rating',
        'remark'
    ];

    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
