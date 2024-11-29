<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Unicodeable;

class Discussion extends Model //or chat room
{
    use Unicodeable; 
    protected $fillable = [
    	'id',
        'title',
        'description',
    	'course_id',
        'allow_takers',
        'allow_learners'
        
    ];

    public $unicodeFields = [
        'title',
        'description'
    ];

    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }

    public function discussionParticipants()
    {
        return $this->hasMany('App\Models\DiscussionParticipant');
    }

    public function discussionMessages()
    {
        return $this->hasMany('App\Models\DiscussionMessage');
    }

    
}
