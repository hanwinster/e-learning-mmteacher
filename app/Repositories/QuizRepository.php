<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CoursePrivacy;
use App\Models\Quiz;
use App\Models\MultipleChoiceAnswer;
use App\Models\TrueFalseAnswer;
use App\Models\BlankAnswer;
use App\Models\MultipleAnswer;
use App\Models\RearrangeAnswer;
use App\Models\MatchingAnswer;
use App\User;
use Carbon\Carbon;
use DB;


class QuizRepository
{
    protected $model;

    public function __construct(Quiz $model)
    {
        $this->model = $model;
    }

    public function saveRecord($request, $id = null)
    {
        // dd($request->all());
        if (isset($id)) {
            $this->model = $this->find($id);
        } else {
            $this->model->user_id = auth()->user()->id;
        }

        $this->model->title = $request->title;
        $this->model->course_id = $request->course_id;
        $this->model->lecture_id = $request->lecture_id;
        $this->model->type = $request->type;
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
                                // ->sortable(['updated_at' => 'desc'])
                                ->orderBy('lecture_id')
                                ->get();
        //                         ->paginate($request->input('limit'));
        // $posts->appends($request->all());
        return $posts;
    }

    public function getForOnlyCourse($request, $course_id)
    {
        $posts = $this->model->where('course_id', $course_id)
                                ->whereNull('lecture_id')
                                ->get();
        //                         ->paginate($request->input('limit'));
        // $posts->appends($request->all());
        return $posts;
    }


}
