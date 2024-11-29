<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RearrangeAnswer extends Model
{
    protected $fillable = [
		'question_id',
		'answer',
    ];

    protected $casts = [
        'answer' => 'array'
    ];
}
