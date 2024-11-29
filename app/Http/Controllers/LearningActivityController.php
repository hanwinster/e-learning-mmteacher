<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\LearningActivity;
use Illuminate\Support\Facades\Notification;
use Spatie\MediaLibrary\Models\Media;
use App\Repositories\CourseLearnerRepository;
use App\Repositories\CourseRepository;

class LearningActivityController extends Controller
{
    // private $learningActivity;
    // private $learningActivityUser;
    private $repository;
    private $clRepo;

    public function __construct(CourseRepository $repository,CourseLearnerRepository $clRepo)
    {
        $this->repository = $repository;
        $this->clRepo = $clRepo;
    }

    public function show(LearningActivity $learningActivity)
    {
        $course = Course::findOrFail($learningActivity->course_id);
        $isLectureNull = $learningActivity->lecture_id == null ? true : false;
        $lectures = $course->lectures()->orderBy('id')->get();
        
        $userLectures = auth()->user()->learningLectures;
        $courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
        $completed = $courseLearner->completed;
        $status = $courseLearner->status;
        $percentage = $courseLearner->percentage;
        $currentSection = $learningActivity;
        $learningActivityMedias = Media::all()->where('model_type', LearningActivity::class)->where('model_id',$learningActivity->id); //->first();
        //dd($learningActivityMedias);exit;
        $lecturesMedias = $learningActivityMedias;//Media::all()->where('model_type', Lecture::class);
        //dd($learningActivityMedias->custom_properties['gdrive_link']);exit;
        $route = route('courses.learning-activity', [$learningActivity]);
        $this->clRepo->updatelastVisited($course->id, auth()->user()->id, $route);  
        if( $isLectureNull ) { 
            $previousSection = $this->repository->getRouteFromValue($this->repository->getPrevSection('learning_'.$learningActivity->id, $completed));
            $nextSection = $this->repository->getRouteFromValue($this->repository->getNextSection('learning_'.$learningActivity->id, $completed));
            if($course->item_affect_certification == 0 && $this->clRepo->isPartTheLastONe($completed, 'learning_'.$learningActivity->id)) {
                $nextSection = route('courses.evaluation', [$course]);
            }
        } else { 
            $previousSection = $this->repository->getRouteFromValue($this->repository->getPrevSection('lla_'.$learningActivity->id, $completed)); 
            $nextSection = $this->repository->getRouteFromValue($this->repository->getNextSection('lla_'.$learningActivity->id, $completed));
            if($course->item_affect_certification == 0 && $this->clRepo->isPartTheLastONe($completed, 'lla_'.$learningActivity->id)) {
                $nextSection = route('courses.evaluation', [$course]);
            }
        }
        return view('frontend.courses.learning-activity', compact('learningActivity', 'course', 'lectures','lecturesMedias', 'learningActivityMedias',
         'userLectures','completed','status','percentage','previousSection', 'nextSection', 'currentSection'));
    }

    public function updateCompletion(Request $request)
    {
        $courseId = $request->all()['course_id'];
        $findValue = $request->all()['find_val'];
        $userId = $request->all()['user_id'];
        $learningActivityId = $request->all()['learning_activity_id'];
        if( $this->clRepo->performCompletionLogic($courseId, $userId, $findValue, true) ) {
            $learningActivity = LearningActivity::findOrFail($learningActivityId);
            //$course = Course::findOrFail($courseId);
            //$this->learnCourse($course, $lecture); 
            return redirect()->route('courses.learning-activity', [$learningActivity] )->with('success', 'Updated!');
        } else {
            return response()->json(['error' => 'error occured while updating!']);
        }
    }

    public function updateCompletionPrev(Request $request)
    {
        $courseId = $request->all()['course_id'];
        $findValue = $request->all()['find_val'];
        $userId = $request->all()['user_id'];
        $learningActivityId = $request->all()['learning_activity_id'];
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
        $learningActivityId = $request->all()['learning_activity_id'];
        $nextRoute = $request->all()['next'];
        if( $this->clRepo->performCompletionLogic($courseId, $userId, $findValue, true) ) {
            return redirect($nextRoute)->with('success', trans('Updated the previous section!')); 
        } else {
            return response()->json(['error' => 'error occured while updating!']);
        }
    }

    

}
