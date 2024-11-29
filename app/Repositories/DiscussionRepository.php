<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CoursePrivacy;
use App\Models\Discussion;
//use App\User;
use Carbon\Carbon;
use DB;


class DiscussionRepository
{
    protected $model;

    public function __construct(Discussion $model)
    {
        $this->model = $model;
    }

    public function saveRecord($request, $id = null)
    {
        //dd($request->allow_takers);exit;
        if (isset($id)) {
            $this->model = $this->find($id);
        } 

        $this->model->title = $request->title;
        $this->model->description = $request->description;
        $this->model->course_id = $request->course_id;
        $this->model->allow_takers = $request->allow_takers == null ? false : true;
        $this->model->allow_learners = $request->allow_learners == null ? false: true;
        $this->model->save();  

    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getKeyId()
    {
        return $this->model->id;
    }

    public function getByCourse($request, $course_id)
    {
        $posts = $this->model->where('course_id', $course_id)
                                //->orderBy('lecture_id')
                                ->latest()
                                ->get();
        //                         ->paginate($request->input('limit'));
        // $posts->appends($request->all());
        return $posts;
    }

    /**
     * Check if user can edit the resource
     *
     * @param App\Models\Discussion $discussion
     * @return boolean
     */
    public static function canEdit($discussion)
    {
        if (!$user = auth()->user()) {
            return false;
        }
        $course = Course::findOrFail($discussion->course_id);
        if (count($discussion->assignment_user) == 0){
            return true;
        }

        return false;
    }

    /**
     * Check if user can review the resource
     *
     * @param App\Models\Discussion $discussion
     * @return boolean
     */
    public static function canReview($discussion)
    {
        if (!$user = auth()->user()) {
            return false;
        }
        $course = Course::findOrFail($discussion->course_id);
        // dd($user->id. ' # ' .$course->user_id);
        if ($user->isAdmin() || $user->isManager() || $user->id == $course->user_id){
            return true;
        }

        return false;
    }
}
