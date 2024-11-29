<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlankAnswer extends Model
{
    protected $fillable = [
		'question_id',
		'answer',
        'paragraph',
        'optional_keywords'
    ];

    protected $casts = [
        'paragraph' => 'array'
    ];
}
