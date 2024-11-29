<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Traits\Unicodeable;

class Summary extends Model implements HasMedia
{
    use Unicodeable, HasMediaTrait;
    protected $fillable = [
    	'title',
        'description',
    	'course_id',
        'lecture_id',
        'user_id'
    ];

    public $unicodeFields = [
        'title',
        'description',
    ];

    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }

    public function lecture()
    {
        return $this->belongsTo('App\Models\Lecture');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('summary_attached_file')
            ->singleFile();
    }
}
