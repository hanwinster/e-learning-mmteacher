<?php

namespace App\Models;

use App\User;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Traits\Unicodeable;


class Course extends Model  implements HasMedia
{
	use Sortable, Unicodeable, HasMediaTrait, Sluggable;

	const BEGINNER_LEVEL = 1;
	const PRE_INTERMEDIATE_LEVEL = 2;
	const INTERMEDIATE_LEVEL = 3;
	const ADVANCE_LEVEL = 4;
	const PROFESSIONAL_LEVEL = 5;
	const LEVELS = [
        self::BEGINNER_LEVEL => 'Beginner',
        self::PRE_INTERMEDIATE_LEVEL => 'Pre-intermediate',
        self::INTERMEDIATE_LEVEL => 'Intermediate',
        self::ADVANCE_LEVEL => 'Advanced',
        self::PROFESSIONAL_LEVEL => 'Professional',
    ];

    const APPROVAL_STATUS_PENDING = 0;
    const APPROVAL_STATUS_APPROVED = 1;
    const APPROVAL_STATUS_REJECTED = 2;

    const APPROVAL_STATUS = [
        self::APPROVAL_STATUS_PENDING => 'Pending',
        self::APPROVAL_STATUS_APPROVED => 'Approved',
        self::APPROVAL_STATUS_REJECTED => 'Rejected',
    ];

    const DOWNLOADABLE_OPTION_PARTIAL = 1;
    const DOWNLOADABLE_OPTION_COMPLETED = 2;
    const DOWNLOADABLE_OPTIONS = [
        self::DOWNLOADABLE_OPTION_PARTIAL => 'After Enrolling',
        self::DOWNLOADABLE_OPTION_COMPLETED => 'After Completion'
    ];

    const ESTIMATED_DURATION_UNIT_HOUR = 'hour(s)';
    const ESTIMATED_DURATION_UNIT_DAY = 'day(s)';
    const ESTIMATED_DURATION_UNIT_WEEK = 'week(s)';
    const ESTIMATED_DURATION_UNIT_MONTH = 'month(s)';
    const ESTIMATED_DURATION_UNIT_YEAR = 'year(s)';
    const ESTIMATED_DURATION_UNIT = [
        self::ESTIMATED_DURATION_UNIT_HOUR, 
        self::ESTIMATED_DURATION_UNIT_DAY, //   => 30,
        self::ESTIMATED_DURATION_UNIT_WEEK, //  => 4,
        self::ESTIMATED_DURATION_UNIT_MONTH, // => 3,
        self::ESTIMATED_DURATION_UNIT_YEAR //  => 1
    ];

    const LANGUAGES = [
        'en' => 'English',
        'my-MM' => 'Myanmar',
        'both' => 'Both'
    ];

    protected $fillable = [
            'title',
            'slug',
            'description',
            'objective',
            'learning_outcome',
            'cover_image',
            'is_display_video',
            'video_link',
            'url_link',
            'course_categories',
            'course_level_id',
            'course_type_id',
            'downloadable_option',
            'approval_status',
            'user_id',
            'approved_by',
            'approved_at',
            'is_published',
            'allow_edit',
            'allow_feedback',
            'allow_discussion',
            'is_locked',
            'is_auto_completion',
            'acceptable_score_for_assignment',
            'acceptable_score_for_assessment',
            'item_affect_certification',
            'estimated_duration',
            'estimated_duration_unit',
            'grace_period_to_notify',
            'view_count',
            'lang',
            'order_type',
            'orders',
            'lecture_order_type',
            'lecture_orders',
            'collaborators',
            'last_modified_by',
            'related_resources' 
    ];

    public $unicodeFields = [
        'title',
        'description',
        'objective'
    ];

    public $sortable = [
        'id',
        'title',
        'approved_by',
        'user_id',
        'created_at'
    ];

    protected $hidden = ['id'];

    protected $casts = [
        'course_categories' => 'array',
        'orders' => 'json',
        'lecture_orders' => 'json',
        'collaborators' => 'json'
    ];
    
    public function privacies()
    {
        return $this->hasMany('App\Models\CoursePrivacy', 'course_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function collaborators()
    {
        return $this->belongsToMany('App\User', 'collaborators');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\CourseCategory', 'course_categories');
    }

    public function level()
    {
        return $this->belongsTo('App\Models\CourseLevel', 'course_level_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\CourseType', 'course_type_id');
    }

    public function getCourseType($courseTypeId)
    {
        return \App\Models\CourseType::findOrFail($courseTypeId);
    }

    public function approver()
    {
        return $this->belongsTo('App\User', 'approved_by');
    }

    public function lecture()
    {
        return $this->hasMany('App\Models\Lecture')->orderBy('lecture_title', 'asc');
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }

    public function quizzes()
    {
        return $this->hasMany('App\Models\Quiz');
    }

    public function learners()
    {
        return $this->hasMany('App\Models\CourseLearner');
    }

    public function cancelLearners()
    {
        return $this->hasMany('App\Models\CourseCancelLearner'); 
    }

    // public function assignments()
    // {
    //     return $this->hasMany('App\Models\Assignment');
    // } 

    public function summaries()
    {
        return $this->hasMany('App\Models\Summary');
    }

    public function learningActivities()
    {
        return $this->hasMany('App\Models\LearningActivity');
    }


    public function assessmentQuestionAnswers()
    {
        return $this->hasMany('App\Models\AssessmentQuestionAnswer')->orderBy('id');
    }

    public function assessmentUsers()
    {
        return $this->hasMany('App\Models\AssessmentUser');
    }

    public function evaluationUsers()
    {
        return $this->hasMany('App\Models\EvaluationUser');
    }

    public function certificate()
    {
        return $this->hasOne('App\Models\Certificate'); // changed from hasMany
    }

    public function discussion()
    {
        return $this->hasOne('App\Models\Discussion');
    }

    public function ratingReviews()
    {
        return $this->hasMany('App\Models\RatingReview');
    }

    public function liveSessions()
    {
        return $this->hasMany('App\Models\LiveSession');
    }

    public function scopeOfUser($query, $user_id)
    {
        return $query->where('user_id', '=', $user_id);
    }

    public function scopeIsPublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeIsApproved($query)
    {
        return $query->where('approval_status', self::APPROVAL_STATUS_APPROVED);
    }

    public function scopeWithCategory($query, $courseCategoryId)
    {   
        if ($courseCategoryId) { 
            $courseCategoryArray = [0 => $courseCategoryId];
            return $query->whereJsonContains('course_categories',$courseCategoryArray);
        } else {
            return $query;
        }
    }

    public function scopeWithLevel($query, $levelId)
    {
        if ($levelId) {
            return $query->where('course_level_id', $levelId);
        } else {
            return $query;
        }
    }

    public function scopeWithType($query, $typeId)
    {
        if ($typeId) {
            return $query->where('course_type_id', $typeId);
        } else {
            return $query;
        }
    }

    public function scopeWithApprovalStatus($query, $approval_status)
    {
        if ($approval_status != null) {
            return $query->where('approval_status', $approval_status);
        } else {
            return $query;
        }
    }

    public function scopeWithUploadedBy($query, $uploaded_by)
    {
        if ($uploaded_by != null) {
            return $query->where('user_id', $uploaded_by);
        } else {
            return $query;
        }
    }

    public function scopeWithSearch($query, $search)
    {
        if ($search) {
            $LIKE = config('cms.search_operator');
            $search = mm_search_string($search);
            $value = format_like_query($search);

            return $query->where('title', $LIKE, $value)
                        ->orWhere('description', $LIKE, $value);

            // Supports Keyword search
            // return $query->orWhereHas('keywords', function ($q) use ($search) {
            //     $q->whereIn('keyword', [$search]);
            // });
        }
        return $query;

    }

    public function getApprovalStatus()
    {
        if ($this->approval_status !== null) {
            return self::APPROVAL_STATUS[$this->approval_status];
        }

        return null;
    }

    public function getLevel()
    {
        $levels = \App\Models\CourseLevel::pluck('value', 'id')->toArray();
        //dd($levels);exit;
        return $levels[$this->course_level_id]; 
    }

    public function getType()
    {   
        $types = \App\Models\CourseType::pluck('name', 'id')->toArray();
        return $types[$this->course_type_id]; // TODO: to modify this function
    }
    // public function getEstimatedUnits()
    // {
    //     return self::ESTIMATED_DURATION_UNIT;
    // }
    public function getEstimatedDurationUnit()
    {   
        return $this->estimated_duration_unit; 
    }
    
    public function isDisplayVideo() 
    {
        return $this->is_display_video;
    }
    public function getVideoLink()
    {
        return $this->video_link;
    }
    public function isAutoCompletion() 
    {
        return $this->is_auto_completion;
    }
    

    public function courseLearners()
    {
        return $this->belongsToMany(User::class, 'course_learners', 'course_id', 'user_id')
            ->withPivot('status','percentage'); //get the user data back and course_learner data as pivot_status, pivot_percentage
    }

    public function courseCancelLearners()
    {
        return $this->belongsToMany(User::class, 'course_cancel_learners', 'course_id', 'user_id')
            ->withPivot('created_at'); 
    }

    public function getThumbnailPath()
    {
        return optional($this->getMedia('course_cover_image')->first())->getUrl('thumb');
    }

    public function getMediumPath()
    {
        return optional($this->getMedia('course_cover_image')->first())->getUrl('medium');
    }

    public function getImagePath() 
    {
        return optional($this->getMedia('course_cover_image')->first())->getUrl('large');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('course_cover_image')
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
        $this->addMediaCollection('course_resource_file')
            ->singleFile();

    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => strip_tags('title')
            ]
        ];
    }
    
    public function scopePrivacyFilter($query, $user_type)
    {
        $query->whereHas('privacies', function ($q) use ($user_type) {
            $q->where('user_type', $user_type);

            if ($user_type != User::TYPE_GUEST) {
                $q->orWhere('user_type', User::TYPE_GUEST);
            }
        });
    }

    public function scopeLanguageFilter($query, $lang)
    {
        if ($lang=='en') {
            return $query->where('lang', '=', 'en')->orWhere('lang','=','both');
        } else {
            return $query->where('lang', '=', 'my-MM')->orWhere('lang','=','both');
        }
    }
}
