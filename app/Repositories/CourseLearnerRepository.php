<?php

namespace App\Repositories;

use App\User;
use App\Models\Course; 
use App\Models\CourseLearner;
use App\Models\CourseCancelLearner;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;

class CourseLearnerRepository
{
    protected $model;

    public function __construct(CourseLearner $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = $this->model
                        ->withSearch($request->input('search'))
                        ->sortable(['updated_at' => 'desc'])
                        ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return $posts;
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getKeyId()
    {
        return $this->model->id;
    }
    public function indexForLearner(Request $request)
    {
        $posts = $this->model
            // ->with(['media'])
            ->ofUser(auth()->user()->id)
            // ->withSearch($request->input('search'))
            // ->withCategory($request->input('course_categories'))
            // ->withLevel($request->input('level_id'))
            // ->withApprovalStatus($request->input('approval_status'))
            // ->sortable(['created_at' => 'desc'])
            ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return $posts;
    }

    public function indexForLearnerMobile(Request $request)
    {
        $posts = $this->model       
            ->ofUser(auth()->user()->id)
            ->get();

        return $posts;
    }

    public function saveRecord($data, $id = null,$return = false)
    {
        if (isset($id)) {
            $this->model = $this->find($id);
        }

        $this->model->fill($data);
        $this->model->save();
        if($return) {
            return $this->model;
        }
    }

    public function updateGenerateCertificate($courseId, $userId) 
    {
        $courseLearner = $this->model->where('course_id', $courseId)
                                        ->where('user_id', $userId)
                                        ->where('active', 1)->first();
        $courseLearner->certificate_count = $courseLearner->certificate_count > 0 ? (int)$courseLearner->certificate_count + 1 : 1;
        $courseLearner->save();
    }

    public function updatelastVisited($courseId, $userId, $route) 
    {
        $courseLearner = $this->model->where('course_id', $courseId)
                                        ->where('user_id', $userId)
                                        ->where('active', 1)->first();
        $courseLearner->last_visited = $route;
        $courseLearner->save();
    }

    public static function getTotalCertificatesForCourse($courseId) 
    {
        $certificates = CourseLearner::where('course_id', $courseId)
                                    ->where('certificate_count', '>', 0)->pluck('user_id');
        return count($certificates);
    }

    public static function getTotalCertificates() 
    {
        $certificates = CourseLearner::where('certificate_count', '>', 0)->pluck('user_id');
        return count($certificates);
    }

    public function cancelCourse($courseId, $userId) 
    {
        $courseLearner = $this->model->where('course_id', $courseId)
                                        ->where('user_id', $userId)
                                        ->where('active', 1)->first();
        $course = Course::findOrFail($courseId);
        try {
            $user = User::findOrFail($userId);
            //delete all live session users
           // dd($user->long_answer_user);exit;
            if ( isset($user->livesession_user) && count($user->livesession_user) ) {
                $sessionIds = array();
                foreach($course->liveSessions as $ls) {
                    array_push($sessionIds, $ls->id);
                }
                foreach($user->livesession_user as $lu) {
                    if ( in_array($lu->session_id, $sessionIds)) { //echo "found course id in assessments ";exit;
                        $lu->delete();
                    }
                }
            }
            
            // if ( isset($user->livesession_user) && count($user->livesession_user) ) {
            //     
            // }
            if ( isset($user->assessment_user) && count($user->assessment_user) ) {
                foreach($user->assessment_user as $au) {
                    if ( $au->course_id == $courseId ) { //echo "found course id in assessments ";exit;
                        $au->delete();
                    }
                }
            }
            //delete all assignment if there's any
           
            if ( isset($user->assignment_user) && count($user->assignment_user) ) { 
                foreach($user->assignment_user as $au) {               
                    $row = Media::where('model_id',$au->id)->first(); // delete the media file first
                    if($row) $row->delete();
                    $au->delete();                  
                } //  delete the file from media as well ! assignment_user_id = model_id in media & user_assignment_attached_file (collection_name)
            }

            if ( isset($user->long_answer_user) && count($user->long_answer_user) ) { 
                foreach($user->long_answer_user as $lau) {               
                    if ( $lau->user_id == $user->id) { //echo "found course id in evaluations"; exit;
                        $lau->delete();
                    }                
                } 
            }
            //delete all evaluations if there's any
            if ( isset($user->evaluation_user) && count($user->evaluation_user) ) {
                foreach($user->evaluation_user as $eu) {
                    if ( $eu->course_id == $courseId ) { //echo "found course id in evaluations"; exit;
                        $eu->delete();
                    }
                }
            }
            $cancelUser = CourseCancelLearner::query()->create([
                'course_id' => $courseId,
                'user_id' => auth()->user()->id
            ]);
            $courseLearner->delete(); //delete course learner data
        } catch(ModelNotFoundException $e){
            return redirect()->back()->with('message', 'Error occured while deleting');
        }
    }

    public static function getlastVisited($courseId, $userId) 
    {
        $courseLearner = CourseLearner::where('course_id', $courseId)
                                        ->where('user_id', $userId)
                                        ->where('active', 1)->first(); //dd($courseLearner->last_visited);exit;
        return $courseLearner->last_visited;
    }

    public static function goToLastSection($userId, $course)
    {
    
        $lastVisited = self::getlastVisited($course->id, $userId);
        if ( $lastVisited ) { 
            return $lastVisited;
        } else {
            $route = route('courses.view-course', [$course]);
            $this->updatelastVisited($course->id, $userId, $route);
            return $route;
        }
    }

    public function getCourseLearnerData($courseId, $userId)
    {
        return $this->model->where('course_id', $courseId)
                                    ->where('user_id', $userId)
                                   ->where('active', 1)
                                    ->orderBy('id')->first();
    }

    public static function isReadyToGenerateCerti($course)
    {
        if ($course->course_type_id == 1) { //certified
            $courseLearnerData = CourseLearner::where('course_id', $course->id)
                                    ->where('user_id', auth()->user()->id)
                                    ->where('active', 1)->first();
            $evaluationUser = $course->evaluationUsers->where('user_id',auth()->user()->id)->first(); 
            // if(auth()->user()->id == 15029) {
            //     dd($evaluationUser); exit;
            // }
            if ( $course->item_affect_certification == 0 && $evaluationUser && $evaluationUser->status == 2) { // 0 means completion only
                return $courseLearnerData->percentage == 100 ? true : false;
            } 
            if ( $course->item_affect_certification == 1 )  { //assessment
                $courseAssessment = $course->assessmentUsers->where('user_id',auth()->user()->id)->first(); // status & overall_score will be same for all records
                if ( $courseAssessment ) {
                    return ($evaluationUser && ($evaluationUser->status == 2) && ($courseAssessment->status == 2 ) && 
                        ( $courseAssessment->overall_score >= $course->acceptable_score_for_assessment) ) ? true : false;
                } else {
                    return false; //no submission for assessment yet
                }         
            } 
            
        } else { //not certified
            return false;
        }
    }

    public static function isReadyToEvaluate($course)
    {
        if ($course->course_type_id == 1) { //certified
            $courseLearnerData = CourseLearner::where('course_id', $course->id)
                                    ->where('user_id', auth()->user()->id)
                                    ->where('active', 1)->first();
            if ( $course->item_affect_certification == 0) { // 0 means completion only
                return $courseLearnerData->percentage == 100 ? true : false;
            } 
            if ( $course->course_type_id == 1 && $course->item_affect_certification == 1 )  { //assessment
                $courseAssessment = $course->assessmentUsers->where('user_id',auth()->user()->id)->first(); // status & overall_score will be same for all records
                if ( $courseAssessment ) {
                    return ( ($courseAssessment->status == 2 ) && ((int)$courseLearnerData->percentage == 100) &&
                        ( $courseAssessment->overall_score >= $course->acceptable_score_for_assessment) ) ? true : false;
                } else { 
                    return false; //no submission for assessment yet
                }         
            } 
            
        } else { //not certified
            return true;
        }
    }

    public static function isPartTheLastONe($completed, $findVal)
    {        
        $last = count($completed) -1;
        $key = array_keys($completed[$last]);      
        return $key[0] ==  $findVal ? true : false; 
    }

    public static function isReadyToAssess($completed)
    {        
        foreach($completed as $idx => $data) {
            $keys = array_keys($data);
            if(strpos($keys[0], 'assessment_') === false) { // if not assessment
                $values = array_values($data);
                if($values[0] == 0) return false;
            }
        }      
        return true;           
    }

    public function isReadyToAssessFromLastSection($completed)
    {        
        foreach($completed as $idx => $data) {
            $keys = array_keys($data);
            if(strpos($keys[0], 'assessment_') === false) { // if not assessment
                $values = array_values($data);
                if($values[0] == 0) return false;
            }
        }      
        return true;           
    }

    public static function isNextSectionAssessment($findVal, $completed)
    {   
        $findValIndex = 0;     
        foreach($completed as $idx => $data) {
            $keys = array_keys($data);
            if(strpos($keys[0], $findVal) !== false) { // find the position of the current section
                $findValIndex = $idx; break;
            }
        } 
        $expectedAssessIndex = $findValIndex + 1;
        if(isset($completed[$expectedAssessIndex])) {
            $keys = array_keys($completed[$expectedAssessIndex]);
            return (strpos($keys[0], 'assessment_') !== false) ? true : false;
            
        } else {
            return false;
        }              
    }

    public static function isEvaluationDone($course)
    {
        $evaluationUsers = $course->evaluationUsers;
        foreach($evaluationUsers as $usr) {
            if($usr->user_id == auth()->user()->id && $usr->course_id == $course->id  && $usr->status == 2 ) return true;
        }
        return  false;
    }

    public static function isAlreadyTakenCourse($user, $course)
    {
        $userlearningCourses = CourseLearner::where('course_id', $course->id)
                                ->where('user_id', auth()->user()->id)
                                ->where('active', 1)->first();
        return $userlearningCourses ? true : false;
    }

    public function isUserAlreadyTakenCourse($user, $courseId)
    {
        $userlearningCourses = CourseLearner::where('course_id', $courseId)
                                ->where('user_id', auth()->user()->id)
                                ->where('active', 1)->first();
        return $userlearningCourses ? true : false;
    }

    public function performCompletionLogic($courseId, $userId, $findValue, $hasEffectOnPercentage)
    {
        $courseLearner = $this->getCourseLearnerData($courseId, $userId);
        $completed = [];
        foreach($courseLearner->completed as $key=> $data) {
            if (array_key_exists($findValue,$data)) { 
                $data[$findValue] = 1; 
                array_push($completed, $data);
            } else { 
                array_push($completed, $data);
            }
        }
        if( $hasEffectOnPercentage ) {
            $percentage = $this->calculatePercentage($completed);
            $courseLearner->percentage = $percentage;
        } else {
            $courseLearner->percentage = $courseLearner->percentage;
        }

        switch($courseLearner->percentage) {
            case 100: $courseLearner->status = 'completed';break;
            case 0: $courseLearner->status =  'not_started';break;
            default: $courseLearner->status =  'learning';break;
        }
        $courseLearner->completed = $completed;
        $courseLearner->save();
        return $completed;
    }

    public function getSectionToRedirect($completed, $current, $redirect)
    {
        $currentPos = -1;
        foreach($completed as $key => $data) {
            if (array_key_exists($current,$data)) {
                $currentPos = $key;
                break;
            } 
        }
        if($currentPos > -1) {
            if($redirect == 'previous') {
                return isset($completed[$currentPos-1]) ? array_keys($completed[$currentPos-1])[0] : -1;
            } else {
                return isset($completed[$currentPos+1]) ? array_keys($completed[$currentPos+1])[0] : -1;
            }
        }
        return $currentPos;      
    }

    public function setPercentageAfterSubmittingAssessment($courseId, $userId,$percentage)
    {
        $courseLearner = $this->getCourseLearnerData($courseId, $userId);
        $courseLearner->percentage = $percentage;
        $courseLearner->status = $percentage == 100 ? 'completed' : 'learning';
        $courseLearner->save();
        return true;
    }

    protected function calculatePercentage($completedArr)
    {
        $total = 0; $completedCount = 0;
        for($i =0; $i < count($completedArr); $i++) {
            $keys = array_keys($completedArr[$i]);
            //if(strpos($keys[0], 'session_') === false) { //exclude session which is optional //included to avoid confusion
                $total++;
                $temp = array_values($completedArr[$i]);
                if($temp[0] == 1) {
                    $completedCount++;
                }          
            //}       
        }
        return ( $completedCount * 100) / $total;
    }

    public static function isAllPartsCompleted($findVal, $completedArr)
    {
        foreach($completedArr as $key=> $data) {
            $keys = array_keys($data); 
            if ( strpos($keys[0], $findVal)  !== false ) {
                $vals = array_values($data); 
                if($vals[0] == 0) return false;
            } 
        }
        return true;
    }

    public static function isAllPartsForLectureCompleted($findVal, $completedArr)
    {
        foreach($completedArr as $key=> $data) {
            $keys = array_keys($data); 
            if ( strpos($keys[0], $findVal)  !== false ) {
                $vals = array_values($data); 
                if($vals[0] == 0) {
                    return false;
                } else {

                }
            } 
        }
        return true;
    }
    
    public static function isThisPartCompleted($findVal, $completedArr)
    {
        foreach($completedArr as $key=> $data) {
            if (array_key_exists($findVal,$data)) {
                if($data[$findVal] == 1) return true;
            } 
        }
        return false;
    }

    public static function isThisPartInCompletedArr($findVal, $completedArr)
    {
        foreach($completedArr as $key=> $data) {
            if (array_key_exists($findVal,$data)) { 
                return true;
            } 
        }
        return false;
    }

    public function addSessionToCourseLearnerCompleted($lectureId,$addVal,$courseId)
    {
        $courseLearners = $this->model->where('course_id', $courseId)
                                      ->where('active', 1)->get();
        if(!$lectureId) {
            for ( $i = 0; $i < sizeof($courseLearners); $i++ ) {
                $temp = $courseLearners[$i]->completed;
                array_push( $temp, [ $addVal => 0 ]);
                $courseLearners[$i]->completed = $temp;
                $courseLearners[$i]->save();
            }
        } else {
            //$this->addNewSessionToCompleted($courseLearners, $lectureId, $addVal);
        }
    }

    protected function addNewSessionToCompleted($courseLearners, $lectureId, $addVal) 
    {
        $final = [];
        $cquiz = []; $cquiztemp = [];
        $cassign = []; $cassigntemp = [];
        $csess = []; $csesstemp = [];
        //TODO need to get lectures!!!
        for ($i=0; $i < count($courseLearners); $i++) {
            //TODO: To have another loop for lectures and check the saved values and value to be added
            array_push( $final, ['lect_'.$lectures[$i]->id  => 0 ] );
            if (count($quizzes)) {
                for ($j=0; $j < count($quizzes); $j++) {
                    if ( $quizzes[$j]->lecture_id == $lectures[$i]->id ) {
                        array_push( $final, ['lquiz_'.$quizzes[$j]->id  => 0 ] );
                    }               
                } 
            }
            if (count($assignments)) {
                for ($k=0; $k < count($assignments); $k++) {
                    if ( $assignments[$k]->lecture_id == $lectures[$i]->id) {
                        array_push($final, ['lassignment_'.$assignments[$k]->id => 0 ]);
                    }             
                }
            }
            if (count($sessions)) {
                for ($l=0; $l < count($sessions); $l++) {
                    if ( $sessions[$l]->lecture_id == $lectures[$i]->id) {
                        array_push($final, ['lsession_'.$sessions[$l]->id => 0 ]);
                    }                
                }
            }           
        }
        $final = array_merge($final,$cquiztemp,$cassigntemp,$csesstemp);
        return $final;

    }

    public function getFirstAssessment($completedArr)
    {
        foreach($completedArr as $key=> $data) {
            $keys = array_keys($data); 
            if ( strpos($keys[0], 'assessment_')  !== false ) {
                return $keys[0];
            } 
        }
        return null;
    }

    public function getTotalAssessmentsFromUserArr($completedArr)
    {
        $count = 0;
        foreach($completedArr as $key=> $data) {
            $keys = array_keys($data); 
            if ( strpos($keys[0], 'assessment_')  !== false ) {
                $count++;
            } 
        }
        return $count;
    }

    public function getAssessmentIdsFromUserArr($completedArr)
    {
        $final = [];
        foreach($completedArr as $key=> $data) {
            $keys = array_keys($data); 
            if ( strpos($keys[0], 'assessment_')  !== false ) {
                $temp = explode("_", $keys[0]);
                array_push($final, $temp[1]);
            } 
        }
        return $final;
    }

}
