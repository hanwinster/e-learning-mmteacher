<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CoursePrivacy;
use App\Models\MultipleChoiceAnswer;
use App\Models\TrueFalseAnswer;
use App\Models\BlankAnswer;
use App\Models\ShortAnswer;
use App\Models\LongAnswer;
use App\Models\MultipleAnswer;
use App\Models\RearrangeAnswer;
use App\Models\MatchingAnswer;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\Question;
use App\User;
use Carbon\Carbon;
use DB;


class QuestionRepository
{
    protected $model;

    public function __construct(Question $model)
    {
        $this->model = $model;
    }

    public function saveRecord($request, $id = null)
    {
        
        if (isset($id)) {
            $this->model = $this->find($id);
        } else {
            $this->model->user_id = auth()->user()->id;
        }
       // dd($request->all());exit;
        $this->model->title = $request->title;
        $this->model->description = $request->description;
        $this->model->quiz_id = $request->quiz_id;
        if ($request->quiz_type != Quiz::ASSIGNMENT) {
            if ($file = $request->file('attached_file')) {
                $this->model->addMediaFromRequest('attached_file')->toMediaCollection('question_attached_file');
            }
        }
        
        $this->model->save();
        $questionId = $this->model->id;
        if ($request->quiz_type == Quiz::MULTIPLE_CHOICE) {
            $this->saveMultipleAnswer($request);
        } else if($request->quiz_type == Quiz::TRUE_FALSE){
            $this->saveTrueFalseAnswer($request);
        } else if($request->quiz_type == Quiz::SHORT_QUESTION){
            $this->saveShortAnswer($request);
        } else if($request->quiz_type == Quiz::LONG_QUESTION){
            $this->saveLongAnswer($request);
        } else if($request->quiz_type == Quiz::BLANK){
            $this->saveBlankAnswer($request);
        } else if($request->quiz_type == Quiz::REARRANGE){
            $this->saveRearrangeAnswer($request);
        } else if($request->quiz_type == Quiz::MATCHING){
            $this->saveMatching($request);
        } else if($request->quiz_type == Quiz::ASSIGNMENT){
            $this->saveAssignment($request, $questionId);
        }   
    }

    private function saveAssignment($request, $questionId)
    {
        // if (isset($id)) {
        //     $this->model = $this->find($id);
        // } else {
        //     $this->model->user_id = auth()->user()->id;
        // }

        //$this->model->title = $request->title;
        //$this->model->course_id = $request->course_id;
        //$this->model->description = $request->description;
        $this->model->question_id = $request->id;
        if ($this->model->assignment === null) {
            $assign = new Assignment();
            $assign->question_id =$questionId;
            $this->model->assignment()->save($assign);
           // $this->model->assignment()->save();  
        } 
        // else {
        //     $this->model->assignment->question_id = $questionId;
        //     $this->model->assignment()->update($this->model->assignment); 
        // }
        if ($file = $request->file('attached_file')) {
            $this->model->addMediaFromRequest('attached_file')->toMediaCollection('assignment_attached_file');
        }
    }

    private function saveMultipleAnswer($request)
    { 
        $multiple_answers = [];
        $alphabets = [ 'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        foreach($alphabets as $idx => $val) {
            if(isset($request['answer_'.$val]) && ($request['answer_'.$val])) {
                $multiple_answers[] = new MultipleAnswer([
                    'name' => $val,
                    'answer' => $request['answer_'.$val],
                    'is_right_answer' => in_array($val, $request->right_answer)
                ]);
            }
        }
       
       // dd($multiple_answers);exit;  
        if (count($multiple_answers) !== 0 ) {
                if($this->model->multiple_answers != NULL)
                    $this->model->multiple_answers->each->delete();
                $this->model->multiple_answers()->saveMany($multiple_answers);
        }
    }

    private function saveTrueFalseAnswer($request)
    {
        $answers = [
            'answer' => $request->true_or_false,
        ];

        if ($this->model->true_false_answer === null) {
                $answer = new TrueFalseAnswer($answers);
                $this->model->true_false_answer()->save($answer);
        } else {
            $this->model->true_false_answer->update($answers);
        }
    }

    private function saveShortAnswer($request)
    {
        $answers = [
            'answer' => $request->short_answer,
        ];

        if ($this->model->short_answer === null) {
                $answer = new ShortAnswer($answers);
                $this->model->short_answer()->save($answer);
        } else {
            $this->model->short_answer->update($answers);
        }
    }

    private function saveLongAnswer($request)
    {
        $answers = [
            'answer' => $request->long_answer,
            'passing_option' => $request->passing_option
        ];

        if ($this->model->long_answer === null) {
                $answer = new LongAnswer($answers);
                $this->model->long_answer()->save($answer);
        } else {
            $this->model->long_answer->update($answers);
        }
    }

    public function saveBlankAnswer($request)
    {
       
        $senPos = 0; $blankPos = 0; $count = 0;
        $sentenceArr = []; $blankArr = [];
        foreach($request->all() as $key => $val) {
                if($key == "sentence") {
                    $senPos = $count;
                    $sentenceArr = $val;
                }
                if($key == "blank") {
                    $blankPos = $count;
                    $blankArr = $val;
                }
                $count++;
        }
        
            $final = []; $counter = 0;
            $limit1 = count($sentenceArr) > count($blankArr) ? count($sentenceArr) : count($blankArr);
            for($i = 0; $i < $limit1; $i++) {
                if($senPos < $blankPos) {
                    if(isset($sentenceArr[$i])) {
                        array_push($final, [ 'sentence_'.$counter => $sentenceArr[$i] ]);
                        $counter++;
                    }
                    if(isset($blankArr[$i])) {
                        array_push($final, [ 'blank_'.$counter => $blankArr[$i] ]);
                        $counter++;
                    }
                } else {
                    if(isset($blankArr[$i])) {
                        array_push($final, [ 'blank_'.$counter => $blankArr[$i] ]);
                        $counter++;
                    }
                    if(isset($sentenceArr[$i])) {
                        array_push($final, [ 'sentence_'.$counter => $sentenceArr[$i] ]);
                        $counter++;
                    }
                }
            }
        //dd($final);exit;
        $answers= [
            'answer' => $request->blank_answer,
            'paragraph' => $final,
            'optional_keywords' => $request->optional_keywords
        ];
        if ($this->model->blank_answer === null) {
                $answer = new BlankAnswer($answers);
                $this->model->blank_answer()->save($answer);
        } else {
            $this->model->blank_answer->update($answers);
        }
    }

    private function saveRearrangeAnswer($request)
    {
        $answers = [
            'answer' => [ 
                        $request->answer_one,
                        $request->answer_two,
                        $request->answer_three
                    ]
        ];

        if($request->answer_four){
            $answers['answer'][] = $request->answer_four;
            if($request->answer_five){
                $answers['answer'][] = $request->answer_five;
            }    
        }

        if ($this->model->rearrange_answer === null) {
                $answer = new RearrangeAnswer($answers);
                $this->model->rearrange_answer()->save($answer);
        } else {
            $this->model->rearrange_answer->update($answers);
        }
    }

    private function saveMatching($request)
    {
        $answers = [
            'answer' => [
                [ 'name_first' => 'A', 'first' => $request->matching_A, 'name_second' => '1', 'second' => $request->matching_One ],
                [ 'name_first' => 'B', 'first' => $request->matching_B, 'name_second' => '2', 'second' => $request->matching_Two ],
                [ 'name_first' => 'C', 'first' => $request->matching_C, 'name_second' => '3', 'second' => $request->matching_Three ]
            ]
        ];

        if($request->matching_D && $request->matching_Four){
            $answers['answer'][] = [ 'name_first' => 'D', 'first' => $request->matching_D, 'name_second' => '3', 'second' => $request->matching_Four ];
            
            if($request->matching_E && $request->matching_Five){
                $answers['answer'][] = [ 'name_first' => 'E', 'first' => $request->matching_E, 'name_second' => '4', 'second' => $request->matching_Five ];
            }
        }

        if ($this->model->matching_answer === null) {
                $answer = new MatchingAnswer($answers);
                $this->model->matching_answer()->save($answer);
        } else {
            $this->model->matching_answer->update($answers);
        }
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getKeyId()
    {
        return $this->model->id;
    }

    public function getByQuiz($request, $quiz_id)
    {
        $posts = $this->model->where('quiz_id', $quiz_id)
                                ->sortable(['updated_at' => 'desc'])
                                ->get();
        //                         ->paginate($request->input('limit'));
        // $posts->appends($request->all());
        return $posts;
    }


}
