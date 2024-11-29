<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortAnswer extends Model
{
    protected $fillable = [
		'question_id',
		'answer',
    ];
}
