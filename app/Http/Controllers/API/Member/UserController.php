<?php

namespace App\Http\Controllers\API\Member;

use App\Models\Resource;
use App\User;
use App\Models\CourseLearner;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\RequestUserApproval as Request;
use App\Http\Requests\RequestUserUpdate;
use App\Http\Resources\UserResource;

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
            $posts = $this->repository->indexForManager(request());
        } elseif ($user_type == User::TYPE_TEACHER_EDUCATOR) {
            $posts = $this->repository->indexForTeacherEducator(request());
        }

        if ($posts) {
            return UserResource::collection($posts);
        }

        return response()->json(['message' => 'There are no users.', 'data' => null]);
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

        return response()->json(['message' => 'User has been updated']);
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

        return response()->json(['message' => 'User has been successfully updated.']);
    }

    /**
     * Update User
     *
     * @param Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function deactivate(\Illuminate\Http\Request $request)
    { 
        $user = User::where('id', auth()->user()->id)->first();
        if(!$user) {
            return response(['errors' => 'Cannot find the user account to get deactivated!'], 404 );
        }
        $userId = auth()->user()->id;
        
        if(Userrepository::getUserCreatedCourses($userId)) {
            return response(['errors' => 'Cannot deactivate as you created a course/courses! Please delete them first'], 400 );
        }
       
       // dd(Userrepository::getUserTakenCourses($userId));exit; 
        //delete if there's a discussion message, rating & review, etc
        if(Userrepository::getUserTakenCourses($userId)) { //return true or false only
            $courseLearner = CourseLearner::where('user_id', $userId)->get();
           // dd($courseLearner);exit;
            if($user->assignment_user) {
               foreach($user->assignment_user as $au) {
                    $au->delete();
               } 
            }        
             if($user->long_answer_user) {
                foreach($user->long_answer_user as $lau) {
                     $lau->delete();
                } 
             }
             if($user->livesession_user) {
                foreach($user->livesession_user as $sau) {
                     $sau->delete();
                } 
             }
             if($user->assessment_user) {
                foreach($user->assessment_user as $au) {
                     $au->delete();
                } 
             }
             if($user->evaluation_user) {
                foreach($user->evaluation_user as $eu) {
                     $eu->delete();
                } 
             }
             if($user->rating_users) {
                foreach($user->rating_users as $ru) {
                     $ru->delete();
                } 
             }
            foreach($courseLearner as $cl) {
                $cl->delete();
            } 
        }
        if($user->discussion_message_users) { // there can be cases in which a user can participate in a discussion where everyone is allowed!
            foreach($user->discussion_message_users as $du) {
                 $du->delete();
            } 
         }
        $user->delete();       

        return response(['data' => 'All the taken courses, ratings & reviews and chat messages in the discussions are deleted!'], 200 );
    }
}
