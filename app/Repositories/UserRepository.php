<?php

namespace App\Repositories;

use App\User;
use App\Models\Course;
use App\Models\CourseLearner;
use App\Models\College;
use Illuminate\Http\Request;
use Exception;
use stdClass;
use DB;

class UserRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public static function getUsers($types)
    {
        return User::whereIn('type', $types)->get();
    }

    public static function getAdminAndManager()
    {
        return self::getUsers([User::TYPE_ADMIN, User::TYPE_MANAGER]);
    }

    public static function getActiveUsers()
    {
        return User::where('approved', User::APPROVAL_STATUS_APPROVED)
                    ->where('verified', 1)
                    ->where('blocked', '!=', 1)
                ->get();
    }

    /**
     * Get the list of Student Teacher who subscribe to new resources
     *
     * @return void
     */
    public static function getStudentTeachers()
    {
        return User::where('approved', User::APPROVAL_STATUS_APPROVED)
                    ->where('verified', 1)
                    ->where('subscribe_to_new_resources', 1)
                    ->where('type', User::TYPE_STUDENT_TEACHER)
                    ->where('blocked', '!=', 1)
                ->get();
    }

    public static function getAdminAndManagerOfSameCollege($user)
    {
        $types = [User::TYPE_ADMIN, User::TYPE_MANAGER];

        return User::where('type', User::TYPE_ADMIN)
                    ->orWhere(function ($query) use ($user) {
                        $query->where('type', User::TYPE_MANAGER)
                            ->where('ec_college', '=', $user->ec_college);
                    })
                ->get();
    }

    /**
     * Get users who have resource upload permissions
     */
    public static function getAllUploaders()
    {
        $types = [User::TYPE_ADMIN, User::TYPE_MANAGER, User::TYPE_TEACHER_EDUCATOR];

        return User::select('id', 'name')
            ->whereIn('type', $types)
            ->orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');
    }

    /**
     * Get users who have resource upload permissions
     */
    public static function getAllUploadersFromSameCollege()
    {
        $types = [User::TYPE_ADMIN, User::TYPE_MANAGER, User::TYPE_TEACHER_EDUCATOR];

        if (auth()->user()->ec_college) {
            return User::select('id', 'name')
            ->whereIn('type', $types)
            ->where('ec_college', auth()->user()->ec_college)
            ->orderBy('name', 'ASC')
            ->get()
            ->pluck('name', 'id');
        }

        return null;
    }

    public static function getTypes($includingGuest = true)
    {
        $types = [
            User::TYPE_ADMIN => trans(User::TYPE_ADMIN),
            User::TYPE_MANAGER => trans(User::TYPE_MANAGER),
            User::TYPE_TEACHER_EDUCATOR => trans(User::TYPE_TEACHER_EDUCATOR),
            User::TYPE_STUDENT_TEACHER => trans(User::TYPE_STUDENT_TEACHER),
            User::TYPE_JOURNALIST => trans(User::TYPE_JOURNALIST),
            User::TYPE_INDEPENDENT_LEARNER => trans(User::TYPE_INDEPENDENT_LEARNER),
            User::TYPE_INDEPENDENT_TEACHER => trans(User::TYPE_INDEPENDENT_TEACHER)
        ];

        if ($includingGuest) {
            $types[User::TYPE_GUEST] = trans(User::TYPE_GUEST);
        }

        $types = ['' => trans('- Select Accessible Right -')] + $types;

        return $types;
    }

    public static function getTypesList($includingGuest = true)
    {
        $types = [
            User::TYPE_ADMIN => User::TYPE_ADMIN,
            User::TYPE_MANAGER => User::TYPE_MANAGER,
            User::TYPE_TEACHER_EDUCATOR => User::TYPE_TEACHER_EDUCATOR,
            User::TYPE_STUDENT_TEACHER => User::TYPE_STUDENT_TEACHER,
        ];

        if ($includingGuest) {
            $types[User::TYPE_GUEST] = User::TYPE_GUEST;
        }

        return $types;
    }

    public static function getTeacherUserTypes($includePlaceHolder = false)
    {
        $types = [
            //User::TYPE_EDUCATION_STAFF => trans(User::VALUE_EDUCATION_STAFF),
            USER::TYPE_INDEPENDENT_TEACHER => trans(User::VALUE_INDEPENDENT_TEACHER),
            User::TYPE_COLLEGE_TEACHING_STAFF => trans(User::VALUE_COLLEGE_TEACHING_STAFF),
            //User::TYPE_COLLEGE_NON_TEACHING_STAFF => trans(User::VALUE_COLLEGE_NON_TEACHING_STAFF),
            //User::TYPE_COLLEGE_STUDENT_TEACHER => trans(User::VALUE_COLLEGE_STUDENT_TEACHER),
            //USER::TYPE_INDEPENDENT_TEACHER => trans(User::VALUE_INDEPENDENT_TEACHER)
        ];

        if ($includePlaceHolder) {
            $types = ['' => trans('- Select Type of Users -')] + $types;
        }

        return $types;
    }

    public static function getUserTypes($includePlaceHolder = false)
    {
        $types = [
            User::TYPE_EDUCATION_STAFF => trans(User::VALUE_EDUCATION_STAFF),
            User::TYPE_COLLEGE_TEACHING_STAFF => trans(User::VALUE_COLLEGE_TEACHING_STAFF),
            User::TYPE_COLLEGE_NON_TEACHING_STAFF => trans(User::VALUE_COLLEGE_NON_TEACHING_STAFF),
            User::TYPE_COLLEGE_STUDENT_TEACHER => trans(User::VALUE_COLLEGE_STUDENT_TEACHER),
            //ADDED for CI
            User::TYPE_JOURNALIST => trans(User::VALUE_JOURNALIST),
            //ADDED for E-Learning
            User::TYPE_LEARNER => trans(User::VALUE_LEARNER),
            User::TYPE_INDEPENDENT_TEACHER => trans(User::VALUE_INDEPENDENT_TEACHER),
            User::TYPE_UNESCO_STAFF => trans(User::VALUE_UNESCO_STAFF)
        ];

        if ($includePlaceHolder) {
            $types = ['' => trans('- Select Type of Users -')] + $types;
        }

        return $types;
    }

    public static function getUserTypesForRegister($includePlaceHolder = false)
    {
        $types = [
            User::TYPE_LEARNER => trans(User::VALUE_LEARNER),
            User::TYPE_JOURNALIST => trans(User::VALUE_JOURNALIST),
            //User::TYPE_EDUCATION_STAFF => trans(User::VALUE_EDUCATION_STAFF),
            //User::TYPE_COLLEGE_TEACHING_STAFF => trans(User::VALUE_COLLEGE_TEACHING_STAFF),
            //User::TYPE_COLLEGE_NON_TEACHING_STAFF => trans(User::VALUE_COLLEGE_NON_TEACHING_STAFF),
            User::TYPE_COLLEGE_STUDENT_TEACHER => trans(User::VALUE_COLLEGE_STUDENT_TEACHER)
             
        ];

        if ($includePlaceHolder) {
            $types = ['' => trans('- Select Type of Users -')] + $types;
        }

        return $types;
    }

    public static function getUserTypesTeacher($includePlaceHolder = false)
    {
        $types = [
            User::TYPE_LEARNER => trans(User::VALUE_LEARNER),
            User::TYPE_JOURNALIST => trans(User::VALUE_JOURNALIST),
            User::TYPE_EDUCATION_STAFF => trans(User::VALUE_EDUCATION_STAFF),
            User::TYPE_COLLEGE_TEACHING_STAFF => trans(User::VALUE_COLLEGE_TEACHING_STAFF),
            User::TYPE_COLLEGE_NON_TEACHING_STAFF => trans(User::VALUE_COLLEGE_NON_TEACHING_STAFF),
            User::TYPE_COLLEGE_STUDENT_TEACHER => trans(User::VALUE_COLLEGE_STUDENT_TEACHER)        
        ];

        if ($includePlaceHolder) {
            $types = ['' => trans('- Select Type of Users -')] + $types;
        }

        return $types;
    }

    public static function getAccountTypes($includePlaceHolder = false)
    {
        $types = [
            User::ACCOUNT_TYPE_LEARNER => trans(User::ACCOUNT_VALUE_LEARNER),
            User::ACCOUNT_TYPE_TEACHER => trans(User::ACCOUNT_VALUE_TEACHER),
        ];

        if ($includePlaceHolder) {
            $types = ['' => trans('- Select Account Type -')] + $types;
        }

        return $types;
    }
    public static function getShortName($name)
    {
        $text = $name;
        preg_match_all('/\b\w/', $name, $match);

        if (is_array($match[0])) {
            $text = implode('', $match[0]);
        }

        return $text;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = $this->model
                        ->with(['media', 'subjects', 'college'])
                        ->withSearch($request->input('search'))
                        ->withApproved($request->input('approved'))
                        ->sortable(['updated_at' => 'desc'])
                        ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return $posts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForUnescoManager(Request $request)
    {
        $posts = $this->model
                        ->where('type','!=','admin')
                        ->where('is_unesco_mgr',0)
                        ->with(['media', 'subjects', 'college'])
                        ->withSearch($request->input('search'))
                        ->withApproved($request->input('approved'))
                        ->sortable(['updated_at' => 'desc'])
                        ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return $posts;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForManager(Request $request)
    {
        $user = auth()->user();

        if ($college = College::find($user->ec_college)) {
            $posts = $this->model
                        ->with(['media', 'subjects', 'college'])
                        ->where('ec_college', '=', $user->ec_college)
                        ->withSearch($request->input('search'))
                        ->withoutMe()
                        ->withApproved($request->input('approved'))
                        ->sortable(['updated_at' => 'desc'])
                        ->paginate($request->input('limit'));

            $posts->appends($request->all());

            return $posts;
        }

        return null;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForTeacherEducator(Request $request)
    {
        $user = auth()->user();

        if ($college = College::find($user->ec_college)) {
            $posts = $this->model
                        ->with(['media', 'subjects', 'college'])
                        ->withType(User::TYPE_STUDENT_TEACHER)
                        ->where('ec_college', '=', $user->ec_college)
                        ->withSearch($request->input('search'))
                        ->withoutMe()
                        ->withApproved($request->input('approved'))
                        ->sortable(['updated_at' => 'desc'])
                        ->paginate($request->input('limit'));

            $posts->appends($request->all());

            return $posts;
        }

        return null;
    }

    public function updateStatus($id, $action)
    {
        $post = User::findOrFail($id);

        // $this->isValidToApprove($id);

        if ($action == 'approve') {
            $post->approved = User::APPROVAL_STATUS_APPROVED;
            //$post->approved_by = auth()->user()->id;
            $text = 'approved';
        } elseif ($action == 'undo') {
            $post->approved = User::APPROVAL_STATUS_PENDING;
            $text = 'undo';
        } else {
            $post->approved = User::APPROVAL_STATUS_BLOCKED;
            $text = 'blocked';
        }

        $post->save();

        /*         // notify to admin and manager users
                if ($action == 'approve' || $action == 'reject') {
                    $users = UserRepository::getAdminAndManager();

                    Notification::send($users, new ResourceApprovalUpdated($post, $text));

                    // notify to submitted user
                    Notification::send($post->user, new ResourceApprovalUpdated($post, $text));
                } */

        return $text;
    }

    public function isValidToApprove($user_id)
    {
        $user = auth()->user();

        $targetUser = User::findOrFail($user_id);

        if ($user->id == $targetUser->id) {
            return redirect()->route('member.user.index')
            ->with('error', 'You can not update approval status for yourself.');
            throw new Exception('You can not update approval status for yourself.');
        }

        if ($user->type != User::TYPE_ADMIN) {
            throw new Exception('You can not update approval status for admin users.');
        }

        return true;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSignupUsersByPeriod(Request $request)
    {
        $posts = $this->model
                        ->with(['media', 'subjects', 'college'])
                        ->withSearch($request->input('search'))
                        ->withApproved($request->input('approved'))
                        ->sortable(['updated_at' => 'desc'])
                        ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return $posts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function getTotalSignupsByPeriod($startDate, $endDate)
    {   
        $result =  User::whereBetween('created_at', [ strval($startDate).' 00:00:00',
                                        strval($endDate).' 23:59:59'])->get(['created_at']); 
        $final = []; 
        foreach($result as $res) {
            $temp = new stdClass();
            $temp->created_at = $res->created_at->toDateString();
            //$temp->username = $res->username;
            array_push($final, $temp);
        }
       
        return $final;
    }

    // /**
    //  * Display a listing of the resource.
    //  *
    //  */
    // public static function getGenderData()
    // { 
    //     $result = DB::table('users')->get('gender');
    //     $finalData = new stdClass();
    //     $finalData->label = USER::GENDER_TYPES;
    //     $finalData->data = [0,0,0];
    //     for($i=0; $i < sizeof($result); $i++) { //echo $result[$i]->gender."\n";
    //         if($result[$i]->gender == 'male') $finalData->data[0]++;
    //             elseif($result[$i]->gender == 'female') $finalData->data[1]++;
    //               else $finalData->data[2]++;
    //     }  
    //     return $finalData;
    // }

    /**
     * Display a listing of the resource.
     *
     */
    public static function getGenderDataByDate($startDate, $endDate)
    { 
        if($startDate === null && $endDate === null) {
            $result = DB::table('users')->get('gender'); //dd(count($result)); exit;
        } else {
            $result = DB::table('users')->whereBetween('created_at', [ $startDate.' 00:00:00',
            $endDate.' 23:59:59'])->get(['gender']); //dd(count($result)); exit;
        }
        $finalData = new stdClass();
        $finalData->label = USER::GENDER_TYPES;
        $finalData->data = [0,0,0];
        for($i=0; $i < sizeof($result); $i++) { //echo $result[$i]->gender."\n";
            if($result[$i]->gender == 'male') $finalData->data[0]++;
                elseif($result[$i]->gender == 'female') $finalData->data[1]++;
                  else $finalData->data[2]++;
        }  
        return $finalData;
    }

    /**
     * Get list of users by ec_college
     *
     */
    public static function getUsersByEc()
    { 
        $totalUsers =  User::groupBy('ec_college')
                        ->selectRaw('count(id) as total, ec_college')
                        ->pluck('total','ec_college');
        // $totalManagers =  User::groupBy('ec_college')
        //                 ->where('user_type','ministry_of_education_staff')
        //                 ->selectRaw('count(id) as total, ec_college')
        //                 ->pluck('total','ec_college'); 
        $totalTeachers =  User::groupBy('ec_college')
                        ->where('user_type','education_college_teaching_staff')
                        ->selectRaw('count(id) as total, ec_college')
                        ->pluck('total','ec_college'); 
        $totalStudents =  User::groupBy('ec_college')
                        ->where('user_type','education_college_student_teacher')
                        ->selectRaw('count(id) as total, ec_college')
                        ->pluck('total','ec_college');     //dd($totalStudents);exit;          
        $ec = College::where('published',1)->get(); //pluck('title','id');
        
        $final = []; 
        foreach($ec as $c) { 
            $temp = new stdClass();
            $temp->ec_id = $c->id;
            $temp->ec_name = $c->title;
            $temp->total = isset($totalUsers[$c->id]) ? $totalUsers[$c->id] : 0;
            $temp->teachers = isset($totalTeachers[$c->id]) ? $totalTeachers[$c->id] : 0;
            $temp->students = isset($totalStudents[$c->id]) ? $totalStudents[$c->id] : 0;
            array_push($final, $temp);
        } 
        return $final;
    }

    /**
     * Get list of users by ec_college
     *
     */
    public static function getUserVisitPercentageByEc($startDate, $endDate)
    { 
        $totalUsers =  User::groupBy('ec_college')
                        ->selectRaw('count(id) as total, ec_college')
                        ->pluck('total','ec_college');
        
        $totalUsersWithinDate =  User::groupBy('ec_college')
                        ->whereBetween('last_login',[ $startDate.' 00:00:00',
                                                $endDate.' 23:59:59'])
                        ->selectRaw('count(id) as total, ec_college')
                        ->pluck('total','ec_college'); 
            
        $ec = College::where('published',1)->get(); //pluck('title','id');
        
        $final = []; 
        foreach($ec as $c) { 
            $temp = new stdClass();
            $temp->ec_id = $c->id;
            $temp->ec_name = $c->title;
            $temp->total = isset($totalUsers[$c->id]) ? $totalUsers[$c->id] : 0;
            $temp->totalVisitors = isset($totalUsersWithinDate[$c->id]) ? $totalUsersWithinDate[$c->id] : 0;
            $temp->percentage = isset($totalUsers[$c->id]) && isset($totalUsersWithinDate[$c->id]) ? ( $totalUsersWithinDate[$c->id] * 100 ) / $totalUsers[$c->id] : 0;
            array_push($final, $temp);
        } 
        return $final;
    }

    /**
     * Get list of users by ec_college
     *
     */
    public static function getUserVisitPercentageByRole($startDate, $endDate)
    { 
        $totalUsers =  User::groupBy('user_type')
                        ->selectRaw('count(id) as total, user_type')
                        ->pluck('total','user_type');
        
        $totalUsersWithinDate =  User::groupBy('user_type')
                        ->whereBetween('last_login',[ $startDate.' 00:00:00',
                                                $endDate.' 23:59:59'])
                        ->selectRaw('count(id) as total, user_type')
                        ->pluck('total','user_type'); 
            
        $types = self::getUserTypes(false); 
        
        $final = []; 
        foreach($types as $key=>$val) { //echo $key."--".$t;exit;
            $temp = new stdClass();
            $temp->user_type = $val;
            $temp->total = isset($totalUsers[$key]) ? $totalUsers[$key] : 0;
            $temp->totalVisitors = isset($totalUsersWithinDate[$key]) ? $totalUsersWithinDate[$key] : 0;
            $temp->percentage = isset($totalUsers[$key]) && isset($totalUsersWithinDate[$key]) ? ( $totalUsersWithinDate[$key] * 100 ) / $totalUsers[$key] : 0;
            array_push($final, $temp);
        }  //dd($final); exit;
        return $final;
    }

    public static function getUnreadNotifications()
    {
        $notifications = auth()->user()->notifications->where('read_at', null); // '!=',null
        return $notifications;
    }

    /**
     * Get the list of Student Teacher who subscribe to new resources
     *
     * @return void
     */
    public static function getLearnersTookTheCourseFromSameCategory($courseCategoryId)
    {   //TODO: to improve this feature in phase 1
        $learners =  User::join('courses as c', 'c.user_id','=','users.id')
                    ->select(DB::raw("users.*"))
                  //  ->join('courses as c','c.user_id','=','u.id')
                    ->where('users.approved', User::APPROVAL_STATUS_APPROVED)
                    ->where('users.verified', 1)
                    ->whereJsonContains('c.course_categories', [ 0 => $courseCategoryId ] )
                    ->where('users.blocked', '!=', 1)
                    //->where('users.id',14995)
                    //->Orwhere('users.id',14996)
                    ->get(); // dd($learners);exit;
        return $learners->unique(); // remove duplicates
    }

    public static function getUserCreatedCourses($userId)
    {
        $coursesCreatedByUser = Course::where('user_id',$userId)->get();
        return count($coursesCreatedByUser) > 0 ? true : false;
    }

    public static function getUserTakenCourses($userId)
    {
        $coursesTakenByUser = CourseLearner::where('user_id',$userId)->get();
        return count($coursesTakenByUser) > 0 ? true : false;
    }

    public static function getUserCancelCourses($userId)
    {
        $coursesTakenByUser = CourseLearner::where('user_id',$userId)->get();
        return count($coursesTakenByUser) > 0 ? true : false;
    }

    public static function getDiscussionMessageForCourse($userId)
    {
        $coursesTakenByUser = CourseLearner::where('user_id',$userId)->get();
        return count($coursesTakenByUser) > 0 ? true : false;
    }

    public static function getUserNameById($id)
    {
        $user = User::where('id', $id)->first();
        return $user ? $user->name : "deleted user - id(".$id.")";
    }

    public static function getUserEmailById($id)
    {
        $user = User::where('id', $id)->first();
        return $user ? $user->email : "deleted user - id(".$id.")";
    }

    public static function getUserById($id)
    {
        $user = User::where('id', $id)->first();
        return $user ? $user : null;
    }
}
