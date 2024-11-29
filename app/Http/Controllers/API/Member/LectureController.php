<?php

namespace App\Http\Controllers\API\Member;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Lecture;
use App\Models\Course;
use Spatie\MediaLibrary\Models\Media;
use App\Repositories\CourseRepository;
use App\Repositories\DiscussionMessageRepository;
use App\Repositories\CourseLearnerRepository;

class LectureController extends Controller
{
    protected $repository;
    protected $disMesRepo;
    protected $clRepo;

    public function __construct(CourseRepository $repository, DiscussionMessageRepository $disMesRepo,
    CourseLearnerRepository $clRepository)
    {   
        $this->repository = $repository;
        $this->disMesRepo = $disMesRepo;
        $this->clRepo = $clRepository;
        
    }
    
    public function getLectureContents(Request $request, $courseId, $lectureId)
    {
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        try {
            $lecture = Lecture::where('id' ,$lectureId)->where('course_id',$courseId)->first();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Lecture is not found'], 404);
        }
        if(!$lecture) {
            return response()->json(['code' => 404, 'message' => 'Lecture is not found'], 404);
        }
        $course = Course::findOrFail($courseId);
        if(!$course->is_published && $course->user_id != auth()->user()->id) {
            return response()->json(['code' => 404, 'message' =>  'This course is currently unpublished'], 404);
        }
      
        $lecturesMedias = convertObjectArrayToArrayOfObjects(Media::all()->where('model_type', Lecture::class));
        $downloadOption = $course->downloadable_option;
        $courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
        $completed = $courseLearner->completed;
        
       // $status = $courseLearner->status;
       // $percentage = $courseLearner->percentage;
        $previousSection = $this->repository->getRouteFromValue($this->repository->getPrevSection('lect_'.$lecture->id, $completed));
        $nextSection = $this->repository->getRouteFromValue($this->repository->getNextSection('lect_'.$lecture->id, $completed));
        if($course->item_affect_certification == 0 && $this->clRepo->isPartTheLastONe($completed, 'lect_'.$lecture->id)) {
            $nextSection = route('courses.evaluation', [$course]);
        } 
        $route = route('courses.learn-course', [$lecture]);
        $this->clRepo->updatelastVisited($course->id, auth()->user()->id, $route);  
        if($course->order_type === 'default') {
            $courseLearner->completed = $this->repository->modifyCompletedToSupportOverview($courseLearner->completed);
        } 
        $data = [
            'lecture' => $lecture,
            'lecture_learning_activities' => $lecture->learningActivities,
            'lecture_quizzes' => $lecture->quizzes,
            'lecture_live_sessions' => $lecture->liveSessions,
            'lecture_summaries' => $lecture->summaries,
            'lecture_media' => $lecturesMedias,
            'downloadable_option' => $downloadOption,
            'course_learner' => $courseLearner

        ];      
        return response()->json(['data' =>  $data], 200);
    }

    public function downloadLecture(Request $request, $courseId, $lectureId)
    {
        try {
            $lecture = Lecture::findOrFail($lectureId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Lecture is not found'], 404);
        }
        $downloadPath = null;
        $tempPath = $lecture->getMedia('lecture_attached_file')->first()->getPath();
        $temp = $tempPath ? explode('/public', $tempPath) : null;
        if($temp && count($temp) == 2) {
            $downloadPath = env('APP_URL').'/storage'.$temp[1];
        }
        if( $lecture->getMedia('lecture_attached_file')->first()->getPath() && file_exists( $lecture->getMedia('lecture_attached_file')->first()->getPath() ) ) {
            return response()->json(['data' => ['file_path' => $downloadPath  ] ], 200);
        }
        
        return response()->json(['code' => 404, 'message' =>  'file not found'], 404);
    }

}