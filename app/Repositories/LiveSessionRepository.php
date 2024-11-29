<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CoursePrivacy;
use App\Models\LiveSession;
use App\User;
use Carbon\Carbon;
use DB;


class LiveSessionRepository
{
    protected $model;

    public function __construct(LiveSession $model)
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

        $this->model->topic = isset($validatedArray['topic'][0]) ? $validatedArray['topic'][0] : $validatedArray['topic'];
        $this->model->agenda = isset($validatedArray['agenda'][0]) ? $validatedArray['agenda'][0] : $validatedArray['agenda'];
        $this->model->meeting_id = $validatedArray['id']; // zoom meeting id
        $this->model->start_time = isset($validatedArray['start_time'][0]) ? $validatedArray['start_time'][0] : $validatedArray['start_time'];
        $this->model->start_date = $validatedArray['start_date'];
        $this->model->host_video = 1;
        $this->model->participant_video = 1;
        $this->model->duration = isset($validatedArray['duration'][0]) ? $validatedArray['duration'][0] : $validatedArray['duration'];
        $this->model->passcode = $validatedArray['password'];
        $this->model->status = $validatedArray['status'];
        $this->model->description = null;
        $this->model->start_url = $validatedArray['start_url'];
        $this->model->join_url = $validatedArray['join_url'];
        $this->model->lecture_id = $validatedArray['lecture_id'];
        $this->model->course_id = $validatedArray['course_id'];
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

    public static function stillCanRegister($startDate, $startTime)
    {
        $now = Carbon::now()->timestamp;
        $tempDate = explode('/', $startDate);
        $tempTime = explode(':', $startTime);
        $meetingTs = new Carbon($tempDate[2].'-'.$tempDate[1].'-'.$tempDate[0].' '. $tempTime[0].':'.$tempTime[1].':00');
        //echo $now." -- ".$meetingTs->timestamp."   =   ".( $meetingTs->timestamp - $now );exit;
        if( ( $meetingTs->timestamp - $now ) >= 300 ) { // more then or equal to 1 hour
            return true;
        }
        return false;
    }

}
