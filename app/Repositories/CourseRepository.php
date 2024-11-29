<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CoursePrivacy;
use App\Models\College;
use App\Models\Lecture;
use App\Models\LiveSession;
use App\Models\Summary;
use App\Models\LearningActivity;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\AssessmentQuestionAnswer;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewCoursePosted;
use Notification;
use stdClass;

class CourseRepository
{
    protected $model;

    public function __construct(Course $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the course.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $myCourses = $this->model
        //                 ->where(function ($query) {
        //                     $query->where('user_id', '=', auth()->user()->id);       
        //                 })
        //                 ->withSearch($request->input('search'))
        //                 ->withApprovalStatus($request->input('approval_status'))
        //                 ->withUploadedBy($request->input('uploaded_by'))
        //                 ->withCategory($request->input('course_category_id'))
        //                 ->withLevel($request->input('level_id'))->paginate(10);
        
        // $collaboratingCourses = ''; // course collaborator roles
        $posts = $this->model
            ->where(function ($query) {
                $query->where('user_id', '=', auth()->user()->id);
                $query->orWhere(function ($query) {
                    $query->whereJsonContains('collaborators', [ strval(auth()->user()->id) ]);
                });
                $query->orWhere(function ($query) {
                    $query->where('approval_status', '!=', null)
                          ->where('user_id', '!=', auth()->user()->id);
                });
            })
            ->withSearch($request->input('search'))
            ->withApprovalStatus($request->input('approval_status'))
            // ->where('approval_status', '!=', null)
            ->withUploadedBy($request->input('uploaded_by'))
            ->withCategory($request->input('course_category_id'))
            ->withLevel($request->input('level_id'))
            //->isPublished()
            ->sortable(['created_at' => 'desc'])
            // $request->input('limit')
            ->paginate(10);
      //  $combined = $myCourses->unionAll($posts)->get();
      //  $combined->paginate(10);
        $posts->appends($request->all());

        return $posts;
    }

    /**
     * Display a listing of the course.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexNoPaginate(Request $request)
    {
        $posts = $this->model
            ->where(function ($query) {
                $query->where('user_id', '=', auth()->user()->id);
                $query->orWhere(function ($query) {
                    $query->whereJsonContains('collaborators', [ strval(auth()->user()->id) ]);
                });
                $query->orWhere(function ($query) {
                    $query->where('approval_status', '!=', null)
                        ->where('user_id', '!=', auth()->user()->id);
                });
            })->get();
        //     ->withSearch($request->input('search'))
        //     ->withApprovalStatus($request->input('approval_status'))
        //     ->withUploadedBy($request->input('uploaded_by'))
        //     ->withCategory($request->input('course_category_id'))
        //     ->withLevel($request->input('level_id'))
        //     ->isPublished()
        //     ->sortable(['created_at' => 'desc']);
        //     // $request->input('limit')
        //     //->paginate(10);

        // $posts->appends($request->all());

        return $posts;
    }
    /**
     * Display a listing of the course.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForManager(Request $request)
    {
        $user = auth()->user();

        if ($college = College::find($user->ec_college)) {
            $posts = $this->model
                // ->with(['media'])
                ->whereHas('user', function ($q) use ($user) {
                    $q->where('ec_college', '=', $user->ec_college);
                })
                ->where(function ($query) {
                    $query->where('user_id', '=', auth()->user()->id);
                    $query->orWhere(function ($query) {
                        $query->where('approval_status', '!=', null)
                            ->where('user_id', '!=', auth()->user()->id);
                    });
                })
                ->withSearch($request->input('search'))
                ->withUploadedBy($request->input('uploaded_by'))
                ->withCategory($request->input('course_category_id'))
                ->withApprovalStatus($request->input('approval_status'))
                ->withLevel($request->input('level_id'))
                ->sortable(['created_at' => 'desc'])
                ->paginate($request->input('limit'));

            $posts->appends($request->all());
        }

        return $posts;
    }

    /**
     * Display a listing of the course.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForMember(Request $request)
    {
        $posts = $this->model
            // ->with(['media'])
           // ->ofUser(auth()->user()->id)
            ->where(function ($query) {
                $query->where('user_id', '=', auth()->user()->id);
                $query->orWhere(function ($query) {
                    $query->whereJsonContains('collaborators', [ strval(auth()->user()->id) ]);
                });
            })
            ->withSearch($request->input('search'))
            ->withCategory($request->input('course_category_id'))
            ->withLevel($request->input('level_id'))
            ->withApprovalStatus($request->input('approval_status'))
            ->sortable(['created_at' => 'desc'])
            ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return $posts;
    }
    
    public function updateLastModifiedPerson($userId)
    {
        $this->model->last_modified_by = $userId;
        $this->model->update();
    }

    public function saveRecord($request, $id = null)
    {   //dd($request->input('course_categories'));exit;
        if (isset($id)) {
            $this->model = $this->find($id);
            if(!$this->model->is_published && $request->has('is_published')) {
                /* status changed from not published to published */
                $course = Course::findOrFail($id); //dd(auth(0->user))
                if($course->user_id == auth()->user()->isAdmin() || $course->user_id == auth()->user()->isUnescoManager()
                     || $course->user_id == auth()->user()->isManager()) { // they can create their own course and no need to request, after approval request, there's a feature to send emails
                   // $learners = removeFakeEmails(UserRepository::getLearnersTookTheCourseFromSameCategory($this->model->course_categories));            
                    //dd(removeFakeEmails($learners));exit;
                    //\Log::info($learners);
                   // Notification::send($learners, new NewCoursePosted($course));
                }
                      
            } 
            $this->model->last_modified_by = auth()->user()->id; // can be owner or collaborator!      won't update the owner which is user_id   
        } else {
            $this->model->user_id = $this->model->last_modified_by = auth()->user()->id;
        }

        $this->model->fill($request->all());

        $this->model->is_published = $request->has('is_published') ? $request->input('is_published') : false;
        $this->model->allow_edit = $request->has('allow_edit') ? $request->input('allow_edit') : false;
        $this->model->allow_feedback = $request->has('allow_feedback') ? $request->input('allow_feedback') : false;
        $this->model->allow_discussion = $request->has('allow_discussion') ? $request->input('allow_discussion') : false;   
        $this->model->is_locked = $request->has('is_locked') ? $request->input('is_locked') : false;
        $this->model->is_display_video = $request->has('is_display_video') ? $request->input('is_display_video') : false;

        $this->model->estimated_duration = $request->has('estimated_duration') ? 
                                            $request->input('estimated_duration') : config('cms.course_default_estimated_duration');
        $this->model->estimated_duration_unit = $request->has('estimated_duration_unit') ? 
                                            Course::ESTIMATED_DURATION_UNIT[$request->input('estimated_duration_unit')] :
                                             config('cms.course_default_estimated_duration_unit');
        $this->model->grace_period_to_notify = $request->has('grace_period_to_notify') ? 
                                             $request->input('grace_period_to_notify') : config('cms.course_default_grace_period_to_notify');
        $this->model->lang = $request->has('lang') ? $request->input('lang') : 'both';
        if($this->model->is_display_video) {
            $modifedLink = str_replace("watch?v=","embed/",$request->input('video_link'));
            $this->model->video_link = $modifedLink;
        }
        if ($request->has('approval_status')) {
            $this->model->approval_status = $request->input('approval_status');
            $this->model->approved_by = null;
            if (
                $this->model->approval_status == Course::APPROVAL_STATUS_APPROVED ||
                $this->model->approval_status == Course::APPROVAL_STATUS_REJECTED
            ) {
                $this->model->approved_at = Carbon::now();
                $this->model->approved_by = auth()->user()->id;
            }
        }
        if ($request->has('collaborators')) {
            $this->model->collaborators = $request->input('collaborators');
        } // default is null

        if ($request->has('related_resources')) {
            $this->model->related_resources = $request->input('related_resources');
        } // default is null

        if ($file = $request->file('cover_image')) {
            $this->model->addMediaFromRequest('cover_image')->toMediaCollection('course_cover_image');
            $this->model->cover_image = '';
        }

        if ($file = $request->file('resource_file')) {
            $med = $this->model->addMediaFromRequest('resource_file')->toMediaCollection('course_resource_file');
            // $med->setCustomProperty('name', 'value'); 
            $this->model->save();
            $media = $this->model->getMedia('course_resource_file');
            
            // dd($result);
            foreach ($media->toArray() as $val) {
                $client = new \Google_Client();
                $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
                $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
                $client->refreshToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));
                $service = new \Google_Service_Drive($client);

                // $fileMetadata = new \Google_Service_Drive_DriveFile(array(
                //     'name' => 'ExpertPHP',
                //     'mimeType' => 'application/vnd.google-apps.folder'));
                // $folder = $service->files->create($fileMetadata, array(
                //     'fields' => 'id'));
                // printf("Folder ID: %s\n", $folder->id);

                // $fileName = $request->file('resource_file')->getClientOriginalName();
                $file = new \Google_Service_Drive_DriveFile(array(
                    'name' => $val['file_name'],
                    'parents' => array(env('GOOGLE_DRIVE_FOLDER_ID'))
                ));
                $result = $service->files->create($file, array(
                    'data' => file_get_contents(public_path("storage/" . $val['id'] . "/" . $val['file_name'])),
                    'mimeType' => 'application/octet-stream',
                    'uploadType' => 'media'
                ));

                // $this->model->res_link = 'https://drive.google.com/open?id=' . $result->id;
                $med->setCustomProperty('gdrive_link', 'https://drive.google.com/open?id=' . $result->id);
                // get url of uploaded file
                // $url = 'https://drive.google.com/open?id=' . $result->id;
                // echo $url;
            }
            $med->save();
        }

        if (!$request->has('order_type') && $id == null) { // on creating a course and needs to add the detault
            $this->model->order_type = "default";
            $final = [];
            array_push( $final, ['intro_'.$this->model->id  => 0 ] );
            $this->model->orders = $final; // $this->getAllSectionsForCourse();
        } // default is null
        

        $this->model->save();
        //dd($this->model->id);exit; // return id even for the new course
        
        if ($request->input('user_type')) {
            $old_privacies = CoursePrivacy::where('course_id', $id)->get();
            foreach ($old_privacies as $old_privacy) {
                $old_privacy->delete();
            }
            $userTypes = array(); // to add the default types
            if ( auth()->user()->type == User::TYPE_ADMIN || 
                            User::TYPE_MANAGER || User::TYPE_TEACHER_EDUCATOR ) {
                 $userTypes = $this->getDefaultRightsForCourseForm(auth()->user()->type);               
            }
            $final = array_merge($userTypes, $request->input('user_type'));
            //dd($final);exit;
            foreach ($final as $user_type) {
                $privacy = new CoursePrivacy();
                $privacy->course_id = $this->model->id;
                $privacy->user_type = $user_type;
                $privacy->save();
            }
        }
    }

    public function updateOrder($request, $id)
    {  
        $this->model = $this->find($id);
        $this->model->order_type = $request->input('order_type') ? $request->input('order_type')  : $this->model->order_type;
        $this->model->lecture_order_type = $request->input('lecture_order_type') ? $request->input('lecture_order_type')  : $this->model->lecture_order_type;
      //  $lectures = [];
        $this->model->lecture_orders = $request->input('lecture_orders') ? $request->input('lecture_orders') : $this->model->lecture_orders;
        if($this->model->order_type == 'default') { // edit and migrations for default type
            $this->model->orders = $this->getAllSectionsForCourse($id);
            $this->model->lecture_order_type = 'default';
            $this->model->lecture_orders = null;
        } else {
            $orders = $request->input('orders');
            $lectureOrders = $request->input('lecture_orders');
            $final = [];
            $lectures = [];
            if($this->model->lecture_order_type  == 'default') {
                foreach($orders as $ord) {      
                    if( strpos($ord, 'lect_') !== false ) { // add the 
                            $temp = explode('_', $ord);
                            $lectureId = $temp[1];
                            $lecture = Lecture::where('id', $lectureId)->first();
                            if($lecture) {
                                array_push( $final, ['lect_'.$lectureId  => 0 ] );  
                                if (count($lecture->learningActivities) || count($lecture->quizzes) || 
                                        count($lecture->liveSessions) || count($lecture->summaries)) {
                                            foreach($lecture->learningActivities as $la) {
                                                array_push( $final, ['lla_'.$la->id  => 0 ] );  
                                            } 
                                            foreach($lecture->quizzes as $quiz) {
                                                array_push( $final, ['lq_'.$quiz->id  => 0 ] );  
                                            }  
                                            foreach($lecture->liveSessions as $session) {
                                                array_push( $final, ['lsess_'.$session->id  => 0 ] );  
                                            }
                                            foreach($lecture->summaries as $summary) {
                                                array_push( $final, ['lsum_'.$summary->id  => 0 ] );  
                                            }
                                }
                                
                            }
                            
                    } else {
                        array_push($final, [ $ord => 0 ]);
                    }                            
                }
            } else { //flexible lecture order
              //  echo "here";exit;
                foreach($orders as $ord) {
                    if( strpos($ord, 'lect_') !== false ) { // add the 
                        $temp = explode('_', $ord);
                        $lectureId = $temp[1];
                        $lecture = Lecture::where('id', $lectureId)->first();
                        if($lecture) {
                            array_push( $final, ['lect_'.$lectureId  => 0 ] );  
                            foreach($lectureOrders as $idx => $data) {
                                foreach($data as $key => $val) {                                  
                                    if($key == $lectureId && !in_array($key, $lectures))  { 
                                        $lectures[] = $key;
                                    //  array_push($lectures, [$val]);
                                        foreach($val as $k => $v) {// print_r($v);exit;
                                            array_push( $final, [ $v => 0 ]);
                                        }
                                    }
                                }
                            }                                  
                        }
                                
                        } else {
                            array_push($final, [ $ord => 0 ]);
                        }                            
                    }
            }
            if ( $this->model->item_affect_certification == 1 && count($this->model->assessmentQuestionAnswers) )  {
                for ($n=0; $n < count($this->model->assessmentQuestionAnswers); $n++) {                 
                    array_push($final, ['assessment_'.$this->model->assessmentQuestionAnswers[$n]->id => 0 ]);                 
                }                
            }
            
            $this->model->orders = $final; //$request->input('orders');
        }
      //dd($this->model);exit;
        $this->model->save();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getKeyId()
    {
        return $this->model->id;
    }

    public function getDefaultRightsForCourseForm($user_type)
    {
        $rights = [];

        switch ($user_type) {
            case User::TYPE_ADMIN:
                $rights = [
                    User::TYPE_ADMIN
                ];
                break;

            case User::TYPE_MANAGER:
                $rights = [
                    User::TYPE_ADMIN, User::TYPE_MANAGER
                ];
                break;
            case User::TYPE_TEACHER_EDUCATOR:
                $rights = [
                    User::TYPE_ADMIN, User::TYPE_MANAGER, User::TYPE_TEACHER_EDUCATOR
                ];
                break;
        }

        return $rights;
    }

    public function checkForOtherUse($model)
    {
        if (count($model->quizzes) != 0) {
            return true;
        } else if (count($model->lecture) != 0) {
            return true;
        } else if (count($model->assignment) != 0) {
            return true;
        }
        return false;
    }

    public static function checkProgress(Course $course, $userLectures)
    {
        $totalCourseLecture = $course->lectures->count();
        $totalLearnLecture = $userLectures->where('course_id', $course->id)->count();
        return round($totalLearnLecture / $totalCourseLecture * 100);
    }

    // public static function isAlreadyTakenCourse(User $user, Course $course)
    // {
    //     return $user->learningCourses->contains('id', $course->id);
    // } //moved to course learner

    public static function goToLastLecture(User $user, Course $course)
    { // function used to go to the last lecture
        $userLectures = $user->learningLectures;
        return $userLectures
            ->where('course_id', $course->id)->last() ? route(
                'courses.learn-course',
                [$course, $userLectures->where('course_id', $course->id)->last()]
            )
            : route('courses.learn-course', [$course, $course->lectures()->first()]);
    }

    public function destroy($id) /* TODO to delete certificate , discussion etc */
    {
        $this->model = $this->find($id);

        $this->destroyLectures();

        $this->destroyQuizzes();

        //$this->destroyAssignments();
        $this->destroyAssessmentQuestionAnswers();

        $this->destroyEvaluationUsers();

        $this->destroyLiveSessions();

        $this->destroySummaries();

        $this->destroyLearningActivities();
        
        $this->destroyLearnCourse();

        $this->model->delete();
    }

    protected function destroyLectures()
    {
        $this->model->lectures->each->delete();
    }

    protected function destroyQuizzes()
    {
        if ($this->model->quizzes) {
            $quizzes = $this->model->quizzes;
            foreach ($quizzes as $quiz) {
                $quiz->questions->each->delete();
                $quiz->delete();
            }
        }
    }

    protected function destroyAssignments()
    {
        if ($this->model->assignment) {
            $assignments = $this->model->assignment;
            foreach ($assignments as $assignment) {
                $assignment->assignment_user->each->delete();
                $assignment->delete();
            }
        }
    }

    protected function destroyAssessmentQuestionAnswers()
    {
        if ($this->model->assessmentQuestionAnswers) {
            $assessments = $this->model->assessmentQuestionAnswers;
            foreach ($assessments as $assessment) {
                $assessment->assessment_user->each->delete();
                $assessment->delete();
            }
        }
    }

    protected function destroyEvaluationUsers()
    {
        if ($this->model->evaluationUsers) {
            $assessments = $this->model->evaluationUsers->each->delete();;
        }
    }

    protected function destroyLiveSessions()
    {   
        if ($this->model->liveSessions) {
            $sessions = $this->model->liveSessions;
            foreach ($sessions as $session) {
                $session->liveSessionUsers->each->delete();
                $session->delete();
            }
        }
    }

    protected function destroySummaries()
    {
        if ($this->model->summaries) {
            $summeries = $this->model->summaries->each->delete();;
        }
    }

    protected function destroyLearningActivities()
    {
        if ($this->model->learningActivities) {
            $las = $this->model->learningActivities->each->delete();;
        }
    }

    protected function destroyLearnCourse()
    {
        $this->model->courseLearners()->detach();
    }

    public function isAccessible(Course $course, $userType)
    {
        foreach ($course->privacies as $privacy) {
            if ($privacy->user_type == $userType) {
                return true;
            }
        }

        return false;
    }

    public function getPublishedCoursesForHome($user_type = User::TYPE_GUEST, $limit=1)
    {      
        $courses = $this->model
                            ->with(['media', 'privacies'])
                            ->isPublished()
                            ->isApproved()
                            ->latest()
                            ->limit($limit)
                            ->privacyFilter($user_type)
                            ->get();

        return $courses;
    }

    public function getPublishedCoursesForHomeByLanguage($user_type = User::TYPE_GUEST, $limit=1, $lang='en')
    {      
        $courses = $this->model
                            ->with(['media', 'privacies'])
                            ->isPublished()
                            ->isApproved()
                            ->latest()
                            ->limit($limit)
                            ->privacyFilter($user_type)
                            ->languageFilter($lang)
                            ->get();

        return $courses;
    }

    public function getAllPublishedCoursesByLanguage($user_type = User::TYPE_GUEST, $lang='en')
    {      
        $courses = $this->model
                            ->with(['media', 'privacies'])
                            ->isPublished()
                            ->latest()
                            ->privacyFilter($user_type)
                            ->languageFilter($lang)
                            ->get();

        return $courses;
    }

    public function getAllPublishedCourses($user_type = User::TYPE_GUEST, $lang='en')
    {      
        $courses = $this->model
                            ->with(['media', 'privacies'])
                            ->isPublished()
                            ->latest()
                            ->privacyFilter($user_type)
                            ->languageFilter($lang)
                            ->get();

        return $courses;
    }

    public function getAllSectionsForCourse($courseId) 
    {
        $final = [];
        $course = $this->model->findOrFail($courseId);
        $lectures = $course->lectures;
        $learningActivities = $course->learningActivities;
        $quizzes = $course->quizzes;
        //$assignments = $course->assignments;
        $sessions = $course->liveSessions;
        $summaries = $course->summaries;
        $assessments = $course->assessmentQuestionAnswers;
        $clearn = []; $clearntemp = [];
        $cquiz = []; $cquiztemp = [];
       // $cassign = []; $cassigntemp = [];
        $csess = []; $csesstemp = [];
        $csum = []; $csumtemp = [];
        $cassess = []; $cassesstemp = [];
        array_push( $final, ['intro_'.$course->id  => 0 ] );
        for ($i=0; $i < count($lectures); $i++) {

            array_push( $final, ['lect_'.$lectures[$i]->id  => 0 ] );

            if (count($learningActivities)) {
                for ($j=0; $j < count($learningActivities); $j++) {
                    if ( $learningActivities[$j]->lecture_id == $lectures[$i]->id ) {
                        array_push( $final, ['lla_'.$learningActivities[$j]->id  => 0 ] );
                    } 
                    if( $learningActivities[$j]->lecture_id == null ) {
                        if (!in_array( $learningActivities[$j]->id , $clearn)) {
                            array_push( $clearn, $learningActivities[$j]->id );
                            array_push( $clearntemp, ['learning_'.$learningActivities[$j]->id  => 0 ] );
                        }                                             
                    }
                } 
            }
            
            if (count($quizzes)) {
                for ($j=0; $j < count($quizzes); $j++) {
                    if ( $quizzes[$j]->lecture_id == $lectures[$i]->id ) {
                        array_push( $final, ['lq_'.$quizzes[$j]->id  => 0 ] );
                    } 
                    if( $quizzes[$j]->lecture_id == null ) {
                        if (!in_array( $quizzes[$j]->id , $cquiz)) {
                            array_push( $cquiz, $quizzes[$j]->id );
                            array_push( $cquiztemp, ['quiz_'.$quizzes[$j]->id  => 0 ] );
                        }                                             
                    }
                } 
            }
            //if (($course->item_affect_certification == 1 || $course->item_affect_certification == 3 )) {
                // for ($k=0; $k < count($assignments); $k++) {
                //     if ( $assignments[$k]->lecture_id == $lectures[$i]->id) {
                //         array_push($final, ['lassignment_'.$assignments[$k]->id => 0 ]);
                //     }
                //     if( $assignments[$k]->lecture_id == null ) {
                //         if (!in_array( $assignments[$k]->id , $cassign)) {
                //             array_push( $cassign, $assignments[$k]->id );
                //             array_push($cassigntemp, ['assignment_'.$assignments[$k]->id => 0 ]);
                //         }
                //     }
                // }
            //}
            if (count($sessions)) {
                for ($l=0; $l < count($sessions); $l++) {
                    if ( $sessions[$l]->lecture_id == $lectures[$i]->id) {
                        array_push($final, ['lsess_'.$sessions[$l]->id => 0 ]);
                    }
                    if( $sessions[$l]->lecture_id == null ) {
                        if (!in_array( $sessions[$l]->id , $csess)) {
                            array_push( $csess, $sessions[$l]->id );
                            array_push($csesstemp, ['session_'.$sessions[$l]->id => 0 ]);
                        }
                    }
                }
            } 
            if (count($summaries)) {
                for ($m=0; $m < count($summaries); $m++) {
                    if ( $summaries[$m]->lecture_id == $lectures[$i]->id) {
                        array_push($final, ['lsum_'.$summaries[$m]->id => 0 ]);
                    }
                    if( $summaries[$m]->lecture_id == null ) {
                        if (!in_array( $summaries[$m]->id , $csum)) {
                            array_push( $csum, $summaries[$m]->id );
                            array_push($csumtemp, ['summary_'.$summaries[$m]->id => 0 ]);
                        }
                    }
                }
            }                      
        }
        if ( $course->item_affect_certification == 1 && count($assessments) )  {
            for ($n=0; $n < count($assessments); $n++) {                 
               if (!in_array( $assessments[$n]->id , $cassess)) {
                    array_push( $cassess, $assessments[$n]->id );
                    array_push($cassesstemp, ['assessment_'.$assessments[$n]->id => 0 ]);
                }                 
            }
        } 
        $final = array_merge($final, $clearntemp, $cquiztemp, $csesstemp, $csumtemp, $cassesstemp); //$cassigntemp
        //dd($final);exit;
        return $final;

    }

    public function getLectureSectionsForFlexibleLectureOrder($courseId) 
    { 
        
        $final =  [];
       
        $course = $this->model->findOrFail($courseId);
        $lectures = $course->lectures;
        $learningActivities = $course->learningActivities;
        $quizzes = $course->quizzes;
        $sessions = $course->liveSessions;
        $summaries = $course->summaries;
        for ($i=0; $i < count($lectures); $i++) {
            
        }
        for ($i=0; $i < count($lectures); $i++) {
                       
            $lecturesArr = [];
            if (count($learningActivities)) {
                for ($j=0; $j < count($learningActivities); $j++) {
                    if ( $learningActivities[$j]->lecture_id == $lectures[$i]->id ) {
                        array_push( $lecturesArr, 'lla_'.$learningActivities[$j]->id  );
                    }                 
                } 
            }
                
            if (count($quizzes)) {
                for ($j=0; $j < count($quizzes); $j++) {
                    if ( $quizzes[$j]->lecture_id == $lectures[$i]->id ) {
                        array_push( $lecturesArr, 'lq_'.$quizzes[$j]->id );
                    }                    
                } 
            }
                
            if (count($sessions)) {
                for ($l=0; $l < count($sessions); $l++) {
                    if ( $sessions[$l]->lecture_id == $lectures[$i]->id) {
                        array_push($lecturesArr, 'lsess_'.$sessions[$l]->id );
                    }               
                }
            } 
            if (count($summaries)) {
                for ($m=0; $m < count($summaries); $m++) {
                    if ( $summaries[$m]->lecture_id == $lectures[$i]->id) {
                        array_push($lecturesArr, 'lsum_'.$summaries[$m]->id );
                    }                  
                }
            }
            $lectureId = $lectures[$i]->id;
            
            array_push($final,  [$lectureId => $lecturesArr]);
        }     
    //    dd($final);exit;
        return $final;
    }

    public function getMainSectionsForFlexibleOrder($courseId) 
    {   //return all main sections & the ones attached to lectures will be looped through under Lectures
        $final = [];
        $course = $this->model->findOrFail($courseId);
        $lectures = $course->lectures;
        $learningActivities = $course->learningActivities;
        $quizzes = $course->quizzes;
        //$assignments = $course->assignments;
        $sessions = $course->liveSessions;
        $summaries = $course->summaries;
        $assessments = $course->assessmentQuestionAnswers;
        $clearn = []; $clearntemp = [];
        $cquiz = []; $cquiztemp = [];
       // $cassign = []; $cassigntemp = [];
        $csess = []; $csesstemp = [];
        $csum = []; $csumtemp = [];
        $cassess = []; $cassesstemp = [];
        array_push( $final, ['intro_'.$course->id  => 0 ] );
        for ($i=0; $i < count($lectures); $i++) {
            array_push( $final, ['lect_'.$lectures[$i]->id  => 0 ] );                     
        }
        if (count($learningActivities)) {
            for ($j=0; $j < count($learningActivities); $j++) {      
                if( $learningActivities[$j]->lecture_id == null ) {
                    if (!in_array( $learningActivities[$j]->id , $clearn)) {
                        array_push( $clearn, $learningActivities[$j]->id );
                        array_push( $clearntemp, ['learning_'.$learningActivities[$j]->id  => 0 ] );
                    }                                             
                }
            } 
        }
        
        if (count($quizzes)) {
            for ($j=0; $j < count($quizzes); $j++) {         
                if( $quizzes[$j]->lecture_id == null ) {
                    if (!in_array( $quizzes[$j]->id , $cquiz)) {
                        array_push( $cquiz, $quizzes[$j]->id );
                        array_push( $cquiztemp, ['quiz_'.$quizzes[$j]->id  => 0 ] );
                    }                                             
                }
            } 
        }

        if (count($sessions)) {
            for ($l=0; $l < count($sessions); $l++) {           
                if( $sessions[$l]->lecture_id == null ) {
                    if (!in_array( $sessions[$l]->id , $csess)) {
                        array_push( $csess, $sessions[$l]->id );
                        array_push($csesstemp, ['session_'.$sessions[$l]->id => 0 ]);
                    }
                }
            }
        } 
        if (count($summaries)) {
            for ($m=0; $m < count($summaries); $m++) {            
                if( $summaries[$m]->lecture_id == null ) {
                    if (!in_array( $summaries[$m]->id , $csum)) {
                        array_push( $csum, $summaries[$m]->id );
                        array_push($csumtemp, ['summary_'.$summaries[$m]->id => 0 ]);
                    }
                }
            }
        } 
        // if ( $course->item_affect_certification == 1 && count($assessments) )  {
        //     for ($n=0; $n < count($assessments); $n++) {                 
        //        if (!in_array( $assessments[$n]->id , $cassess)) {
        //             array_push( $cassess, $assessments[$n]->id );
        //             array_push($cassesstemp, ['assessment_'.$assessments[$n]->id => 0 ]);
        //         }                 
        //     }
        // } // need to add all assessments 
        $final = array_merge($final, $clearntemp, $cquiztemp, $csesstemp, $csumtemp, $cassesstemp); //$cassigntemp
        //dd($final);exit;
        return $final;

    }

    public static function getPrevSection($findVal, $completed)
    {
        foreach($completed as $key=> $data) {
            if (array_key_exists($findVal,$data)) {
                return isset($completed[$key-1]) ? array_keys($completed[$key-1])[0] : null;
            } 
        }
        return null;
    }

    public static function getNextSection($findVal, $completed)
    {
        foreach($completed as $key=> $data) {
            if (array_key_exists($findVal,$data)) {
                return isset($completed[$key+1]) ? array_keys($completed[$key+1])[0] : null;
            } 
        }
        return null;
    }

    public static function getRouteFromValue($findVal)
    {
        if ( strpos($findVal, 'intro')  !== false ) {
            $courseTemp = explode("_", $findVal);
            $courseId = $courseTemp[1];
            $course = Course::findOrFail($courseId);
            return route('courses.view-course', [$course]);
        } elseif ( strpos($findVal, 'lect')  !== false ) {
            $lectTemp = explode("_", $findVal); 
            $lectId = $lectTemp[1];
            $lecture = Lecture::findOrFail($lectId); 
            return route('courses.learn-course', [$lecture]);
        } elseif ( strpos($findVal, 'quiz')  !== false  || strpos($findVal, 'lq')  !== false) {
            $qTemp = explode("_", $findVal);
            $qId = $qTemp[1];
            // $quiz = Course::findOrFail($qId);
            return route('quiz.show', $qId );
        } elseif ( strpos($findVal, 'session')  !== false || strpos($findVal, 'lsess')  !== false ) {
            $sTemp = explode("_", $findVal);
            $sId = $sTemp[1];
            $session = LiveSession::findOrFail($sId);
            return route('courses.view-live-session', [$session]);
        } elseif ( strpos($findVal, 'summary')  !== false  || strpos($findVal, 'lsum')  !== false) {
            $sumTemp = explode("_", $findVal);
            $sumId = $sumTemp[1];
            $summary = Summary::findOrFail($sumId);
            return route('courses.summary', [$summary]);
        } elseif ( strpos($findVal, 'learning')  !== false  || strpos($findVal, 'lla')  !== false) {
            $learnTemp = explode("_", $findVal);
            $learnId = $learnTemp[1];
            $learning = LearningActivity::findOrFail($learnId);
            return route('courses.learning-activity', [$learning]);
        } elseif ( strpos($findVal, 'assessment')  !== false ) {
            $aTemp = explode("_", $findVal);
            $aId = $aTemp[1];
            $assessment = AssessmentQuestionAnswer::findOrFail($aId);
            return route('courses.assessment', [$assessment]);
        } 
        return null;
    }

    public static function getTitleFromValue($findVal, $course)
    {
        if ( strpos($findVal, 'intro')  !== false ) {
            return $course->title;
        } elseif ( strpos($findVal, 'lect')  !== false ) {
            $lectTemp = explode("_", $findVal); 
            $lectId = $lectTemp[1];
            $lecture = Lecture::findOrFail($lectId); 
            return $lecture->lecture_title;
        } elseif ( strpos($findVal, 'quiz')  !== false  || strpos($findVal, 'lq')  !== false) {
            $qTemp = explode("_", $findVal);
            $qId = $qTemp[1];
            $quiz = Quiz::findOrFail($qId);
            return $quiz->title;
        } elseif ( strpos($findVal, 'session')  !== false || strpos($findVal, 'lsess')  !== false ) {
            $sTemp = explode("_", $findVal);
            $sId = $sTemp[1];
            $session = LiveSession::findOrFail($sId);
            return $session->topic;
        } elseif ( strpos($findVal, 'summary')  !== false  || strpos($findVal, 'lsum')  !== false) {
            $sumTemp = explode("_", $findVal);
            $sumId = $sumTemp[1];
            $summary = Summary::findOrFail($sumId);
            return $summary->title;
        } elseif ( strpos($findVal, 'learning')  !== false  || strpos($findVal, 'lla')  !== false) {
            $learnTemp = explode("_", $findVal);
            $learnId = $learnTemp[1];
            $learning = LearningActivity::findOrFail($learnId);
            return $learning->title;
        } elseif ( strpos($findVal, 'assessment')  !== false ) {
            $aTemp = explode("_", $findVal);
            $aId = $aTemp[1];
            $assessment = AssessmentQuestionAnswer::findOrFail($aId);
            return $assessment->question;
        } 
        return null;
    }

    public static function getIdFromValue($findVal, $course)
    {
        if ( strpos($findVal, 'intro')  !== false ) {
            return $course->id;
        } elseif ( strpos($findVal, 'lect')  !== false ) {
            $lectTemp = explode("_", $findVal); 
            $lectId = $lectTemp[1];
            return $lectId;
        } elseif ( strpos($findVal, 'quiz')  !== false  || strpos($findVal, 'lq')  !== false) {
            $qTemp = explode("_", $findVal);
            $qId = $qTemp[1];
            return $qId;
        } elseif ( strpos($findVal, 'session')  !== false || strpos($findVal, 'lsess')  !== false ) {
            $sTemp = explode("_", $findVal);
            $sId = $sTemp[1];
            return $sId;
        } elseif ( strpos($findVal, 'summary')  !== false  || strpos($findVal, 'lsum')  !== false) {
            $sumTemp = explode("_", $findVal);
            $sumId = $sumTemp[1];
            return $sumId;
        } elseif ( strpos($findVal, 'learning')  !== false  || strpos($findVal, 'lla')  !== false) {
            $learnTemp = explode("_", $findVal);
            $learnId = $learnTemp[1];
            return $learnId;
        } elseif ( strpos($findVal, 'assessment')  !== false ) {
            $aTemp = explode("_", $findVal);
            $aId = $aTemp[1];
            return $aId;
        } 
        return null;
    }
    
    public function getTypeFromValue($findVal, $course)
    {
        if ( strpos($findVal, 'intro')  !== false ) {
            return 'course';
        } elseif ( strpos($findVal, 'lect')  !== false ) {      
            return 'lecture';
        } elseif ( strpos($findVal, 'quiz')  !== false  || strpos($findVal, 'lq')  !== false) {
            return 'quiz';
        } elseif ( strpos($findVal, 'session')  !== false || strpos($findVal, 'lsess')  !== false ) {
            return 'live_session';
        } elseif ( strpos($findVal, 'summary')  !== false  || strpos($findVal, 'lsum')  !== false) {
            return 'summary';
        } elseif ( strpos($findVal, 'learning')  !== false  || strpos($findVal, 'lla')  !== false) {
            return 'learning_activity';
        } elseif ( strpos($findVal, 'assessment')  !== false ) {
            return 'assessment';
        } 
        return null;
    }
    public function getIsCompletedFromValue($current, $completed)
    {
        $isCompleted = 0;
        foreach($completed as $key => $data) {
            if (array_key_exists($current,$data)) {  
                foreach($data as $idx => $val) {
                    $isCompleted = $val; break;
                }
            } 
        }      
        return $isCompleted; 
    }

    public static function getEvaluationRoute($course)
    {
        return route('courses.evaluation', [$course]);
    }

    public static function getCourseTitleById($courseId)
    {
        return Course::where('id',$courseId)->pluck('title');
    }

    
    public static function getCourseSlugById($courseId)
    {
        return Course::where('id',$courseId)->pluck('slug');
    }

    public static function isCoursePublished($courseId)
    {
        $cc = Course::where('id',$courseId)->where('is_published',1)->first();
        return $cc ? true : false;
    }
    public static function validateCourseBeforeRequest($course)
    { 
        $lecture = $course->lectures;
        return count($lecture) ? null : trans('A course should have at least a lecture attached!');
    }

    public static function shouldCrudButtonsDisabled($course)
    {
        return isset($course->learners) && count($course->learners)  ? true : false; //&& $course->is_published
    }

    public static function getAssignmentLink($courseId, $userId)
    {
        $course = Course::findOrFail($courseId);
        $userAssignmentId = null;
        $userAssignmentLink = [];
        if ($course->quizzes && count($course->quizzes)) { 
            foreach($course->quizzes as $cq) {
                if($cq->type == 'assignment') { 
                    $quiz = Quiz::findOrFail($cq->id);  
                    if($quiz->questions && count($quiz->questions)) {
                        foreach($quiz->questions as $qq) {
                            $question = Question::findOrFail($qq->id); 
                            if($question->assignment && $question->assignment->id) {
                                array_push($userAssignmentLink, route('member.assignment.detail', [$question->assignment->id])); 
                            }
                        }
                    }
                }  
            }
        }
        return $userAssignmentLink;
        
    }

    public static function isCollaborator($corseId, $userId)
    {

    }

    public function convertCompletedArrayToAPISupportedFormat($completed, $course)
    {
        $final = [];
        foreach($completed as $idx => $data) {
            $keys = array_keys($data);
            $values = array_values($data);
           
            if( strpos($keys[0], 'lect')  !== false ) {
                $isAllCompleted = $values[0] === 1 ? true : false;
                $lectureId = explode('_', $keys[0])[1];
                $lecture = Lecture::where('id', $lectureId)->first();
                if ( count($lecture->learningActivities) ||
                     count($lecture->quizzes) ||
                     count($lecture->liveSessions) ||
                     count($lecture->summaries)) {
                        foreach($lecture->learningActivities as $la) {
                            $isAllCompleted = $isAllCompleted && CourseLearnerRepository::isThisPartCompleted('lla_' . $la->id, $completed);
                        }
                        foreach($lecture->quizzes as $quiz) {
                            $isAllCompleted = $isAllCompleted && CourseLearnerRepository::isThisPartCompleted('lq_' . $quiz->id, $completed); 
                        }
                        foreach($lecture->liveSessions as $session) {
                            $isAllCompleted = $isAllCompleted && CourseLearnerRepository::isThisPartCompleted('lsess_' . $session->id, $completed); 
                        }
                        foreach($lecture->summaries as $summary) {
                            $isAllCompleted = $isAllCompleted && CourseLearnerRepository::isThisPartCompleted('lsum_' . $summary->id, $completed); 
                        }
                }
                
                array_push($final, $this->getObjFromValue($keys[0], $isAllCompleted == true ? 1 : 0 , $course));
                array_push($final, $this->getOverviewForLecture($keys[0], $values[0], $course));
            } else {
                array_push($final, $this->getObjFromValue($keys[0], $values[0], $course));
            }
        } 
        return $final;
    }

    public function getOverviewForLecture($findVal, $isCompleted,$course)
    {
        $lectTemp = explode("_", $findVal); 
        $lectId = $lectTemp[1];
        $object = new stdClass();
        $object->key = "lect_".$lectId."_overview";
        $object->isCompleted = $isCompleted;
        $object->id = $lectId;
        $object->type = "overview";
        $object->title = "Overview";
        return $object;
    }

    public function getObjFromValue($findVal, $isCompleted,$course)
    {
        $object = new stdClass();
        $object->key = $findVal;
        $object->isCompleted = $isCompleted;
        if ( strpos($findVal, 'intro')  !== false ) {
            $courseTemp = explode("_", $findVal);
            $courseId = $courseTemp[1];          
            $object->id = $courseId;
            $object->type = "course";
            $object->title = $course->title;
            return $object;
        } elseif ( strpos($findVal, 'lect')  !== false ) {
            $lectTemp = explode("_", $findVal); 
            $lectId = $lectTemp[1];
            $object->id = $lectId;
            $object->type = "lecture";
            $object->title = CourseRepository::getTitleFromValue($findVal, $course);
            return $object;
        } elseif ( strpos($findVal, 'quiz')  !== false  || strpos($findVal, 'lq')  !== false) {
            $qTemp = explode("_", $findVal);
            $qId = $qTemp[1];
            $object->id = $qId;       
            $object->type = "quiz";
            $object->title = CourseRepository::getTitleFromValue($findVal, $course);
            return $object;
        } elseif ( strpos($findVal, 'session')  !== false || strpos($findVal, 'lsess')  !== false ) {
            $sTemp = explode("_", $findVal);
            $sId = $sTemp[1];
            $object->id = $sId;
            $object->type = "session";
            $object->title = CourseRepository::getTitleFromValue($findVal, $course);
            return $object;
        } elseif ( strpos($findVal, 'summary')  !== false  || strpos($findVal, 'lsum')  !== false) {
            $sumTemp = explode("_", $findVal);
            $sumId = $sumTemp[1];
            $object->id = $sumId;          
            $object->type = "summary";
            $object->title = CourseRepository::getTitleFromValue($findVal, $course);
            return $object;
        } elseif ( strpos($findVal, 'learning')  !== false  || strpos($findVal, 'lla')  !== false) {
            $learnTemp = explode("_", $findVal);
            $learnId = $learnTemp[1];
            $object->id = $learnId;          
            $object->type = "learning";
            $object->title = CourseRepository::getTitleFromValue($findVal, $course);
            return $object;
        } elseif ( strpos($findVal, 'assessment')  !== false ) {
            $aTemp = explode("_", $findVal);
            $aId = $aTemp[1];
            $object->id = $aId;          
            $object->type = "assessment";
            $object->title = CourseRepository::getTitleFromValue($findVal, $course);
            return $object; 
        } 
        return null;
    }

    public function modifyCompletedToSupportOverview($completed)
    {
        $final = [];
        $withOverview = [];
        foreach($completed as $idx => $data) {
            $keys = array_keys($data);
            $values = array_values($data);
            if( strpos($keys[0], 'lect')  !== false ) {
                $overview = $keys[0]."_overview"; //lect val used for web will be value for overview
                $isAllCompleted = $values[0] === 1 ? 1 : 0;
                $lectureId = explode('_', $keys[0])[1];
                $lecture = Lecture::where('id', $lectureId)->first();
                if ( count($lecture->learningActivities) ||
                     count($lecture->quizzes) ||
                     count($lecture->liveSessions) ||
                     count($lecture->summaries)) {
                        foreach($lecture->learningActivities as $la) {
                            $isAllCompleted = $isAllCompleted && CourseLearnerRepository::isThisPartCompleted('lla_' . $la->id, $completed);
                        }
                        foreach($lecture->quizzes as $quiz) {
                            $isAllCompleted = $isAllCompleted && CourseLearnerRepository::isThisPartCompleted('lq_' . $quiz->id, $completed); 
                        }
                        foreach($lecture->liveSessions as $session) {
                            $isAllCompleted = $isAllCompleted && CourseLearnerRepository::isThisPartCompleted('lsess_' . $session->id, $completed); 
                        }
                        foreach($lecture->summaries as $summary) {
                            $isAllCompleted = $isAllCompleted && CourseLearnerRepository::isThisPartCompleted('lsum_' . $summary->id, $completed); 
                        }
                }
                $lectureData =[ $keys[0] => $isAllCompleted ? 1 : 0, $overview => $values[0] ];
                array_push($final, $lectureData);
               // array_push($final, $overview); 
            } else {
                array_push($final, $data);
            }
            
        } 
        return $final;
    }

}
