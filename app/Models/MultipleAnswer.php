<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MultipleAnswer extends Model
{
    protected $fillable = [
    	'question_id',
    	'name',
    	'answer',
    	'is_right_answer',
    ];

	public function question()
    {
        return $this->belongsTo('App\Models\Question');
    }
}
