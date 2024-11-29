<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CourseLearner extends Model
{
    const COURSE_LEARNER_STATUSES = [
        'not_started' => 'Not Started',
        'in_progress' => 'In Progress',
        'learning' => 'Learning',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];
    protected $fillable = [
		'course_id',
		'user_id',
        'status', //not_started, in_progress, completed
        'percentage',
		'notify_count',    	
        'active',
        'completed', //array
        'certificate_count',
        'last_visited' //url
    ];

    protected $casts = [
        'completed' => 'json'
    ];
    public function scopeOfUser($query, $user_id)
    {
        return $query->where('user_id', '=', $user_id);
    }
    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }

    public static function getLearnersByCourseId($courseId)
    {
        return CourseLearner::where('course_id', $courseId)
                              ->where('active', 1)->get();
    }

}