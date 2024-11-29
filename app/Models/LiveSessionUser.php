<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Spatie\MediaLibrary\HasMedia\HasMedia;
//use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Traits\Unicodeable;

class LiveSessionUser extends Model //implements HasMedia
{
	use Unicodeable;
    protected $fillable = [
			'session_id',
			'user_id',
			'status', // registered, joined
			'remark',		
    ];

    public $unicodeFields = [
        'remark'
    ];

    public function liveSession()
    {
        return $this->belongsTo('App\Models\LiveSession');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }


}
