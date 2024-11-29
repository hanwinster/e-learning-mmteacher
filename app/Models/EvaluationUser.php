<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Unicodeable;

class EvaluationUser extends Model 
{
	use Unicodeable;

    protected $fillable = [
            'course_id',
			'user_id',
			'feedbacks',
            'status',
            'overall_rating'   
    ];

    public $unicodeFields = [
        'feedbacks'
    ];

    protected $casts = [
        'feedbacks' => 'json'
    ];

    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function commentUser()
    {
        return $this->belongsTo('App\User', 'comment_by');
    }

}
