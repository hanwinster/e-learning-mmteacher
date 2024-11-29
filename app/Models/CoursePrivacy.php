<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoursePrivacy extends Model
{
    protected $fillable = [
        'course_id',
        'user_type',
        'role_id'
    ];

    public function course()
    {
        $this->belongsTo('App\Models\Course', 'course_id');
    }
}
