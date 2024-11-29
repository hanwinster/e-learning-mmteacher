<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\LiveSession;
use App\Models\LiveSessionUser;
use Illuminate\Support\Facades\Notification;
use Spatie\MediaLibrary\Models\Media;
use App\Repositories\CourseLearnerRepository;
use App\Repositories\CourseRepository;
use App\Repositories\LiveSessionUserRepository;
use App\Traits\ZoomJWT;

class LiveSessionController extends Controller
{
    // private $session;
    // private $sessionUser;
    use ZoomJWT;
    private $clRepo;
    private $lsuRepo;
    private $repository;
    
    public function __construct(CourseRepository $repository, CourseLearnerRepository $clRepo, LiveSessionUserRepository $lsuRepo)
    {
        $this->repository = $repository;
        $this->lsuRepo = $lsuRepo;
        $this->clRepo = $clRepo;
    }

    public function show(LiveSession $session)
    {  // dd($session);exit;
        $course = Course::findOrFail($session->course_id);
        $isLectureNull = $session->lecture_id == null ? true : false;
        $lectures = $course->lectures()->orderBy('id')->get();
        $lecturesMedias = Media::all()->where('model_type', Lecture::class);
        $userLectures = auth()->user()->learningLectures;
        $courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
        $completed = $courseLearner->completed;
        $status = $courseLearner->status;
        $percentage = $courseLearner->percentage;
        $liveSessionUser = LiveSessionUser::where('session_id', $session->id)
                                            ->where('user_id', auth()->user()->id)->first();
        $route = route('courses.view-live-session', [$session]);
        $this->clRepo->updatelastVisited($course->id, auth()->user()->id, $route);  
        
        if( $isLectureNull ) { 
            $previousSection = $this->repository->getRouteFromValue($this->repository->getPrevSection('session_'.$session->id, $completed));
            $nextSection = $this->repository->getRouteFromValue($this->repository->getNextSection('session_'.$session->id, $completed));
            if($course->item_affect_certification == 0 && $this->clRepo->isPartTheLastONe($completed, 'session_'.$session->id)) {
                $nextSection = route('courses.evaluation', [$course]);
            }
        } else { 
            $previousSection = $this->repository->getRouteFromValue($this->repository->getPrevSection('lsess_'.$session->id, $completed)); 
            $nextSection = $this->repository->getRouteFromValue($this->repository->getNextSection('lsess_'.$session->id, $completed));
            if($course->item_affect_certification == 0 && $this->clRepo->isPartTheLastONe($completed, 'lsess_'.$session->id)) {
                $nextSection = route('courses.evaluation', [$course]);
            }
        }
       // dd($nextSection);exit;
        return view('frontend.courses.live-session', compact('session', 'course', 'lectures','lecturesMedias',
         'userLectures','completed','status','percentage','liveSessionUser', 'previousSection', 'nextSection'));
    }

   
    public function registerSession(Request $request)
    {        
        $res = $this->addRegistrantToZoomMeeting($request->all(), $request->all()['meeting_id']);
        $courseId = $request->all()['course_id'];
        $findValue = $request->all()['find_val'];
        $userId = $request->all()['user_id'];
        $session = LiveSession::findOrFail($request->all()['session_id']);
        if($res) {
            $this->clRepo->performCompletionLogic($courseId, $userId, $findValue, true);
            $this->lsuRepo->saveRecord($res, null);
            return redirect()->route('courses.view-live-session', [$session] )->with('message', trans('Registered!'));
        } else {
            return redirect()->route('courses.view-live-session', [$session] )->with('error', trans('error occured while registering!'));
        }
    }

    public function addRegistrantToZoomMeeting(Array $validatedArray, string $id)
    {     
        
        $path = 'users/me/meetings/' . $id.'/registrants';
        $response = $this->zoomPost($path, [
            'first_name' => $validatedArray['name'], //last_name is optional
            'email' => $validatedArray['email'], //should be default for now           
        ]);
        $validatedArray['status'] = 'registered'; //dd($validatedArray, $id);exit;         
        if($response->status() === 200 || $response->status() === 201 || $response->status() === 204) {
            return $validatedArray;
        } else {// dd($response);exit;
            return false;       
        }
    }

    // public function updateCompletion(Request $request)
    // {
    //     $courseId = $request->all()['course_id'];
    //     $findValue = $request->all()['find_val'];
    //     $userId = $request->all()['user_id'];
    //     $sessionId = $request->all()['session_id'];
    //     if( $this->clRepo->performCompletionLogic($courseId, $userId, $findValue, true) ) {
    //         $session = LiveSession::findOrFail($sessionId);
    //         //$course = Course::findOrFail($courseId);
    //         //$this->learnCourse($course, $lecture); 
    //         return redirect()->route('courses.view-live-session', [$session] )->with('success', 'Updated!');
    //     } else {
    //         return response()->json(['error' => 'error occured while updating!']);
    //     }
    // }

    public function updateCompletionPrev(Request $request)
    {
        $courseId = $request->all()['course_id'];
        $findValue = $request->all()['find_val'];
        $userId = $request->all()['user_id'];
        $sessionId = $request->all()['session_id'];
        $prevRoute = $request->all()['previous'];
        if( $this->clRepo->performCompletionLogic($courseId, $userId, $findValue, true) ) {
            return redirect($prevRoute)->with('success', trans('Updated the previous section!'));
        } else {
            return response()->json(['error' => 'error occured while updating!']);
        }
    }

    public function updateCompletionNext(Request $request)
    {
        $courseId = $request->all()['course_id'];
        $findValue = $request->all()['find_val'];
        $userId = $request->all()['user_id'];
        $sessionId = $request->all()['session_id'];
        $nextRoute = $request->all()['next'];
        if( $this->clRepo->performCompletionLogic($courseId, $userId, $findValue, true) ) {
            return redirect($nextRoute)->with('success', trans('Updated the previous section!'));
        } else {
            return response()->json(['error' => 'error occured while updating!']);
        }
    }

}
