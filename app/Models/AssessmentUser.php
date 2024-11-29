<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Unicodeable;

class AssessmentUser extends Model
{
	use Unicodeable;

    protected $fillable = [
			'assessment_question_answer_id',
            'course_id',
			'user_id',
			'answers',
            'score',
            'attempts',
            'status',
			'comment',
			'comment_by',
            'pass_option' // default is pass, [ submitted,pass,retake ]
    ];

    public $unicodeFields = [
        'answers','comment'
    ];

    protected $casts = [
        'answers' => 'json'
    ];

    public function assessment_question_answer()
    {
        return $this->belongsTo('App\Models\AssessmentQuestionAnswer');
    }

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
