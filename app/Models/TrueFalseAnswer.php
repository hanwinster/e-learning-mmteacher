<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrueFalseAnswer extends Model
{
    protected $fillable = [
		'question_id',
		'answer'
    ];

}
