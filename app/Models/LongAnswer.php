<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Unicodeable;


class LongAnswer extends Model
{ 
    use Unicodeable;

    protected $fillable = [
      'question_id',
      'answer',
      'passing_option'
    ];

    public $unicodeFields = [
        'answer'
    ];

    const PASSING_OPTION_1 = "after_providing_answer";
    const PASSING_OPTION_2 = "after_sending_feedback";
    const PASSING_OPTION_3 = "after_setting_pass";
    const PASSING_OPTIONS = [ 
      self::PASSING_OPTION_1 => 1,
      self::PASSING_OPTION_2 => 2,
      self::PASSING_OPTION_3 => 3
    ];

    public function long_answer_users()
    {
        return $this->hasMany('App\Models\LongAnswerUsers');
    }
    
    public function question()
    {
        return $this->belongsTo('App\Models\Question');
    }
}
