<?php

namespace App\Repositories;

use App\Models\ApprovalRequest;
use App\Models\Resource;
use App\Models\College;
use App\Models\ApprovalRequestComment;
use Illuminate\Http\Request;
use App\Notifications\ResourceSubmittedForApproval;
use App\Notifications\ResourceApprovalUpdated;
use App\Notifications\NewResourcePosted;
use Notification;

class ApprovalRequestRepository
{
    protected $model;

    public function __construct(ApprovalRequest $model)
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
        $posts = $this->model->with(['resource', 'user'])
                        ->has('resource')
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
            $posts = $this->model->with(['resource', 'user'])
                ->has('resource')
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

    public function getResourceCount($resourceId, $status)
    {
        return $this->model
                        ->where('resource_id', $resourceId)
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
        $post = ApprovalRequest::findOrFail($id);

        if ($action == 'approve') {
            $post->approval_status = Resource::APPROVAL_STATUS_APPROVED;
            $post->approved_by = auth()->user()->id;

            $post->resource->approval_status = Resource::APPROVAL_STATUS_APPROVED;
            $post->resource->published = true;
            $post->resource->approved_by = auth()->user()->id;
            $post->resource->allow_edit = 0;
            $post->resource->save();
            $text = 'approved';
        } elseif ($action == 'undo') {
            $post->approval_status = Resource::APPROVAL_STATUS_PENDING;
            $post->approved_by = null;
            $post->resource->approval_status = Resource::APPROVAL_STATUS_PENDING;
            $post->resource->approved = 0;
            $post->resource->approved_by = auth()->user()->id;
            $post->resource->published = 0;
            $post->resource->allow_edit = 1;
            $post->resource->save();
            $text = 'undo';
        } else {
            if (auth()->user()->isAdmin()) {
                $post->resource->is_locked = 1;
            }

            $post->approval_status = Resource::APPROVAL_STATUS_REJECTED;
            $post->approved_by = auth()->user()->id;
            $post->resource->approval_status = Resource::APPROVAL_STATUS_REJECTED;
            $post->resource->published = 0;
            $post->resource->approved_by = auth()->user()->id;
            $post->resource->allow_edit = 0;
            $post->resource->save();
            $text = 'rejected';
        }

        $post->save();

        // notify to all student teachers
        if ($action == 'approve') {
            $students = UserRepository::getStudentTeachers();

            Notification::send($students, new NewResourcePosted($post->resource));
        }

        // notify to admin and manager users
        if ($action == 'approve' || $action == 'reject') {
            // $users = UserRepository::getAdminAndManager();
            $users = UserRepository::getAdminAndManagerOfSameCollege(auth()->user());

            Notification::send($users, new ResourceApprovalUpdated($post, $text));

            // notify to submitted user
            Notification::send($post->user, new ResourceApprovalUpdated($post, $text));
        }

        return $text;
    }
}
