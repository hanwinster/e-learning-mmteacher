<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Traits\Unicodeable;

class AssignmentUser extends Model implements HasMedia
{
	use Unicodeable, HasMediaTrait;
    protected $fillable = [
			'assignment_id',
			'user_id',
			'attached_file',
            'score',
			'comment',
			'comment_by',
    ];

    public $unicodeFields = [
        'comment',
    ];

    public function assignment()
    {
        return $this->belongsTo('App\Models\Assignment');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function commentUser()
    {
        return $this->belongsTo('App\User', 'comment_by');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('user_assignment_attached_file')
            ->singleFile();
    }

}
