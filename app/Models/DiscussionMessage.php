<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Unicodeable;

class DiscussionMessage extends Model
{
    use Unicodeable; 
    protected $fillable = [
    	'id',
        'discussion_id',
        'user_id',
        'username',
    	'message'  
    ];

    public $unicodeFields = [
        'message'
    ];

    public function discussion()
    {
        return $this->belongsTo('App\Models\Discussion'); // $this->hasOne('App\Models\Discussion',  'id',  'discussion_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
