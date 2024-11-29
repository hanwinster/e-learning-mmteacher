<?php

namespace App\Http\Controllers\Member;

use App\Models\Resource;
use App\User;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\CollegeRepository;
use App\Http\Requests\RequestUserApproval as Request;
use App\Http\Requests\RequestUserUpdate;

class UserController extends Controller
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_type = currentUserType();

        if ($user_type == User::TYPE_ADMIN) {
            $posts = $this->repository->index(request());
        } elseif ($user_type == User::TYPE_MANAGER) {
            if(auth()->user()->is_unesco_mgr) {
                $posts = $this->repository->indexForUnescoManager(request());
            } else {
                $posts = $this->repository->indexForManager(request());
            }          
        }
        $approvalStatus = User::APPROVAL_STATUS;     
        return view('frontend.member.user.index', compact('posts', 'approvalStatus'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  App\Http\Requests\RequestUserApproval $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id, $action)
    {
        $text = $this->repository->updateStatus($id, $action);

        return redirect()->route('member.user.index')
            ->with('success', 'User has been updated.');
    }

    /**
     * Edit User
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = User::findOrFail($id);

        $user_types = UserRepository::getUserTypes();
        $ec_colleges = CollegeRepository::getItemList(true);

        return view('frontend.member.user.form', compact('post', 'user_types', 'ec_colleges'));
    }

    /**
     * Update User
     *
     * @param RequestUserUpdate $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestUserUpdate $request, $id)
    {
        $post = User::findOrFail($id);

        $post->user_type = $request->input('user_type');
        $post->ec_college = $request->input('ec_college');
        $post->save();

        return redirect()->route('member.user.index')
            ->with('success', 'User has been successfully updated.');
    }
}
