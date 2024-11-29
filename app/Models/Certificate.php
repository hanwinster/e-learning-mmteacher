<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Traits\Unicodeable;

class Certificate extends Model implements HasMedia
{
	use Unicodeable, HasMediaTrait;
    protected $fillable = [
        'course_id',
    	'title', /* Certificate of Completion */
        'description', /* just a note from the instructor */
        'certify_text', 
        'completion_text',
        'certificate_date',
        'signature_1',
        'signature_2',
        'background_image',
        'logo_image'
    ];

    public $unicodeFields = [
        'title',
        'description',
        'certify_text', 
        'completion_text'
    ];

    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }

    public function certificate_user()
    {
        return $this->hasMany('App\Models\CertificateUser');
    }

    // public function registerMediaCollections()
    // {
    //     $this->addMediaCollection('assignment_attached_file')->singleFile();
    // }
}