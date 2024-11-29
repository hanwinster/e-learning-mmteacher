<?php

namespace App\Http\Controllers\Member;

// use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Resource;
use App\Models\ApprovalRequest;
use App\Repositories\UserRepository;
use App\Repositories\ApprovalRequestRepository;
use App\Notifications\ResourceSubmittedForApproval;
use App\Http\Requests\RequestSubmitResource as Request;
use Notification;

class ApprovalRequestController extends Controller
{
    protected $repository;

    public function __construct(ApprovalRequestRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
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
            $posts = $this->repository->indexForManager(request());
        }

        $approvalStatus = Resource::APPROVAL_STATUS;

        return view('frontend.member.request.list', compact('posts', 'approvalStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $post = Resource::findOrFail($id);

        return view('frontend.member.request.form', compact('post'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $post = Resource::findOrFail($id);

        // Resource now has only approval request. Overwrite existing one.
        $approvalRequest = ApprovalRequest::where('user_id', auth()->user()->id)
            ->where('resource_id', $id)->first();

        if (!$approvalRequest) {
            $approvalRequest = new ApprovalRequest();
        }

        $approvalRequest->resource_id = $id;
        $approvalRequest->user_id = auth()->user()->id;
        $approvalRequest->description = $request->input('description');
        $approvalRequest->approval_status = Resource::APPROVAL_STATUS_PENDING;
        $approvalRequest->isRequested = 1;
        $approvalRequest->approved_by = null;
        $approvalRequest->save();

        $post->approval_status = Resource::APPROVAL_STATUS_PENDING;
        $post->allow_edit = 0;
        $post->save();

        // notify to admin and manager users
        // $users = UserRepository::getAdminAndManager();
        $users = UserRepository::getAdminAndManagerOfSameCollege(auth()->user());

        Notification::send($users, new ResourceSubmittedForApproval($approvalRequest));

        if ($request->input('btnSubmitNew')) {
            return redirect()->route('member.resource.create')
            ->with('success', 'You have successfully submitted the resource for approval.');
        }

        return redirect()->route('member.resource.index')
            ->with('success', 'You have successfully submitted the resource for approval.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = ApprovalRequest::findOrFail($id);

        return view('frontend.member.request.show', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus($id, $action)
    {
        $text = $this->repository->updateStatus($id, $action);

        return redirect()->route('member.approval-request.index')
            ->with('success', 'You have ' . $text . ' the resource for approval request.');
    }
}
