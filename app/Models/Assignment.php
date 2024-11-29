<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
//use App\Traits\Unicodeable;

class Assignment extends Model implements HasMedia
{
	use HasMediaTrait;
    protected $fillable = [
        'question_id',
    	// 'title',
        // 'description',
    	// 'course_id',
        // 'user_id',
        // 'lecture_id'
    ];


    public function question()
    {
        return $this->belongsTo('App\Models\Question');
    }

    // public function course()
    // {
    //     return $this->belongsTo('App\Models\Course');
    // }

    // public function lecture()
    // {
    //     return $this->belongsTo('App\Models\Lecture');
    // }

    public function assignment_user()
    {
        return $this->hasMany('App\Models\AssignmentUser');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('assignment_attached_file')
            ->singleFile();
    }

}
