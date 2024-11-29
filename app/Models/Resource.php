<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\User;
use App\Traits\Unicodeable;
use App\Traits\Reviewable;

class Resource extends Model implements HasMedia
{
    use Sortable, Sluggable, HasMediaTrait, Unicodeable, Reviewable;

    protected $table = 'resources';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title', 'slug', 'published', 'description', 'resource_format', 'user_id', 'license_id', 'strand',
        'sub_strand', 'lesson', 'author', 'publisher', 'publishing_year', 'publishing_month',
        // 'suitable_for_ec_year',
        'url', 'additional_information', 'approved', 'allow_feedback', 'allow_download', 'is_featured',
        'allow_edit', 'is_locked'
    ];

    const ASSESSMENT = 'assessment';
    const AUDIO = 'audio';
    const VIDEO = 'video';
    const IMAGE = 'image';
    const GAME = 'game';
    const LESSON_PLAN = 'lesson_plan';
    const PUBLICATION = 'publication';
    const PRESENTATION_FILE = 'presentation_file';
    const REFERENCE_MATERIAL = 'reference_material';
    const SOFTWARE_DIGITAL_TOOL = 'software_digital_tool';
    const SYLLABI = 'syllabi';
    const TEXTBOOK_TEACHER_GUIDE = 'textbook_teacher_guide';
    const TEACHER_EDUCATOR_TRAINING_MATERIAL = 'teacher_educator_training_material';
    const WEB_LINK = 'web_link';
    const WORKSHEET = 'worksheet';
    const OTHERS = 'others';

    const RESOURCE_FORMATS = [
        self::ASSESSMENT => 'Assessment',
        self::AUDIO => 'Audio',
        self::VIDEO => 'Video',
        self::IMAGE => 'Image',
        self::GAME => 'Game',
        self::LESSON_PLAN => 'Lesson Plan',
        self::PUBLICATION => 'Publication',
        self::PRESENTATION_FILE => 'Presentation File',
        self::REFERENCE_MATERIAL => 'Reference Material',
        self::SOFTWARE_DIGITAL_TOOL => 'Software Digital Tool',
        self::SYLLABI => 'Syllabi',
        self::TEXTBOOK_TEACHER_GUIDE => 'Textbook Teacher Guide',
        self::TEACHER_EDUCATOR_TRAINING_MATERIAL => 'Teacher Educator Training Material',
        self::WEB_LINK => 'Web Link',
        self::WORKSHEET => 'Worksheet',
        self::OTHERS => 'Others',
    ];

    const APPROVAL_STATUS_PENDING = 0;
    const APPROVAL_STATUS_APPROVED = 1;
    const APPROVAL_STATUS_REJECTED = 2;

    const APPROVAL_STATUS = [
        self::APPROVAL_STATUS_PENDING => 'Pending',
        self::APPROVAL_STATUS_APPROVED => 'Approved',
        self::APPROVAL_STATUS_REJECTED => 'Rejected',
    ];

    public $sortable = [
        'id',
        'title',
        'approved_at',
        'approved_by',
        'user_id',
        'created_at',
        'updated_at'
    ];

    public $unicodeFields = [
        'title',
        'description',
        'strand',
        'sub_strand',
        'lesson',
        'author',
        'publisher',
        // 'publishing_year',
        'additional_information',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function advanced_search_keys()
    {
        return [
            '1' => 'Author',
            '2' => 'Title',
            '3' => 'Strand',
            '4' => 'Keywords',
            '5' => 'Suitable for Education College Year(s)',
            '6' => 'Type of copyright license',
            '7' => 'File size',
            '8' => 'Accessible right',
        ];
    }

    public function collegeYears()
    {
        return [
            '1' => 'Year 1',
            '2' => 'Year 2',
            '3' => 'Year 3',
            '4' => 'Year 4',
        ];
    }

    public function scopeIsFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeIsPublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeIsApproved($query)
    {
        return $query->where('approval_status', self::APPROVAL_STATUS_APPROVED);
    }

    public function path()
    {
        return route('resource.show', $this->slug);
    }

    public function favourites()
    {
        return $this->belongsToMany('App\Models\Resource', 'favourites', 'resource_id', 'user_id')->withTimestamps();
    }

    /**
     * Determine whether a post has been marked as favorite by a user.
     *
     * @return boolean
     */
    public function favourited()
    {
        return (bool) Favourite::where('user_id', auth()->id())
                        ->where('resource_id', $this->id)
                        ->first();
    }

    public function subjects()
    {
        return $this->belongsToMany('App\Models\Subject', 'resource_subject', 'resource_id', 'subject_id');
    }

    public function years()
    {
        return $this->belongsToMany('App\Models\Year', 'resource_year', 'resource_id', 'year_id');
    }

    public function related()
    {
        return $this->hasMany('App\Models\RelatedResource', 'resource_id');
    }

    public function license()
    {
        return $this->belongsTo('App\Models\License', 'license_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo('App\User', 'approved_by');
    }

    public function approvalrequests()
    {
        return $this->hasMany('App\Models\ApprovalRequest', 'resource_id');
    }

    public function original()
    {
        return $this->belongsTo('App\Models\Resource', 'original_resource_id');
    }

    public function formats()
    {
        return $this->belongsToMany(Format::class, 'resource_format', 'resource_id', 'format_id');
    }

    public function keywords()
    {
        return $this->belongsToMany('App\Models\Keyword', 'keyword_resource', 'resource_id', 'keyword_id')
            ->withPivot('provided_by', 'user_id')->withTimestamps();
    }

    public function keywordList($providedBy = 'creator', $user_id = null)
    {
        return $this->keywords()
            ->wherePivot('provided_by', $providedBy)
            //->select('keyword', \DB::raw(''))
            ->selectRaw('`keyword` as id, `keyword`')
            //->wherePivot('user_id', $user_id)
            ->pluck('keyword', 'id');
        //->toArray();
    }

    public function getApprovalStatus()
    {
        if ($this->approval_status !== null) {
            return self::APPROVAL_STATUS[$this->approval_status];
        }

        return null;
    }

    public function getResourceFormat()
    {
        if ($this->resource_format !== null) {
            if (isset(self::RESOURCE_FORMATS[$this->resource_format])) {
                return self::RESOURCE_FORMATS[$this->resource_format];
            }
        }

        return null;
    }

    public function scopeWithAllSearch($query, $search)
    {
        if ($search) {
            $LIKE = config('cms.search_operator');
            $value = format_like_query($search);

            return $query->where('title', $LIKE, $value)
                        ->orWhere('description', $LIKE, $value)
                        ->orWhere('strand', $LIKE, $value)
                        ->orWhere('sub_strand', $LIKE, $value)
                        ->orWhere('author', $LIKE, $value);
        } else {
            return $query;
        }
    }

    public function scopeWithSubject($query, $subject_id)
    {
        if ($subject_id) {
            return $query->join('resource_subject', 'resources.id', 'resource_subject.resource_id')
                    ->where('resource_subject.subject_id', $subject_id)
                    ->select('resources.*');
        } else {
            return $query;
        }
    }

    public function scopeWithSubjectList($query, $subjects)
    {
        if ($subjects) {
            $query->whereHas('subjects', function ($q) use ($subjects) {
                if (is_array($subjects)) {
                    $q->whereIn('subject_id', $subjects);
                } else {
                    $q->whereIn('subject_id', explode(',', $subjects));
                }
            });
        }
    }

    public function scopeWithResourceFormats($query, $format_id)
    {
        if ($format_id) {
            return $query->join('resource_format', 'resources.id', 'resource_format.resource_id')
                    ->where('resource_format.format_id', $format_id)
                    ->select('resources.*');
        } else {
            return $query;
        }
    }

    public function scopeWithBulkResources($query, $id_array)
    {
        if ($id_array) {
            return $query->whereIn('id', $id_array);
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

    public function scopeWithoutCurrentResource($query, $slug)
    {
        if ($slug) {
            return $query->where('slug', '<>', $slug);
        } else {
            return $query;
        }
    }

    public function privacies()
    {
        return $this->hasMany('App\Models\ResourcePrivacy', 'resource_id');
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

    public function scopeOfUser($query, $user_id)
    {
        return $query->where('user_id', '=', $user_id);
    }

    public function scopeWithSearch($query, $search)
    {
        if ($search) {
            $LIKE = config('cms.search_operator');
            $search = mm_search_string($search);
            $value = format_like_query($search);

            $query->where('title', $LIKE, $value)
                        ->orWhere('description', $LIKE, $value)
                        ->orWhere('author', $LIKE, $value)
                        ->orWhere('publisher', $LIKE, $value)
                        ->orWhere('strand', $LIKE, $value)
                        ->orWhere('sub_strand', $LIKE, $value)
                        ->orWhere('lesson', $LIKE, $value);

            // Supports Keyword search
            return $query->orWhereHas('keywords', function ($q) use ($search) {
                $q->whereIn('keyword', [$search]);
            });
        } else {
            return $query;
        }
    }

    public function scopeWithResourceFormat($query, $format)
    {
        if ($format) {
            return $query->where('resource_format', $format);
        } else {
            return $query;
        }
    }

    public function getThumbnailPath()
    {
        return optional($this->getMedia('resource_cover_image')->first())->getUrl('thumb');
    }

    public function getMediumPath()
    {
        return optional($this->getMedia('resource_cover_image')->first())->getUrl('medium');
    }

    public function getImagePath()
    {
        return optional($this->getMedia('resource_cover_image')->first())->getUrl('large');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('resource_cover_image')
                ->singleFile()
                ->registerMediaConversions(function (Media $media) {
                    $this
                    ->addMediaConversion('thumb')
                    ->width(200)
                    ->height(200);

                    $this
                    ->addMediaConversion('bthumb')
                    ->width(300)
                    ->height(300);

                    $this
                    ->addMediaConversion('medium')
                    ->width(400)
                    ->height(400);

                    $this
                    ->addMediaConversion('large')
                    ->width(800)
                    ->height(800);
                });

        $this->addMediaCollection('resource_previews')
            ->singleFile();

        $this->addMediaCollection('resource_full_version_files')
            ->singleFile();
    }

    public function deleteFiles()
    {
        $files = glob(public_path('videos/temp/') . '*');
        $now = time();

        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= 60 * 60 * 24 * 2) { // 2 days
                    unlink($file);
                }
            }
        }
    }
}
