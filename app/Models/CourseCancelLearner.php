<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCancelLearner extends Model
{
    
    protected $fillable = [
		'course_id',
		'user_id'
    ];

    
    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function getCancelledLearnersByCourseId($courseId)
    {
        return CourseLearner::where('course_id', $courseId)->get();
    }

}