<?php

namespace App\Http\Controllers\API\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\LearningActivity;
use App\Models\Course;
use Spatie\MediaLibrary\Models\Media;
use App\Repositories\CourseRepository;
use App\Repositories\CourseLearnerRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LearningActivityController extends Controller
{

    protected $clRepo;
    protected $repository;
    public function __construct(CourseLearnerRepository $clRepository, CourseRepository $cr)
    {   
       
        $this->clRepo = $clRepository;
        $this->repository = $cr;
    }
    
    public function getLAContents(Request $request, $courseId, $laId)
    {
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        try {
            $la = LearningActivity::where('id',$laId)->where('course_id',$courseId)->first();
        } catch (ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Learning activity is not found'], 404);
        }
        if(!$la) {
            return response()->json(['code' => 404, 'message' => 'Learning activity is not found'], 404);
        }
        $learningActivityMedia = convertObjectArrayToArrayOfObjects(Media::all()->where('model_type', LearningActivity::class)->where('model_id',$la->id));
        $course = $la->course;
        $downloadOption = $course->downloadable_option;
        $courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
        if($course->order_type === 'default') {
            $courseLearner->completed = $this->repository->modifyCompletedToSupportOverview($courseLearner->completed);
        }
        $data = [
            'learning_activity' => $la,        
            'learning_activity_media' => $learningActivityMedia,
            'download_option' => $downloadOption,  
            'course_learner' => $courseLearner
        ];      
        return response()->json(['data' =>  $data], 200);
    }


}