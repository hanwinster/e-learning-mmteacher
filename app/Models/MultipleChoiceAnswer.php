<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MultipleChoiceAnswer extends Model
{
    protected $fillable = [
		'question_id',
		'answer_a',
		'answer_b',
		'answer_c',
		'answer_d',
		'answer_e',
		'right_answer',
		'description',
    ];

    public function quiz()
    {
    	return $this->belongsTo(\App\Models\Quiz::class);
    }
}
