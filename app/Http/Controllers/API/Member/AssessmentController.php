<?php

namespace App\Http\Controllers\API\Member;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\AssessmentQuestionAnswer;
use App\Models\AssessmentUser;
use App\Models\Course;
use App\Repositories\CourseRepository;
use App\Repositories\CourseLearnerRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SubmitAssessmentLongAnswer;

class AssessmentController extends Controller
{
    protected $repository;
    protected $clRepo;

    public function __construct(CourseRepository $repository, CourseLearnerRepository $clRepository)
    {   
        $this->clRepo = $clRepository;
        $this->repository = $repository;
    }
    
    public function getAssessments(Request $request, $courseId)
    {
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        $course = Course::where('id', $courseId)->first();
        if(!$course) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
        try {
            $assessQs = AssessmentQuestionAnswer::where('course_id',$courseId)->get(); 
            foreach($assessQs as $idx => $val) {
                if($val->type == "multiple_choice") {
                    $temp = changeMcAnswerFormat($val->answers);
                    //$val->answers =  changeMcAnswerFormat($val->answers);
                    $val['multiple_que_answer'] = $temp;
                }
                if($val->type == "matching") {
                    $temp2 = [];
                    foreach($val->answers as $va) {
                        array_push($temp2, strip_tags($va));
                    }
                    sort($temp2);
                    $val['sorted_answers'] =  $temp2;
                }
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Assessment is not found'], 404);
        }
        if(!$assessQs) {
            return response()->json(['code' => 404, 'message' => 'Assessment is not found'], 404);
        }
        
        $data = [
            'assessment_questions' => $assessQs,        
        ];      
        return response()->json(['data' =>  $data], 200);
    }

    public function getAssessmentAndSavedDataById(Request $request, $courseId, $assessId)
    {
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        $course = Course::where('id', $courseId)->first();
        if(!$course) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
        try {
            $assessQ = AssessmentQuestionAnswer::where('id',$assessId)->first(); 
        } catch (ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Assessment is not found'], 404);
        }
        if(!$assessQ) {
            return response()->json(['code' => 404, 'message' => 'Assessment is not found'], 404);
        }
        
        $post = AssessmentUser::where('course_id', $course->id)
                                ->where('user_id',auth()->user()->id)
                                ->where('assessment_question_answer_id', $assessId)->get()->first();
        $post = isset($post->id) ? $post : null;
        if($post) { 
            $post->answers = $post->answers ? convertObjectToArray($post->answers) : [];
        }
        $assessmentQs = $course->assessmentQuestionAnswers; 
        $totalQs = count($assessmentQs);  
        $allAnswers = AssessmentUser::where('course_id', $course->id)
                                    ->where('user_id',auth()->user()->id)->get();
        $totalAs = count($allAnswers);
        $isSubmitted = isset($post->id) && $post->status == 2 ? true : false;
        $isLastQuestion = $assessmentQs[$totalQs-1]->id == $assessId ? true : false;
        $isReadyToSubmit = $totalAs == $totalQs ? true : false;
        $gotAcceptableScore = isset($post->id) && $post->overall_score >= $course->acceptable_score_for_assessment ? true : false; 
        $route = route('courses.assessment', [$assessQ]);
        $this->clRepo->updatelastVisited($course->id, auth()->user()->id, $route); 
        //$this->clRepo->clRepo->performCompletionLogicLogic($course->id, auth()->user()->id, 'assessment_'.$assessId, true);
        $courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
        $courseLearner['completed'] = $this->repository->convertCompletedArrayToAPISupportedFormat($courseLearner['completed'], $course);
        $data = [
            'is_submitted' => $isSubmitted,
            'is_last_question' => $isLastQuestion,
            'is_ready_to_submit' => $isReadyToSubmit,
            'got_acceptable_score' => $gotAcceptableScore,
            'saved_answer_for_assessment' => $post, 
            'question_detail' => $assessQ,        
            'course_learner' => $courseLearner
        ];      
        return response()->json(['data' =>  $data], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveAssessment(Request $request, $courseId, $assessQid)
    {
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        $course = Course::where('id', $courseId)->first();
        if(!$course) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'answers' => 'required|array'
		]);
		if ($validator->fails()) {
			return response(['errors'=> $validator->errors()->all()], 422);
		}
        $answers = $request->input('answers');
        $keys = array_keys($answers);
        $values = array_values($answers);
        $filterValues = array_filter($values, static function($var){return $var !== null;} );
      
        if(count($filterValues) < count($keys)) { 
            return response()->json(['code' => 400, 'message' => 'Answer should not be null'], 400);
        }        
        $hasExisting = AssessmentUser::where('course_id', $courseId)
                        ->where('assessment_question_answer_id',$assessQid)
                        ->where('user_id', auth()->user()->id)
                        ->first();
        if($hasExisting) { 
            return response()->json(['code' => 409, 'message' => 'Existing record in database!'], 409);
        }
        $assessmentQ = AssessmentQuestionAnswer::where('id',$assessQid)->first();
        $type = $assessmentQ->type;
        
        $rightAns = array_values($assessmentQ->right_answers);
        $score = calculateAssessmentScore($type, $answers, $rightAns);
        $data = AssessmentUser::query()->create([
                'course_id' => $courseId,
                'user_id' => auth()->user()->id,
                'assessment_question_answer_id' => $assessQid,
                'answers' => $answers,
                'score' => $score,
                'attempts' => 0,
                'status' =>  1
        ]);
        //dd($rec);exit;
        if ($type == 'long_answer') {
            if($assessmentQ->passing_option === 'after_providing_answer') {
                $this->clRepo->performCompletionLogic($courseId, auth()->user()->id, 'assessment_'.$assessQid, true);
            } 
            Notification::send( User::query()->where('id', $course->user_id)->first(), new submitAssessmentLongAnswer($data, $courseId) );
        } else {
            $this->clRepo->performCompletionLogic($courseId, auth()->user()->id, 'assessment_'.$assessQid, true); 
        }
        return response()->json(['data' => 'Saved successfully' ]);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitAssessments(Request $request, $courseId) //, $assessQid)
    {
        $course = Course::where('id', $courseId)->first();
        if(!$course) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
        $assessmentA = AssessmentUser::where('course_id',$courseId)->where('user_id', auth()->user()->id)->first(); 
        $attempts = $assessmentA->attempts > 0 ? $assessmentA->attempts + 1 : 1; 
        $allAnswers = AssessmentUser::where('course_id', $courseId)
                                    ->where('user_id',auth()->user()->id)->get();
        $allQuestions = AssessmentQuestionAnswer::where('course_id', $courseId)->get();
        // if( $allAnswers && $allQuestions && 
        //     count($allAnswers) != count($allQuestions)) {
        //         return response()->json(['code' => 409, 'message' => 'All answers must be saved before submitting!'], 409);
        // }
        //modify the logic as we allow adding a new assessment after taking a course
        $courseLearner = $this->clRepo->getCourseLearnerData($courseId, auth()->user()->id);
        foreach($courseLearner->completed as $idx => $data) {
            $keys = array_keys($data);
            if(strpos($keys[0], 'assessment_') !== false) { // if  assessment
                $values = array_values($data);
                if($values[0] == 0) return response()->json(['code' => 409, 'message' => 'All answers must be saved before submitting!'], 409);
            }
        }    
        $sum =0; 
        for($i =0; $i < count($allAnswers); $i++) {
            $sum += $allAnswers[$i]->score;
        }
        $overallScore = ($sum / count($allAnswers) ) * 100; //echo $overallScore;exit;
       
        AssessmentUser::query() // need to update other records for the course as well
                            ->where('course_id', $courseId)
                            ->where('user_id', auth()->user()->id)
                            ->update([
                                'attempts' => $attempts,
                                'status' =>  2,
                                'overall_score' => $overallScore
        ]);
        if($overallScore >= $course->acceptable_score_for_assessment ) {
                $this->clRepo->setPercentageAfterSubmittingAssessment($courseId, auth()->user()->id, 100);
                return response()->json(['data' => [
                    'message' => 'Submitted successfully and you got', 
                    'score' => $overallScore,
                    'acceptable_score' => $course->acceptable_score_for_assessment,
                    'proceed_message' => 'Congratulations! You may proceed to evaluate the course and then generate certificate.' ]] );
        } else {
                $this->clRepo->setPercentageAfterSubmittingAssessment($courseId, auth()->user()->id, 99);
                return response()->json(['data' => [
                    'message' => 'Submitted successfully. Unfortunately, your score is lower than the accepatable score - ', 
                    'score' => $overallScore,
                    'acceptable_score' => $course->acceptable_score_for_assessment, 
                    'proceed_message' => '. Please try again!' ]] );              
         }                    
        
    }

    /**
     * Update
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAssessment(Request $request, $courseId, $assessQid)
    {
        $course = Course::where('id', $courseId)->first();
        if(!$course) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        } 
        //dd($request->all());exit; 
        $validator = Validator::make($request->all(), [
            'answers' => 'required|array',
            'id' => 'required|int'
		]);
		if ($validator->fails()) {
			return response(['errors'=> $validator->errors()->all()], 422);
		}
        $answers = $request->input('answers');
        $keys = array_keys($answers);
        $values = array_values($answers);
        $filterValues = array_filter($values, static function($var){return $var !== null;} );
      
        if(count($filterValues) < count($keys)) { 
            return response()->json(['code' => 400, 'message' => 'Answer should not be null'], 400);
        } 
        $savedAnswersId = $request->input('id');
      //  echo $savedAnswersId." - ".$assessQid." - ".$courseId." -- ".auth()->user()->id;exit;
        $savedAnswers = AssessmentUser::where('id',$savedAnswersId)
                        ->where('assessment_question_answer_id',$assessQid)
                        ->where('course_id', $courseId)
                        ->where('user_id', auth()->user()->id)->first();
        if(!$savedAnswers) {
            return response()->json(['code' => 404, 'message' => 'Saved record is not found'], 404);
        }
        $assessmentQ = AssessmentQuestionAnswer::where('id',$assessQid)->first(); 
        $type = $assessmentQ->type;
        if ($type == 'long_answer') { 
            if($assessmentQ->passing_option === 'after_providing_answer') { 
                $this->clRepo->performCompletionLogic($courseId, auth()->user()->id, 'assessment_'.$assessQid, true);
            } 
            Notification::send( User::query()->where('id', $course->user_id)->first(), new submitAssessmentLongAnswer($savedAnswers, $courseId) );
        } else {
            $isCompleted = $this->clRepo->performCompletionLogic($courseId, auth()->user()->id, 'assessment_'.$assessQid, true);
        }
        $rightAns = array_values($assessmentQ->right_answers);
        $passOption = $savedAnswers->pass_option;
        if($type == 'long_answer') {
            if($assessmentQ->passing_option === 'after_providing_answer') {
                $score = 1;
                $passOption = 'pass';
            } else {
                $score =  $assessmentUser->score ?  $assessmentUser->score : 0;
                $passOption = $assessmentUser->pass_option == 'pass' ? 'pass' : 'submitted';
            }
            
        } else {
            $score = $rightAns == $answers ? 1 : 0; 
        }
        $isSaved = AssessmentUser::query()
                        ->where('id',$savedAnswersId)
                        ->where('assessment_question_answer_id',$assessQid)
                        ->where('user_id', auth()->user()->id)
                        ->where('course_id', $courseId)
                        ->update([
                        'answers' => $answers,
                        'score' => $score
                    ]);
        $courseLearner = $this->clRepo->getCourseLearnerData($courseId, auth()->user()->id);
        return response()->json(['data' =>  $courseLearner ]); 
        
    }

    protected function performSubmitLogic($id, $courseId,$course,$ans, $score, $assessmentQ)
    {
            $assessmentA = AssessmentUser::findOrFail($id); 
            $attempts = $assessmentA->attempts > 0 ? $assessmentA->attempts + 1 : 1; 
            $allAnswers = AssessmentUser::where('course_id', $courseId)
                                    ->where('user_id',auth()->user()->id)->get();
            $sum =0; 
            for($i =0; $i < count($allAnswers); $i++) {
                $sum += $allAnswers[$i]->score;
            }
            $overallScore = ($sum / count($allAnswers) ) * 100; //echo $overallScore;exit;
            AssessmentUser::query()
                            ->where('id', $id)
                            ->update([
                                'answers' => $ans,
                                'score' => $score,
                                'attempts' => $attempts,
                                'status' =>  2,
                                'overall_score' => $overallScore
            ]);
            AssessmentUser::query() // need to update other records for the course as well
                            ->where('course_id', $courseId)
                            ->where('user_id', auth()->user()->id)
                            ->update([
                                'attempts' => $attempts,
                                'status' =>  2,
                                'overall_score' => $overallScore
            ]);
            if($overallScore >= $course->acceptable_score_for_assessment ) {
                $this->clRepo->setPercentageAfterSubmittingAssessment($courseId, auth()->user()->id, 100);
                return response()->json(['data' => [
                    'message' => 'Submitted successfully and you got', 
                    'score' => $overallScore,
                    'acceptable_score' => $course->acceptable_score_for_assessment,
                    'proceed_message' => 'Congratulations! You may proceed to evaluate the course and then generate certificate.' ]] );
            } else {
                $this->clRepo->setPercentageAfterSubmittingAssessment($courseId, auth()->user()->id, 99);
                return response()->json(['data' => [
                    'message' => 'Submitted successfully. Unfortunately, your score is lower than the accepatable score - ', 
                    'score' => $overallScore,
                    'acceptable_score' => $course->acceptable_score_for_assessment, 
                    'proceed_message' => '. Please try again!' ]] );              
            }
    }
}