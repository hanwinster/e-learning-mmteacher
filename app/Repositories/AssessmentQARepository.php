<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CoursePrivacy;
use App\Models\AssessmentQuestionAnswer;
use App\Models\AssessmentUser;
use App\User;
use Carbon\Carbon;
use DB;


class AssessmentQARepository
{
    protected $model;

    public function __construct(AssessmentQuestionAnswer $model)
    {
        $this->model = $model;
    }

    public function saveRecord($request, $id = null)
    {  //dd($request->all());exit;
        if (isset($id)) {
            $this->model = $this->find($id);
        } else {
            $this->model->user_id = auth()->user()->id;
        }
        $this->model->question = $request->question;
        $this->model->type = $request->type;
        $this->model->course_id = $request->course_id;        
        $this->model->order = isset($id) ?  $this->model->order + 1 : 1;
        if (isset($id)) {
            if ($request->type == 'true_false') {
                $this->model->answers = ["true","false","none"];
                $this->model->right_answers = $request->right_answers;
            } elseif ($request->type == 'rearrange') {
                $this->model->answers = $this->removeNulls($request->answers);           
                $final = array();
                $temp = array_values($this->model->answers); 
                foreach($temp as $data) {
                    array_push($final, strip_tags($data));
                }
                $this->model->right_answers = $final;
            }   elseif ($request->type == 'long_answer') {
                $this->model->answers = $this->model->right_answers = $request->right_answers;
                $this->model->passing_option = $request->passing_option;
            }   else { // multiple choice & matching
                $this->model->answers = $this->removeNulls($request->answers);
                $this->model->right_answers = $this->removeNulls($request->right_answers);
            }
        } else {
            $this->model->answers = [];
            $this->model->right_answers = [];
        }
        
        $this->model->save();    
    }

    protected function removeNulls($arr)
    {
        return array_filter($arr, fn($value) => !is_null($value) && $value !== '' && !is_null(strip_tags($value)) && strip_tags($value) !== '');
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getKeyId()
    {
        return $this->model->id;
    }

    /** 
     * Check if user can edit the resource
     *
     * @param App\Models\Resource $course
     * @return boolean
     */
    public static function canEdit($assessmentQA)
    {
        if (!$user = auth()->user()) {
            return false;
        }
        //$course = Course::findOrFail($assessmentQA->course_id);
        //dd($assessmentQA->assessment_user);exit;
        if (!count($assessmentQA->assessment_user)) {
            return true;
        }
        return false;
    }

    /**
     * Check if user can review the resource
     *
     * @param App\Models\Resource $course
     * @return boolean
     */
    public static function canReview($assessmentQA)
    {
        if (!$user = auth()->user()) {
            return false;
        }
        $course = Course::findOrFail($assessmentQA->course_id);
        // dd($user->id. ' # ' .$course->user_id);
        if ($user->isAdmin() || $user->isManager() || $user->id == $course->user_id){
            return true;
        }

        return false;
    }

    public function getByCourse($request, $course_id)
    {
        $posts = $this->model->where('course_id', $course_id)
                             ->get();
        return $posts;
    }

    public static function checkLongAnswerByCourseAndQuestionId($courseId, $qaId)
    {
        $longAns = AssessmentUser::where('assessment_question_answer_id', $qaId)
                                ->where('course_id', $courseId)
                                ->where('answers','!=',null)->first();
        return $longAns ? true : false;
    }

}
