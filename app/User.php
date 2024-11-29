<?php

namespace App;

use App\Models\Course;
use App\Models\Lecture;
use App\Models\AssignmentUser;
use App\Models\LongAnswerUser;
use App\Models\AssessmentUser;
use App\Models\EvaluationUser;
use App\Models\DiscussionMessage;
use App\Models\RatingReview;
use App\Models\LiveSessionUser;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use League\OAuth2\Server\Exception\OAuthServerException;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Traits\Unicodeable;
use App\Models\Year;

class User extends Authenticatable implements HasMedia
{
    use Notifiable, Sortable, HasRoles, HasMediaTrait, HasApiTokens, Unicodeable;

    const TYPE_ADMIN = 'admin';
    const TYPE_MANAGER = 'manager';
    const TYPE_TEACHER_EDUCATOR = 'teacher_educator';
    const TYPE_STUDENT_TEACHER = 'student_teacher';
    const TYPE_GUEST = 'guest';
    //ADDED for E-Learning
    const TYPE_LEARNER = 'independent_learner';
    const TYPE_EDUCATION_STAFF = 'ministry_of_education_staff';
    const TYPE_COLLEGE_TEACHING_STAFF = 'education_college_teaching_staff';
    const TYPE_COLLEGE_NON_TEACHING_STAFF = 'education_college_non_teaching_staff';
    const TYPE_COLLEGE_STUDENT_TEACHER = 'education_college_student_teacher';
    const TYPE_STAFF = 'staff';

    const ACCOUNT_TYPE_TEACHER = 1;
    const ACCOUNT_TYPE_LEARNER = 2;

    const ACCOUNT_VALUE_TEACHER = 'Teacher';
    const ACCOUNT_VALUE_LEARNER = 'Learner';
    //ADDED for CI
    const TYPE_JOURNALIST = 'journalist';
    const TYPE_INDEPENDENT_LEARNER = 'independent_learner';
    const TYPE_UNESCO_STAFF = 'unesco_staff';
    const TYPE_INDEPENDENT_TEACHER = 'independent_teacher';

    const VALUE_EDUCATION_STAFF = 'Ministry of Education Staff';
    const VALUE_COLLEGE_TEACHING_STAFF = 'Education Degree College Teaching Staff';
    const VALUE_COLLEGE_NON_TEACHING_STAFF = 'Education Degree College Non-teaching Staff';
    const VALUE_COLLEGE_STUDENT_TEACHER = 'Education Degree College Student Teacher';
    const VALUE_STAFF = 'Staff';
    //ADDED for CI
    const VALUE_JOURNALIST = 'Journalist';
    //ADDED for E-Learning
    const VALUE_LEARNER = 'Independent Learner';
    const VALUE_UNESCO_STAFF = 'UNESCO Staff';
    const VALUE_INDEPENDENT_TEACHER = 'Independent Teacher';

    const STAFF_TYPES = [
        self::TYPE_EDUCATION_STAFF => 'Ministry of Education Staff',
        self::TYPE_COLLEGE_TEACHING_STAFF => 'Education Degree College Teaching Staff',
        self::TYPE_COLLEGE_NON_TEACHING_STAFF => 'Education Degree College Non-teaching Staff',
        self::TYPE_COLLEGE_STUDENT_TEACHER => 'Education Degree College Student Teacher',
        self::TYPE_STAFF => 'Guest'
    ];

    const TYPES = [ //In use
        self::TYPE_ADMIN => 'Admin',
        self::TYPE_MANAGER => 'Manager',
        self::TYPE_TEACHER_EDUCATOR => 'Teacher Educator',
        self::TYPE_STUDENT_TEACHER => 'Student Teacher',
        self::TYPE_JOURNALIST => 'Journalist',
        self::TYPE_LEARNER => 'Independent Learner',
        self::TYPE_INDEPENDENT_TEACHER => 'Independent Teacher',
        self::TYPE_GUEST => 'Guest'
    ];

    const TYPES_FOR_EDC = [ //In use
        self::TYPE_ADMIN => 'Admin',
        self::TYPE_MANAGER => 'Manager',
        self::TYPE_TEACHER_EDUCATOR => 'Teacher Educator',
        self::TYPE_STUDENT_TEACHER => 'Student Teacher'
    ];

    const APPROVAL_STATUS_PENDING = 0;
    const APPROVAL_STATUS_APPROVED = 1;
    const APPROVAL_STATUS_BLOCKED = 2;

    const APPROVAL_STATUS = [
        self::APPROVAL_STATUS_PENDING => 'Pending',
        self::APPROVAL_STATUS_APPROVED => 'Approved',
        self::APPROVAL_STATUS_BLOCKED => 'Blocked',
    ];

    const GENDER_TYPES = ['male','female','others'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'country_code', 'mobile_no', 'notification_channel', 'ec_college', 'id',
        'gender', 'affiliation', 'account_type', 'position', 'organization', 'last_login', 'is_unesco_mgr'
    ]; 

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $sortable = [
        'id',
        'name',
        'username',
        'email',
        'created_at',
        'updated_at'
    ];

    public $unicodeFields = [
        'name',
        //'ec_college',
    ];

    public function getApprovalStatus()
    {
        if ($this->approved !== null) {
            if (isset(self::APPROVAL_STATUS[$this->approved])) {
                return self::APPROVAL_STATUS[$this->approved];
            }
        }

        return null;
    }

    public function getYears()
    {
        $years = [];

        if ($this->suitable_for_ec_year) {
            if (strpos($this->suitable_for_ec_year, ',') !== false) {
                $ec_years = explode(',', $this->suitable_for_ec_year);
            } else {
                $ec_years[] = $this->suitable_for_ec_year;
            }

            $years = Year::whereIn('id', $ec_years)
                ->select('id', 'title')->get();
        }

        return $years;
    }

    public function getStaffType()
    {
        if ($this->user_type) {
            return isset(self::STAFF_TYPES[$this->user_type]) ? self::STAFF_TYPES[$this->user_type] : null;
        }
    }

    public function getType()
    {
        if ($this->type) {
            return isset(self::TYPES[$this->type]) ? self::TYPES[$this->type] : null;
        }
    }

    public function isAdmin()
    {
        return $this->type === self::TYPE_ADMIN;
    }

    public function isManager()
    {
        return $this->type === self::TYPE_MANAGER;
    }

    public function isUnescoManager()
    {
        return ( $this->type === self::TYPE_MANAGER && $this->is_unesco_mgr === 1 );
    }

    public function isTeacherEducator()
    {
        return $this->type === self::TYPE_TEACHER_EDUCATOR;
    }

    public function isStudentTeacher()
    {
        return $this->type === self::TYPE_STUDENT_TEACHER;
    }

    public function isJournalist()
    {
        return $this->type === self::TYPE_JOURNALIST;
    }

    public function isIndependentLearner()
    {
        return $this->type === self::TYPE_LEARNER;
    }

    public function scopeWithCollege($query, $college_id)
    {
        if ($college_id) {
            return $query->where('ec_college', '=', $college_id);
        } else {
            return $query;
        }
    }

    public function scopeWithRole($query, $role_name)
    {
        if ($role_name) {
            // The HasRoles trait also adds a role scope to your models to scope
            // the query to certain roles or permissions:
            // $users = User::role('writer')->get(); // Returns only users with the role 'writer'
            return $query->role($role_name); // technically $role_id is Role's Name
        } else {
            return $query;
        }
    }

    public function scopeWithType($query, $type)
    {
        if ($type) {
            return $query->where('type', '=', $type);
        } else {
            return $query;
        }
    }

    public function scopeWithSearch($query, $search)
    {
        if ($search) {
            $query->where('name', 'LIKE', "%$search%");
            $query->orWhere('username', 'LIKE', "%$search%");
            $query->orWhere('mobile_no', 'LIKE', "%$search%");
            return $query->orWhere('email', 'LIKE', "%$search%");
        } else {
            return $query;
        }
    }

    public function scopeWithBlocked($query, $blocked = null)
    {
        if ($blocked != null) {
            return $query->where('blocked', $blocked);
        } else {
            return $query;
        }
    }

    public function scopeWithVerified($query, $verified = null)
    {
        if ($verified != null) {
            return $query->where('verified', $verified);
        } else {
            return $query;
        }
    }

    public function scopeWithApproved($query, $approved = null)
    {
        if ($approved != null) {
            return $query->where('approved', $approved);
        } else {
            return $query;
        }
    }

    public function scopeWithoutMe($query)
    {
        $user = auth()->user();

        if ($user->id != null) {
            return $query->where('id', '!=', $user->id);
        } else {
            return $query;
        }
    }

    public function isEducationStaff()
    {
        return $this->type === self::TYPE_EDUCATION_STAFF;
    }

    public function isNonTeachingStaff()
    {
        return $this->type === self::TYPE_NON_TEACHING_STAFF;
    }

    public function isTeachingStaff()
    {
        return $this->type === self::TYPE_COLLEGE_TEACHING_STAFF;
    }

    public function isCollegeStudentTeacher()
    {
        return $this->type === self::TYPE_COLLEGE_STUDENT_TEACHER;
    }

    public function resources()
    {
        return $this->hasMany('App\Models\Resource', 'user_id');
    }

    public function favourites()
    {
        return $this->belongsToMany('App\Models\Resource', 'favourites', 'user_id', 'resource_id')->withTimestamps();
    }

    public function subjects()
    {
        return $this->belongsToMany('App\Models\Subject', 'user_subject', 'user_id', 'subject_id');
    }

    public function college()
    {
        return $this->belongsTo('App\Models\College', 'ec_college');
    }

    public function path()
    {
        return route('profile.show', $this->username);
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('profile')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(100)
                    ->height(100);

                $this
                    ->addMediaConversion('medium')
                    ->width(400)
                    ->height(400);

                $this
                    ->addMediaConversion('large')
                    ->width(800)
                    ->height(800);
            });
    }

    public function getThumbnailPath()
    {
        $thumbnail = optional($this->getMedia('profile')->first())->getUrl('thumb');

        if ($thumbnail) {
            return $thumbnail;
        }

        $default_thumbnail = asset('assets/img/avatar.png');

        return $default_thumbnail;
        // return optional($this->getMedia('profile')->first())->getUrl('thumb');
    }

    public function getProfileImagePath($size)
    {
        $thumbnail = optional($this->getMedia('profile')->first())->getUrl($size);

        if ($thumbnail) {
            return $thumbnail;
        }

        $default_thumbnail = asset('assets/img/avatar.png');

        return $default_thumbnail;
        // return optional($this->getMedia('profile')->first())->getUrl('thumb');
    }

    /*
    * Passport Authentication API
    * Laravel\Passport\Bridge\UserRepository;
    * Credit to: https://laracasts.com/discuss/channels/laravel/laravel-passport-login-using-username-or-email?page=1
    */
    public function findForPassport($identifier)
    {
        //return $this->orWhere('email', $identifier)->orWhere('username', $identifier)->where('approved', 1)->first();
        $user = $this->orWhere('email', $identifier)->orWhere('username', $identifier)->first();

        if (!$user) {
            throw new OAuthServerException('The user credentials were incorrect.', 6, 'invalid_credentials', 401);
        }

        if ($user !== null && $user->verified == 0) {
            throw new OAuthServerException('User account is not verified.', 6, 'account_unverified', 401);
        }

        if ($user !== null && $user->approved == 0) {
            throw new OAuthServerException('User account is not approved.', 6, 'account_inactive', 401);
            // default error
            // throw new OAuthServerException('The user credentials were incorrect.', 6, 'invalid_credentials', 401);
        }

        if ($user !== null && $user->blocked == 1) {
            throw new OAuthServerException('User account has been blocked.', 6, 'account_blocked', 401);
        }

        return $user;
    }

    /*
     * Handling Oauth Error

     * Credit to : https://github.com/laravel/passport/issues/81 *
     https://stackoverflow.com/questions/41656116/how-do-i-modify-error-messages-from-passport-oauth-in-laravel-5-3
     https://github.com/laravel/passport/issues/289#issuecomment-291645481
     https://gist.github.com/deividaspetraitis/7c3958381d33a06b511d17e2e22b8bb5
    */
    public function courses()
    {
        return $this->hasMany('App\Models\Course', 'user_id');
    }
    
    public function learningCourses()
    {
        return $this->belongsToMany(Course::class, 'course_learners', 'user_id', 'course_id') //->where('status',1)
            ->withPivot('status');
    }

    public function learningLectures()
    {
        return $this->belongsToMany(Lecture::class, 'learner_lectures', 'user_id', 'lecture_id');
    }

    public function assignment_user()
    {
        return $this->hasMany(AssignmentUser::class);
    }

    public function long_answer_user()
    {
        return $this->hasMany(LongAnswerUser::class);
    }

    public function assessment_user()
    {
        return $this->hasMany(AssessmentUser::class);
    }

    public function livesession_user()
    {
        return $this->hasMany(LiveSessionUser::class);
    }

    public function evaluation_user()
    {
        return $this->hasMany(EvaluationUser::class);
    }

    public function discussion_message_users()
    {
        return $this->hasMany(DiscussionMessage::class);
    }

    public function rating_users()
    {
        return $this->hasMany(RatingReview::class);
    }

    public static function checkLogin()
    {
        return auth()->check();
    }

    public static function getUserById($userId)
    {
        return User::find($userId); //->toArray();
    }

    public static function getUsersByEcId($ecId)
    {
        return User::where('ec_college', $ecId)->pluck('username', 'id'); //return array ( id => name )
    }
}
