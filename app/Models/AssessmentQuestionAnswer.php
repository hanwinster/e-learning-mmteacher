<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Unicodeable;

class AssessmentQuestionAnswer extends Model
{
	use Unicodeable;
    const TRUE_FALSE = 'true_false';
    const MULTIPLE_CHOICE = 'multiple_choice';
    const REARRANGE = 'rearrange';
    const MATCHING = 'matching';
    const LONG_ANSWER = 'long_answer';
    const ASSESSMENT_TYPES = [
        self::TRUE_FALSE => 'True/False',
        self::MULTIPLE_CHOICE => 'Multiple Choice',
        self::REARRANGE => 'Rearrange',
        self::MATCHING => 'Matching',
        self::LONG_ANSWER => 'Long Answer'
    ];
    // const PASSING_OPTION_1 = "after_providing_answer";
    // const PASSING_OPTION_2 = "after_sending_feedback";
    // const PASSING_OPTION_3 = "after_setting_pass";
    // const PASSING_OPTIONS = [ 
    //   self::PASSING_OPTION_1 => 1,
    //   self::PASSING_OPTION_2 => 2,
    //   self::PASSING_OPTION_3 => 3
    // ];
    protected $fillable = [
    	'question',
		'course_id',
		'user_id',
    	'answers',
    	'right_answers',
        'order',
        'type',
        'passing_option' //after_providing_answer, after_sending_feedback, after_setting_pass
    ];

	public $unicodeFields = [
        'question',
        'answers'
    ];

    protected $casts = [
        'answers' => 'json',
        'right_answers' => 'json'
    ];
    
	public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }

	public function assessment_user()
    {
        return $this->hasMany('App\Models\AssessmentUser');
    }
}
