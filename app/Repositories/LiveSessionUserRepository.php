<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CoursePrivacy;
use App\Models\LiveSessionUser;
use App\User;
use Carbon\Carbon;
use DB;


class LiveSessionUserRepository
{
    protected $model;

    public function __construct(LiveSessionUser $model)
    {
        $this->model = $model;
    }

    public function saveRecord(Array $validatedArray, $id = null)
    {
        // dd($request->all());
        if (isset($id)) {
            $this->model = $this->find($id);
        } else {
            $this->model->user_id = auth()->user()->id;
        }
        $this->model->session_id =  $validatedArray['session_id'];
        $this->model->status = isset($validatedArray['status']) ? $validatedArray['status'] : null;
        $this->model->remark = isset($validatedArray['remark']) ? $validatedArray['remark'] : null;
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
        $posts = $this->model->where([
                                ['course_id', $course_id],
                                ['lecture_id','!=', NULL] ])
                                ->orderBy('lecture_id')
                                ->latest()
                                ->get();
        return $posts;
    }

    /**
     * Check if user can edit the resource
     *
     * @param App\Models\Resource $course
     * @return boolean
     */
    public static function canEdit($liveSession)
    {
        if (!$user = auth()->user()) {
            return false;
        }
        $course = Course::findOrFail($liveSession->course_id);
        //if (count($liveSession->assignment_user) == 0){
            return true;
        //}

        return false;
    }

    /**
     * Check if user can review the resource
     *
     * @param App\Models\Resource $course
     * @return boolean
     */
    public static function canReview($liveSession)
    {
        if (!$user = auth()->user()) {
            return false;
        }
        $course = Course::findOrFail($liveSession->course_id);
        // dd($user->id. ' # ' .$course->user_id);
        if ($user->isAdmin() || $user->isManager() || $user->id == $course->user_id){
            return true;
        }

        return false;
    }

    public function getForOnlyCourse($request, $course_id)
    {
        $posts = $this->model->where('course_id', $course_id)
                                ->whereNull('lecture_id')
                                ->get();
        return $posts;
    }

    
}
