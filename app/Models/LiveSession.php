<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Spatie\MediaLibrary\Models\Media;
//use Spatie\MediaLibrary\HasMedia\HasMedia;
//use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Traits\Unicodeable;

class LiveSession extends Model //implements HasMedia
{
	use Unicodeable; //, HasMediaTrait;
    protected $fillable = [
    	'topic',
        'meeting_id', // be received from Zoom
        'start_date',
        'start_time',
        'agenda',
        'host_video',
        'participant_video',
        'duration',
        'passcode',
        'status', //waiting,
        'description',
        'start_url',
        'join_url',
    	'course_id',
        'user_id',
        'lecture_id'
        
    ];

    public $unicodeFields = [
        'topic',
        'agenda',
        'description'
    ];

    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }

    public function lecture()
    {
        return $this->belongsTo('App\Models\Lecture');
    }

    // public function liveSessionUsers()
    // {
    //     return $this->hasMany('App\Models\LiveSessionUser');
    // }
}
