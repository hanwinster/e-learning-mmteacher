<?php

namespace App\Repositories;

use App\Models\CourseApprovalRequest;
use App\Models\Course;
use App\Models\College;
use Illuminate\Http\Request;
use App\Notifications\CourseApprovalUpdated;
use App\Notifications\NewCoursePosted;
use Notification;

class CourseApprovalRequestRepository
{
    protected $model;

    public function __construct(CourseApprovalRequest $model)
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
        $posts = $this->model->with(['course', 'user'])
                        ->has('course')
                        ->has('user')
                        ->withSearch($request->input('search'))
                        ->withApprovalStatus($request->input('approval_status'))
                        ->orderBy('updated_at', 'desc')
                        ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return $posts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForManager(Request $request)
    {
        $user = auth()->user();

        if ($college = College::find($user->ec_college)) {
            $posts = $this->model->with(['course', 'user'])
                ->has('course')
                ->whereHas('user', function ($q) use ($user) {
                    $q->where('ec_college', '=', $user->ec_college);
                })
                ->withSearch($request->input('search'))
                ->withApprovalStatus($request->input('approval_status'))
                ->orderBy('updated_at', 'desc')
                ->paginate($request->input('limit'));

            $posts->appends($request->all());
        } else {
            return null;
        }

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

    public function getCourseCount($course_id, $status)
    {
        return $this->model
                        ->where('course_id', $course_id)
                        ->where('approval_status', $status)
                        ->count();
    }

    public function saveRecord($request, $id = null)
    {
        if (isset($id)) {
            $this->model = $this->find($id);
        } else {
            $this->model->user_id = auth()->user()->id;
        }

        $this->model->fill($request->all());

        if ($request->input('approval_status') !== null) {
            $this->model->approval_status = $request->input('approval_status', 0);
        }

        $this->model->save();
    }

    public function updateStatus($id, $action)
    {
        $post = CourseApprovalRequest::findOrFail($id);

        if ($action == 'approve') {
            $post->approval_status = Course::APPROVAL_STATUS_APPROVED;
            $post->approved_by = auth()->user()->id;

            $post->course->approval_status = Course::APPROVAL_STATUS_APPROVED;
            $post->course->is_published = true;
            $post->course->approved_by = auth()->user()->id;
            $post->course->allow_edit = 0;
            $post->course->save();
            $text = 'approved';
        } elseif ($action == 'undo') {
            $post->approval_status = Course::APPROVAL_STATUS_PENDING;
            $post->approved_by = null;
            $post->course->approval_status = Course::APPROVAL_STATUS_PENDING;
            $post->course->approved_at = null;
            $post->course->approved_by = auth()->user()->id;
            $post->course->is_published = 0;
            $post->course->allow_edit = 1;
            $post->course->save();
            $text = 'undo';
        } else {
            // if (auth()->user()->isAdmin()) {
            //     $post->course->is_locked = 1;
            // }

            $post->approval_status = Course::APPROVAL_STATUS_REJECTED;
            $post->approved_by = auth()->user()->id;
            $post->course->approval_status = Course::APPROVAL_STATUS_REJECTED;
            $post->course->is_published = 0;
            $post->course->approved_by = auth()->user()->id;
            $post->course->allow_edit = 0;
            $post->course->save();
            $text = 'rejected';
        }

        $post->save();

        // notify to all student teachers
        if ($action == 'approve') {
            //$students = UserRepository::getStudentTeachers();
            //TODO to be improved this feature in phase 2
           // $learners = removeFakeEmails(UserRepository::getLearnersTookTheCourseFromSameCategory($post->course->course_categories));
           // \Log::info($learners);
           // Notification::send($learners, new NewCoursePosted($post->course));
        }

        // notify to admin and manager users
        if ($action == 'approve' || $action == 'reject') {
            // $users = UserRepository::getAdminAndManager();
            $users = UserRepository::getAdminAndManagerOfSameCollege(auth()->user());

            Notification::send($users, new CourseApprovalUpdated($post, $text));

            // notify to submitted user
            Notification::send($post->user, new CourseApprovalUpdated($post, $text));
        }

        return $text;
    }
}
