<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Kyslik\ColumnSortable\Sortable;
use App\Traits\Unicodeable;

class Question extends Model  implements HasMedia
{
    use Sortable, Unicodeable, HasMediaTrait;
    protected $fillable = [
		'title',
		'quiz_id',
        'user_id', 
        'description'
    ];

    public $unicodeFields = [
        'title',
    ];

    // public function multiple_choice()
    // {
    // 	return $this->hasOne(\App\Models\MultipleChoiceAnswer::class);
    // }

    public function multiple_answers()
    {
        return $this->hasMany(\App\Models\MultipleAnswer::class);
    }

    public function true_false_answer()
    {
        return $this->hasOne(\App\Models\TrueFalseAnswer::class);
    }

    public function blank_answer()
    {
        return $this->hasOne(\App\Models\BlankAnswer::class);
    }

    public function short_answer()
    {
        return $this->hasOne(\App\Models\ShortAnswer::class);
    }

    public function long_answer()
    {
        return $this->hasOne(\App\Models\LongAnswer::class);
    }

    public function rearrange_answer()
    {
        return $this->hasOne(\App\Models\RearrangeAnswer::class);
    }

    public function matching_answer()
    {
        return $this->hasOne(\App\Models\MatchingAnswer::class);
    }

    public function assignment()
    {
        return $this->hasOne(\App\Models\Assignment::class);
    }

    public function lecture()
    {
        return $this->belongsTo('App\Models\Lecture');
    }

    public function quiz()
    {
        return $this->belongsTo('App\Models\Quiz');
    }

    public function getThumbnailPath()
    {
        return optional($this->getMedia('question_attached_file')->first())->getUrl('thumb');
    }

    public function getMediumPath()
    {
        return optional($this->getMedia('question_attached_file')->first())->getUrl('medium');
    }

    public function getImagePath()
    {
        return optional($this->getMedia('question_attached_file')->first())->getUrl('large');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('question_attached_file')
                ->singleFile()
                ->registerMediaConversions(function (Media $media) {
                    $this
                    ->addMediaConversion('thumb')
                    ->width(200)
                    ->height(200)
                    ->nonQueued();

                    $this
                    ->addMediaConversion('bthumb')
                    ->width(300)
                    ->height(300)
                    ->nonQueued();

                    $this
                    ->addMediaConversion('medium')
                    ->width(400)
                    ->height(400)
                    ->nonQueued();

                    $this
                    ->addMediaConversion('large')
                    ->width(800)
                    ->height(800)
                    ->nonQueued();
                });
    }

    // this is a recommended way to declare event handlers
    public static function boot() {
        parent::boot();

        static::deleting(function($question) { // before delete() method call this
             $question->multiple_answers->each->delete();
             $question->true_false_answer()->delete();
             $question->blank_answer()->delete();
             $question->rearrange_answer()->delete();
             $question->matching_answer()->delete();
             // do the rest of the cleanup...
        });
    }

    public static function shuffelArray($arr)
    {
        $newArr = shuffle($arr);
        return $newArr;
    }

}
