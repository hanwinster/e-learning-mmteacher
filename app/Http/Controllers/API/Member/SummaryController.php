<?php

namespace App\Http\Controllers\API\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Summary;
use App\Models\Course;
use Spatie\MediaLibrary\Models\Media;
use App\Repositories\CourseLearnerRepository;
use App\Repositories\CourseRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SummaryController extends Controller
{
    protected $clRepo;
    protected $repository;

    public function __construct(CourseLearnerRepository $clRepository, CourseRepository $cr)
    {   
        $this->clRepo = $clRepository;
        $this->repository = $cr;
    }
    
    public function getSummaryContents(Request $request, $courseId, $summaryId)
    {
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        try {
            $summary = Summary::where('id',$summaryId)->where('course_id',$courseId)->first();
        } catch (ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Summary is not found'], 404);
        }
        if(!$summary) {
            return response()->json(['code' => 404, 'message' => 'Summary is not found'], 404);
        }
        $summaryMedia = convertObjectArrayToArrayOfObjects(Media::all()->where('model_type', Summary::class)->where('model_id',$summary->id));
        $course = $summary->course;
        $downloadOption = $course->downloadable_option;
        $courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
        if($course->order_type === 'default') {
            $courseLearner->completed = $this->repository->modifyCompletedToSupportOverview($courseLearner->completed);
        } 
        $data = [
            'summary' => $summary,        
            'summary_media' => $summaryMedia,
            'download_option' => $downloadOption,  
            'course_learner' => $courseLearner
        ];      
        return response()->json(['data' =>  $data], 200);
    }


}