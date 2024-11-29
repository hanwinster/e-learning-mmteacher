<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\AssessmentQuestionAnswer;
use App\Models\AssessmentUser;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SubmitAssessmentLongAnswer;
use Spatie\MediaLibrary\Models\Media;
use App\Repositories\CourseRepository;
use App\Repositories\CourseLearnerRepository;
use Jenssegers\Agent\Agent;

class AssessmentController extends Controller
{
  
    private $repository;
    private $clRepo;

    public function __construct(CourseRepository $repository, CourseLearnerRepository $clRepo)
    {
        $this->repository = $repository;
        $this->clRepo = $clRepo;
    }

    public function show(AssessmentQuestionAnswer $assessment)
    {
        $course = Course::findOrFail($assessment->course_id);
        $lectures = $course->lectures()->orderBy('id')->get();
        $lecturesMedias = Media::all()->where('model_type', Lecture::class);
        $userLectures = auth()->user()->learningLectures;
        $courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
        $completed = $courseLearner->completed;
        if (!$this->clRepo->isReadyToAssessFromLastSection($completed)) { 
            return redirect()->back()->with('error', trans("You have to finish all the coursework to proceed to the assessment section"));
        }
        $status = $courseLearner->status;
        $percentage = $courseLearner->percentage;
        $assessmentQs = $course->assessmentQuestionAnswers; 
        $totalQs = $this->clRepo->getTotalAssessmentsFromUserArr($completed); //count($assessmentQs);      
        $post = AssessmentUser::where('course_id', $course->id)
                                ->where('user_id',auth()->user()->id)
                                ->where('assessment_question_answer_id', $assessment->id)
                                ->get()->first();
        $post = isset($post->id) ? $post : null;
        $allAnswers = AssessmentUser::where('course_id', $course->id)
                                    ->where('user_id',auth()->user()->id)->get();
        $totalAs = count($allAnswers);
        $isSubmitted = isset($post->id) && $post->status == 2 ? true : false; 
        $isLastQuestion = $assessmentQs[$totalQs-1]->id == $assessment->id ? true : false;
        $isReadyToSubmit = $totalAs >= ($totalQs - 1) ? true : false;
       // echo $totalAs." -- ".$totalQs;exit;
        $gotAcceptableScore = isset($post->id) && $post->overall_score >= $course->acceptable_score_for_assessment ? true : false; 
        //echo $isLastQuestion." -- ".$isReadyToSubmit." ".$totalAs." -- ".$totalQs;exit;
        $previousSection = $this->repository->getRouteFromValue($this->repository->getPrevSection('assessment_'.$assessment->id, $completed));
        //$nextLink = $this->repository->getRouteFromValue($this->repository->getNextSection('assessment_'.$assessment->id, $completed));
        $nextSection =  $this->repository->getRouteFromValue($this->repository->getNextSection('assessment_'.$assessment->id, $completed));
        $route = route('courses.assessment', [$assessment]);
        $this->clRepo->updatelastVisited($course->id, auth()->user()->id, $route);   
        $allQandAns = AssessmentQuestionAnswer::where('course_id',$course->id)->get();
        $assessmentIdsFromUser =  $this->clRepo->getAssessmentIdsFromUserArr($completed);
        $allQandA = [];
        foreach($allQandAns as $aq) {
            if(in_array($aq->id, $assessmentIdsFromUser)) {
                array_push($allQandA, $aq);
            }
        }
        $agent = new Agent();    
        return view('frontend.courses.assessment', compact('course', 'lectures','lecturesMedias','assessment','post','agent',
            'previousSection', 'nextSection', 'allQandA',
             'userLectures','completed','status','percentage', 'isSubmitted', 'isLastQuestion', 'isReadyToSubmit', 'gotAcceptableScore'));
               
    }

    protected function validator(array $data, string $type)
    {  
        $validator =  Validator::make($data, [
            'answers' => 'required|array'
        ]); 
        $redirectLink = $data['submit_assess'] !== "2" ? $data['submit_assess'] : CourseRepository::getRouteFromValue('assessment_'.$data['assessmentQA_id']);
        
        if ($validator->fails()) {//echo "validator fails";exit;
            return redirect($redirectLink)
                    ->with('error', $validator->errors()->all());
        }  
        if($type === 'long_answer') { //dd($data);exit; 
            if($data['answers'][0] === null ) { // || str_word_count($data['answers'][0]) < 100) {
                return redirect($redirectLink)
                    ->with('error', trans('Answer should not be empty!'));
            } 
        }
        return null;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\Illuminate\Http\Request $request)
    {   //dd($request->all());exit;
        $type = $request->input('assessment_type');
        $validated = $this->validator($request->all(), $type);
        if($validated !== null) {
            return $validated;
        }
        $assessmentQaId = $request->input('assessmentQA_id');
        $assessmentQ = AssessmentQuestionAnswer::findOrFail($assessmentQaId);
        $courseId = $request->input('course_id');
        $course = Course::findOrFail($courseId);
        $isFinalSubmit = $request->input('submit_assess') == 2 ? true : false; // possible for the last record!!
        $redirectLink = $request->input('submit_assess');
       
        $rightAns = array_values($assessmentQ->right_answers);
        $ans = array_values($request->input('answers'));
        if($type == 'long_answer') {
            if($assessmentQ->passing_option === 'after_providing_answer') {
                $score = 1;
            } else {
                $score = 0;
            }
        } else {
            $score = $this->calculateScore($type, $ans, $rightAns); //$rightAns == $ans ? 1 : 0; 
        }
        if($isFinalSubmit) {
           // dd('correct'); exit;
            //save first 
            $data = AssessmentUser::query()->create([
                'assessment_question_answer_id' => $assessmentQaId,
                'course_id' => $courseId,
                'user_id' => auth()->user()->id,
                'answers' => $request->input('answers'),
                'score' => $score,
                'attempts' => 0,
                'status' =>  1,
                'pass_option' => $type === 'long_answer' ? 'submitted' : 'pass'
            ]);
            //dd($data->id);exit;
            if ($type == 'long_answer') {
                if($assessmentQ->passing_option === 'after_providing_answer') {
                    $this->performCompletion($courseId, auth()->user()->id, 'assessment_'.$assessmentQaId);
                } 
                Notification::send( User::query()->where('id', $course->user_id)->first(), new submitAssessmentLongAnswer($data, $courseId) );
            } else {
                $this->performCompletion($courseId, auth()->user()->id, 'assessment_'.$assessmentQaId);
            }               
            return $this->performSubmitLogic($data->id, $courseId,$course,$ans,$score,$assessmentQ);
        } else {
            // just save
            $data = AssessmentUser::query()->create([
                    'assessment_question_answer_id' => $assessmentQaId,
                    'course_id' => $courseId,
                    'user_id' => auth()->user()->id,
                    'answers' => $request->input('answers'),
                    'score' => $score,
                    'attempts' => 0,
                    'status' =>  1,
                    'pass_option' => $type === 'long_answer' ? 'submitted' : 'pass'
            ]);
            //just update the completion
            if ($type == 'long_answer') {
                if($assessmentQ->passing_option === 'after_providing_answer') {
                    $this->performCompletion($courseId, auth()->user()->id, 'assessment_'.$assessmentQaId);
                } 
                 Notification::send( User::query()->where('id', $course->user_id)->first(), new submitAssessmentLongAnswer($data, $courseId) );
            } else {
                $this->performCompletion($courseId, auth()->user()->id, 'assessment_'.$assessmentQaId);
            }
            return redirect($redirectLink)
                ->with('success', trans('Saved successfully'));
            //return $this->returnToView($assessmentQ, 'success', 'Saved successfully'); 
        }     
    }

    protected function performSubmitLogic($id, $courseId,$course,$ans, $score, $assessmentQ)
    {  
            $assessmentA = AssessmentUser::findOrFail($id); 
            $attempts = $assessmentA->attempts > 0 ? $assessmentA->attempts + 1 : 1; 
            $allAnswers = AssessmentUser::where('course_id', $courseId)
                                    ->where('user_id',auth()->user()->id)->get();
            $hasPendingActionByCourseOwner = false;
            foreach($allAnswers as $aa) {
                if($aa->pass_option == 'submitted' || $aa->pass_option == 'retake') {
                    $hasPendingActionByCourseOwner = true;
                }
            }
            $sum =0; 
            for($i =0; $i < count($allAnswers); $i++) {
                $sum += $allAnswers[$i]->score;
            }
            $overallScore = ($sum / count($allAnswers) ) * 100; //echo $overallScore;exit;
           
            // AssessmentUser::query()
            //                 ->where('id', $id)
            //                 ->update([
            //                     'answers' => $ans,
            //                     'score' => $score,
            //                     'attempts' => $attempts,
            //                     'status' =>  2,
            //                     'overall_score' => $overallScore
            // ]);
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
                return $this->returnToView($assessmentQ, 'success', trans('Submitted successfully and you got ').$overallScore.
                    trans('. Congratulations! You may proceed to evaluate the course and then generate certificate.'));
            } else {  
                $this->clRepo->setPercentageAfterSubmittingAssessment($courseId, auth()->user()->id, 99);
                return $this->returnToView($assessmentQ, 'error', trans('Submitted successfully. Unfortunately, your score is lower than the accepatable score - ')
                                .$course->acceptable_score_for_assessment.trans('. Please try again!'));
            }
    }
    protected function calculateScore($type, $ans, $rightAns)
    {
        $score = 0;
        switch($type) {
            case 'multiple_choice': $score = $rightAns == $ans ? 1 : 0; break;
            case 'rearrange': $score = $rightAns == $ans ? 1 : 0; break;
            case 'matching': $score = $rightAns == $ans ? 1 : 0; break;
            default: $score = $rightAns == $ans ? 1 : 0; break;
        }
        return $score;
    }

    protected function performCompletion($courseId, $userId, $findValue)
    {
        $this->clRepo->performCompletionLogic($courseId, $userId, $findValue, true);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(\Illuminate\Http\Request $request, $id)
    {  
        $type = $request->input('assessment_type');
        //dd($request->all());exit;
        if($type !== 'long_answer') {
            $validated = $this->validator($request->all(), $type);
            if($validated !== null) {
                return $validated;
            }
        }
        
        $assessmentQaId = $request->input('assessmentQA_id');
        $assessmentQ = AssessmentQuestionAnswer::findOrFail($assessmentQaId); 
        $courseId = $request->input('course_id');
        $course = Course::findOrFail($courseId);
        $rightAns = array_values($assessmentQ->right_answers); 
        $ans = array_values($request->input('answers')); 
        $assessmentUser = AssessmentUser::findOrFail($id); 
        //dd($ans);exit;
        $passOption = $assessmentUser->pass_option;
        if($type == 'long_answer') {
            if($assessmentQ->passing_option === 'after_providing_answer') {
                $score = 1;
                $passOption = 'pass';
            } else {
                $score =  $assessmentUser->score ?  $assessmentUser->score : 0;
                $passOption = $assessmentUser->pass_option == 'pass' ? 'pass' : 'submitted';
            }
            
        } else {
            $score = $rightAns == $ans ? 1 : 0; 
        }
        //dd($passOption);exit;
        $isFinalSubmit = $request->input('submit_assess') == 2 ? true : false;
       
        if($isFinalSubmit) { //dd($request->input('answers'));exit; 
           
            $isSaved =  AssessmentUser::query()
                            ->where('id', $id)
                            ->update([
                                'answers' => $request->input('answers'),
                                'score' => $score,
                                'pass_option'  => $passOption                         
                        ]);
            if($type == 'long_answer') { 
                Notification::send( User::query()->where('id', $course->user_id)->first(), new submitAssessmentLongAnswer($assessmentUser, $courseId) );
            }
            return $this->performSubmitLogic($id, $courseId, $course, $ans, $score, $assessmentQ);
                  
        } else { 
            $redirectLink = $request->input('submit_assess');
            $isSaved =  AssessmentUser::query()
                            ->where('id', $id)
                            ->update([
                                'answers' => $request->input('answers'),
                                'score' => $score,
                                'pass_option'  => $passOption
                        ]);
            if($type == 'long_answer') { 
                Notification::send( User::query()->where('id', $course->user_id)->first(), new submitAssessmentLongAnswer($assessmentUser, $courseId) );
            }
           return redirect($redirectLink)->with('success', trans('Saved successfully'));
        }      
    }

    protected function returnToView($assessment, $messageType, $message)
    {
        return redirect()->route('courses.assessment', $assessment)
              ->with(
                  $messageType,
                  __($message)
              );
    }
}
