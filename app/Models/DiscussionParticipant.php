<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscussionParticipant extends Model
{
    use Unicodeable; 
    protected $fillable = [
    	'id',
        'discussion_id',
        'user_id',
    	'status' //added/contributed  
    ];


    public function discussion()
    {
        return $this->belongsTo('App\Models\Discussion');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
