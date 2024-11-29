<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Traits\Unicodeable;

class Quiz extends Model
{
    use Sortable, Unicodeable;
    protected $fillable = [
		'title',
		'course_id',
		'lecture_id',
        'user_id',
		'type',    	
    ];

    public $unicodeFields = [
        'title',
    ];

    const TRUE_FALSE = 'true_false';
    const BLANK = 'blank';
    const SHORT_QUESTION = 'short_question';
    const LONG_QUESTION = 'long_question';
    const MULTIPLE_CHOICE = 'multiple_choice';
    const REARRANGE = 'rearrange';
    const MATCHING = 'matching';
    const ASSIGNMENT = 'assignment';
    const QUIZ_TYPES = [
        self::TRUE_FALSE => 'True/False',
        self::SHORT_QUESTION => 'Short Question',
        self::LONG_QUESTION => 'Long Question',
        self::BLANK => 'Fill in the blank',
        self::MULTIPLE_CHOICE => 'Multiple Choice',
        self::REARRANGE => 'Rearrange',
        self::MATCHING => 'Matching',
        self::ASSIGNMENT => 'Assignment'
    ];

    public function questions()
    {
        return $this->hasMany(\App\Models\Question::class, 'quiz_id');
    }

    public function lecture()
    {
        return $this->belongsTo('App\Models\Lecture');
    }

    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }

    public function getQuizType()
    {
        if ($this->type !== null) {
            return self::QUIZ_TYPES[$this->type];
        }

        return null;
    }

    // this is a recommended way to declare event handlers
    public static function boot() { 
        parent::boot();
        static::deleting(function($quiz) { // before delete() method call this
            foreach ($quiz->questions as $key => $question) { 
                $question->true_false_answer()->delete();
                $question->multiple_answers->each->delete();
                $question->true_false_answer()->delete();
                $question->blank_answer()->delete();
                $question->rearrange_answer()->delete();
                $question->matching_answer()->delete();
                $question->assignment()->delete();
                $question->short_answer()->delete();
                $question->long_answer()->delete();
            }
             $quiz->questions()->delete();
             // do the rest of the cleanup...
        });
    }

}
