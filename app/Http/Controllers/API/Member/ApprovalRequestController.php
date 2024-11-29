<?php

namespace App\Http\Controllers\API\Member;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\User;
use App\Models\ApprovalRequest;
use App\Repositories\UserRepository;
use App\Repositories\ApprovalRequestRepository;
use App\Notifications\ResourceSubmittedForApproval;
use App\Http\Requests\RequestSubmitResource as Request;
use Notification;
use App\Http\Resources\ApprovalRequestResource;

class ApprovalRequestController extends Controller
{
    protected $repository;

    public function __construct(ApprovalRequestRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
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

        return ApprovalRequestResource::collection($posts);
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
        $approvalRequest->approved_by = null;
        $approvalRequest->save();

        $post->approval_status = Resource::APPROVAL_STATUS_PENDING;
        $post->save();

        // notify to admin and manager users
        // $users = UserRepository::getAdminAndManager();
        $users = UserRepository::getAdminAndManagerOfSameCollege(auth()->user());

        Notification::send($users, new ResourceSubmittedForApproval($approvalRequest));

        $json = ['message' => 'You have successfully submitted the resource for approval.'];
        return response()->json($json);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $post = ApprovalRequest::with(['resource'])->findOrFail($id);

        if (!$post = ApprovalRequest::where('id', $id)->first()) {
            return response()->json(['message' => 'Resource not found']);
        }

        return new ApprovalRequestResource($post);
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

        return response()->json(['message' => 'You have ' . $text . ' the resource for approval request.']);
    }
}
