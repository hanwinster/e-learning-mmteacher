<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CoursePrivacy;
use App\Models\Discussion;
use App\Models\DiscussionMessage;
//use App\User;
use Carbon\Carbon;
use DB;


class DiscussionMessageRepository
{
    protected $model;

    public function __construct(DiscussionMessage $model)
    {
        $this->model = $model;
    }

    public function saveRecord($request, $id = null)
    {
        // dd($request->all());
        if (isset($id)) {
            $this->model = $this->find($id);
        } 

        $this->model->discussion_id = $request->discussion_id;
        $this->model->user_id = $request->user_id;
        $this->model->message = $request->message;
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

    public static function getMessagesByDiscussionId($discussionId)
    {
        $discussions = DiscussionMessage::where('discussion_id',$discussionId)->get();
        return count($discussions) > 0 ? $discussions->toArray() : [];
    }
}
