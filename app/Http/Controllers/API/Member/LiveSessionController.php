<?php

namespace App\Http\Controllers\API\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\LiveSession;
use App\Models\LiveSessionUser;
use App\Models\Course;
use Spatie\MediaLibrary\Models\Media;
use App\Repositories\CourseLearnerRepository;
use App\Repositories\CourseRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\LiveSessionUserRepository;
use App\Repositories\LiveSessionRepository;
use App\Traits\ZoomJWT;

class LiveSessionController extends Controller
{
    protected $clRepo;
    use ZoomJWT;
    protected $lsuRepo;
    protected $repository;

    public function __construct(CourseLearnerRepository $clRepository, LiveSessionUserRepository $lsuRepo, CourseRepository $cr)
    {   
        $this->clRepo = $clRepository;
        $this->lsuRepo = $lsuRepo;
        $this->repository = $cr;
    }
    
    public function getLiveContents(Request $request, $courseId, $sessionId)
    {
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        try {
            $session = LiveSession::where('id',$sessionId)->where('course_id',$courseId)->first();
        } catch (ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'LiveSession is not found'], 404);
        }
        if(!$session) {
            return response()->json(['code' => 404, 'message' => 'LiveSession is not found'], 404);
        }
    
        $course = $session->course;
        $downloadOption = $course->downloadable_option;
        $courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
        //$courseLearner->completed = $this->repository->convertCompletedArrayToAPISupportedFormat($courseLearner->completed, $course);
        $completedMobileFormat = $this->repository->modifyCompletedToSupportOverview($courseLearner->completed);
        $courseLearner->completed = $completedMobileFormat;  
        $liveSessionUser = LiveSessionUser::where('session_id', $sessionId)
                                            ->where('user_id', auth()->user()->id)->first();
        unset($session->course);
        $data = [
            'live_session' => $session,        
            'download_option' => $downloadOption,  
            'course_learner' => $courseLearner,
            'is_user_registered' => $liveSessionUser ? true : false
        ];      
        return response()->json(['data' =>  $data], 200);
    }

    public function registerSession(Request $request, $courseId, $sessionId)
    {   
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        $session = LiveSession::where('id',$sessionId)->where('course_id',$courseId)->first();
        if(!$session) {
            return response()->json(['code' => 404, 'message' => 'LiveSession is not found'], 404);
        }
        if(!LiveSessionRepository::stillCanRegister($session->start_date,$session->start_time)) {
            return response()->json(['code' => 404, 'message' => 'LiveSession is expired and cannot register'], 404);
        }
        $validator = Validator::make($request->all(), [
            'meeting_id' => 'required|int',
            'current_section' => 'required|string',
		]);
		if ($validator->fails()) {
			return response(['errors'=> $validator->errors()->all()], 422);
		}
        $validatedArray = $request->all();
        $validatedArray['name'] = auth()->user()->name;
        $validatedArray['email'] = auth()->user()->email;
        $validatedArray['session_id'] = $sessionId;
        $res = $this->addRegistrantToZoomMeeting($validatedArray, $request->all()['meeting_id']);
       
        $findValue = $request->all()['current_section'];
        $userId = auth()->user()->id;
        
        if($res) {// dd($res);exit;
            $this->clRepo->performCompletionLogic($courseId, $userId, $findValue, true);
            $this->lsuRepo->saveRecord($res, null);
            return response()->json(['data' => ['message' =>'Registered!', 'join_url' => $session->join_url ] ] );
        } else {
            return response()->json(['code' => 500, 'message' => 'error occured while registering!'], 500 );
        }
    }

    protected function addRegistrantToZoomMeeting(Array $validatedArray, string $id) 
    {   //  dd($validatedArray);exit;
        $path = 'users/me/meetings/' . $id.'registrants';
        $response = $this->zoomPost($path, [
            'first_name' => $validatedArray['name'], //last_name is optional
            'email' => $validatedArray['email'], //should be default for now           
        ]);
        $validatedArray['status'] = 'registered'; //dd($validatedArray, $id);exit;         
        if($response->status() === 200 || $response->status() === 201 ) {
            return $validatedArray;
        } else {// dd($response);exit;
            return false;       
        }
    }

}