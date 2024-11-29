<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\Summary;
use Illuminate\Support\Facades\Notification;
use Spatie\MediaLibrary\Models\Media;
use App\Repositories\CourseLearnerRepository;
use App\Repositories\CourseRepository;

class SummaryController extends Controller
{
    // private $summary;
    // private $summaryUser;
    private $repository;
    private $clRepo;

    public function __construct(CourseRepository $repository,CourseLearnerRepository $clRepo)
    {
        $this->repository = $repository;
        $this->clRepo = $clRepo;
    }

    public function show(Summary $summary)
    {
        $course = Course::findOrFail($summary->course_id);
        $isLectureNull = $summary->lecture_id == null ? true : false;
        $lectures = $course->lectures()->orderBy('id')->get();
        
        $userLectures = auth()->user()->learningLectures;
        $courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
        $completed = $courseLearner->completed;
        $status = $courseLearner->status;
        $percentage = $courseLearner->percentage;
        $currentSection = $summary;
        $summaryMedias = Media::all()->where('model_type', Summary::class)->where('model_id',$summary->id); //->first();
        //dd($summaryMedias);exit;
        $lecturesMedias = $summaryMedias;//Media::all()->where('model_type', Lecture::class);
        //dd($summaryMedias->custom_properties['gdrive_link']);exit;
        $route = route('courses.summary', [$summary]);
        $this->clRepo->updatelastVisited($course->id, auth()->user()->id, $route);  
        if( $isLectureNull ) { 
            $previousSection = $this->repository->getRouteFromValue($this->repository->getPrevSection('summary_'.$summary->id, $completed));
            $nextSection = $this->repository->getRouteFromValue($this->repository->getNextSection('summary_'.$summary->id, $completed));
            if($course->item_affect_certification == 0 && $this->clRepo->isPartTheLastONe($completed, 'summary_'.$summary->id)) {
                $nextSection = route('courses.evaluation', [$course]);
            }
        } else { 
            $previousSection = $this->repository->getRouteFromValue($this->repository->getPrevSection('lsum_'.$summary->id, $completed)); 
            $nextSection = $this->repository->getRouteFromValue($this->repository->getNextSection('lsum_'.$summary->id, $completed));
            if($course->item_affect_certification == 0 && $this->clRepo->isPartTheLastONe($completed, 'lsum_'.$summary->id)) {
                $nextSection = route('courses.evaluation', [$course]);
            }
        }
        return view('frontend.courses.summary', compact('summary', 'course', 'lectures','lecturesMedias', 'summaryMedias',
         'userLectures','completed','status','percentage','previousSection', 'nextSection', 'currentSection'));
    }

    public function updateCompletion(Request $request)
    {
        $courseId = $request->all()['course_id'];
        $findValue = $request->all()['find_val'];
        $userId = $request->all()['user_id'];
        $summaryId = $request->all()['summary_id'];
        if( $this->clRepo->performCompletionLogic($courseId, $userId, $findValue, true) ) {
            $summary = Summary::findOrFail($summaryId);
            //$course = Course::findOrFail($courseId);
            //$this->learnCourse($course, $lecture); 
            return redirect()->route('courses.summary', [$summary] )->with('success', 'Updated!');
        } else {
            return response()->json(['error' => 'error occured while updating!']);
        }
    }

    public function updateCompletionPrev(Request $request)
    {
        $courseId = $request->all()['course_id'];
        $findValue = $request->all()['find_val'];
        $userId = $request->all()['user_id'];
        $summaryId = $request->all()['summary_id'];
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
        $summaryId = $request->all()['summary_id'];
        $nextRoute = $request->all()['next'];
        if( $this->clRepo->performCompletionLogic($courseId, $userId, $findValue, true) ) {
            return redirect($nextRoute)->with('success', trans('Updated the previous section!')); 
        } else {
            return response()->json(['error' => 'error occured while updating!']);
        }
    }

    

}
