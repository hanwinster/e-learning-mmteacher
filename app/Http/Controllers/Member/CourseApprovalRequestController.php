<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Course;
use App\Models\CourseApprovalRequest;
use App\Repositories\UserRepository;
use App\Repositories\CourseApprovalRequestRepository;
use App\Notifications\CourseSubmittedForApproval;
use Notification;

class CourseApprovalRequestController extends Controller
{
    protected $repository;

    public function __construct(CourseApprovalRequestRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the course.
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_type = currentUserType();

        if ($user_type == User::TYPE_ADMIN) {
            $posts = $this->repository->index(request());
        } elseif ($user_type == User::TYPE_MANAGER) {
            if(auth()->user()->isUnescoManager()) {
                $posts = $this->repository->index(request());
            } else {
                $posts = $this->repository->indexForManager(request());
            }
            
        }

        $approvalStatus = Course::APPROVAL_STATUS;

        return view('frontend.member.course.request.list', compact('posts', 'approvalStatus'));
    }

    /**
     * Show the form for creating a new course.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $post = Course::findOrFail($id);

        return view('frontend.member.course.request.form', compact('post'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $course_id)
    {
        $post = Course::findOrFail($course_id);
        // course now has only approval request. Overwrite existing one.
        $approvalRequest = CourseApprovalRequest::where('user_id', auth()->user()->id)
                            ->where('course_id', $course_id)->first();

        if (!$approvalRequest) {
            $approvalRequest = new CourseApprovalRequest();
        }

        $approvalRequest->course_id = $course_id;
        $approvalRequest->user_id = auth()->user()->id;
        $approvalRequest->description = $request->input('description');
        $approvalRequest->approval_status = Course::APPROVAL_STATUS_PENDING;
        $approvalRequest->approved_by = null;
        $approvalRequest->save();

        $post->approval_status = Course::APPROVAL_STATUS_PENDING;
        $post->allow_edit = 0;
        $post->save();

        // notify to admin and manager users
        // $users = UserRepository::getAdminAndManager();
        
        $users = UserRepository::getAdminAndManagerOfSameCollege(auth()->user());
        Notification::send($users, new CourseSubmittedForApproval($approvalRequest));

        return redirect()->route('member.course.index')
            ->with('success', 'You have successfully submitted the course for approval.');
    }

    /**
     * Display the specified course.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = CourseApprovalRequest::findOrFail($id);

        return view('frontend.member.course.request.show', compact('post'));
    }

    /**
     * Update the specified course in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus($id, $action)
    {
        $text = $this->repository->updateStatus($id, $action);

        return redirect()->route('member.course-approval-request.index')
            ->with('success', 'You have ' . $text . ' the course for approval request.');
    }
}
