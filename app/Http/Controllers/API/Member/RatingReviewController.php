<?php

namespace App\Http\Controllers\API\Member;

use Illuminate\Http\Request;
use App\Models\RatingReview;
use App\User;
use App\Models\Course;
use App\Http\Controllers\Controller;
use App\Repositories\RatingReviewRepository;
use App\Repositories\CourseLearnerRepository;
use Illuminate\Support\Facades\Validator;
use App\Notifications\CourseReviewSubmitted;
use Notification;
use Lang;

class RatingReviewController extends Controller
{
    protected $repository;
    protected $clRepo;

    public function __construct(RatingReviewRepository $repository, CourseLearnerRepository $clRepo)
    {
        $this->repository = $repository;
        $this->clRepo = $clRepo;
    }

    public function index(Request $request, $courseId)
    {
        if( $request->header('Content-Language') ) {
            if(!setLanguageForSession($request->header('Content-Language'))) { // if language is set and it's not supported
                return response(['errors' => 'Provided language is not supported'], 404);
            }
            $lang = $request->header('Content-Language');
            try {
                $reviews = RatingReview::where('course_id', $courseId)->get(); //dd($reviews);exit;
                if($reviews && count($reviews) > 0) {
                    $list = [];
                    foreach ($reviews as $rev) {
                        $list[] = [
                            'id' => $rev->id, 
                            'rating' => $rev->rating, 
                            'review' => $rev->remark,
                            'username' => $rev->user ? $rev->user->name : 'deactivated user',
                            'created' => $rev->created_at,
                            'updated' => $rev->updated_at
                        ];
                    }
                    return response()->json([ 'data' => $list], 200);
                } else {
                    return response()->json(['code' => 404, 'message' => 'Reviews Not Found'], 404);
                }      
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(['code' => 404, 'message' => 'Reviews Not Found'], 404);
            }
        } else { 
                return response(['errors' => 'Content Language is missing in the header'], 400);
        }
    }
    
    public function saveRatingReview(Request $request, $courseId)
    {   
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        // if(!setLanguageForSession($request->header('Content-Language'))) {
		// 	return response(['errors' => trans('Provided language is not supported')], 404);
		// }
        $validator = Validator::make($request->all(), [
            'rating' => 'required|int|between:1,5',
            'remark' => 'nullable|max:500'
		]);
		if ($validator->fails()) {
			return response(['code' => 422, 'errors'=> $validator->errors()->all()], 422);
		}
        $course = Course::findOrFail($courseId);
        if($course) {
            
            $isReviewerALearner = false;
            foreach($course->courseLearners as $cl) {
                if($cl->id == auth()->user()->id) {
                    $isReviewerALearner = true;
                }
            }
            if(!$isReviewerALearner) {
                return response(['code' => 422, 'errors' => trans('Current user still did not take the course to review it')], 422);
            }
            $courseReviewer = User::findOrFail(auth()->user()->id);
            $request['course_id'] = $courseId;
            $request['user_id'] = auth()->user()->id;
            $ratingReview = $this->repository->saveRecord($request);
            $courseCreator = User::findOrFail($course->user_id);
            $courseReviewArray = array(
                'courseCreator' => $courseCreator->name,
                'courseCreatorEmail' => $courseCreator->email,
                'courseReviewer' => $courseReviewer->name,
                'courseReviewerEmail' => $courseReviewer->email,
                'remark'=> $ratingReview->remark,
                'rating'=> $ratingReview->rating,
                'id' => $ratingReview->id,
                'courseTitle' => strip_tags($course->title),
                'course'=> $course->title
            );
            //dd($ratingReview->rating);exit;
            Notification::send($courseCreator, new CourseReviewSubmitted($courseReviewArray));
            unset($courseReviewArray['course']);
            return response()->json(['data' => $courseReviewArray ], 200);
        } else {
            return response(['code' => 404, 'errors'=> trans('Course is not found') ], 404);
        }
        
    }
}
