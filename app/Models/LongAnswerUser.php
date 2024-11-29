<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Unicodeable;

class LongAnswerUser extends Model
{
	use Unicodeable;

    protected $fillable = [
			'long_answer_id',        
			'user_id',
			'submitted_answer',
			'comment',
			'comment_by',
            'status'
    ];

    public $unicodeFields = [
        'submitted_answer','comment'
    ];
    const STATUS_OPTIONS = [ 
        'submitted' => 'submitted',
        'pass' => 'pass',
        'retake' => 'retake',
      ];

    protected $casts = [
        'submitted_answer' => 'json'
    ];

    public function long_answer()
    {
        return $this->belongsTo('App\Models\LongAnswer');
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
