<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Traits\Unicodeable;
use App\User;

class Lecture extends Model implements HasMedia
{
	use Sortable, HasMediaTrait, Unicodeable;

    const RESOURCE_TYPE =[
        'none' => 'None',
        'attach_file' => 'Attach File',
        'embed_video' => 'Embed YouTube Video'  
    ];

    protected $fillable = [
        'id',
		'lecture_title',
		'course_id',
		'user_id',
        'resource_type',
        'video_link',
        'resource_link',
        'description',
    ];

    public $unicodeFields = [
        'title',
    ];

    protected $hidden = [
        //'id',
        'uuid'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('lecture_attached_file')
            ->singleFile();
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function quizzes()
    {
        return $this->hasMany('App\Models\Quiz');
    }

    // public function assignments()
    // {
    //     return $this->hasMany('App\Models\Assignment');
    // }

    public function learningActivities()
    {
        return $this->hasMany('App\Models\LearningActivity');
    }

    public function summaries()
    {
        return $this->hasMany('App\Models\Summary');
    }

    public function liveSessions()
    {
        return $this->hasMany('App\Models\LiveSession');
    }


    public function lectureLearners()
    {
        return $this->belongsToMany(User::class, 'learner_lectures', 'lecture_id', 'user_id');
    }

    // this is a recommended way to declare event handlers
    public static function boot() {
        parent::boot();

        static::deleting(function($lecture) { // before delete() method call this
            foreach ($lecture->quizzes as $key => $quiz) {
                foreach ($quiz->questions as $key => $question) {
                    $question->true_false_answer()->delete();
                    $question->multiple_answers->each->delete();
                    $question->true_false_answer()->delete();
                    $question->blank_answer()->delete();
                    $question->rearrange_answer()->delete();
                    $question->matching_answer()->delete();
                   // $question->assignments->each->delete();
                }
                $quiz->questions()->delete();
            }
             $lecture->quizzes()->delete();
             // do the rest of the cleanup...
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
