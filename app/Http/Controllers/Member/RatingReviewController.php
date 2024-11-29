<?php

namespace App\Http\Controllers\Member;

use App\Models\Review;
use App\Models\Resource;
use App\Models\Course;
use App\User;
use App\Http\Controllers\Controller;
use App\Repositories\RatingReviewRepository;
use App\Http\Requests\RequestRatingReview as RatingReviewRequest;
use App\Notifications\CourseReviewSubmitted;
use Notification;

class RatingReviewController extends Controller
{
    protected $repository;

    public function __construct(RatingReviewRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\RequestRatingReview $request
     * @return \Illuminate\Http\Response
     */
    public function store(RatingReviewRequest $request)
    {
        $validated = $request->validated();
        $ratingReview = $this->repository->saveRecord($request);
        $courseReviewer = User::findOrFail($request->all()['user_id']);
        $courseId = $request->all()['course_id'];
        $course = Course::findOrFail($courseId);
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
            'course'=> $course
        );
        //dd($ratingReview->rating);exit;
        Notification::send($courseCreator, new CourseReviewSubmitted($courseReviewArray));
        return redirect()->route('courses.show', $course->slug)->with('success', 'Successfully saved');
    }
}
