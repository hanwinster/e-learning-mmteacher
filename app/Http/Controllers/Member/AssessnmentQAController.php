<?php

namespace App\Http\Controllers\Member;

use App\Http\Requests\RequestAssessmentQuestionAnswer as RequestAssessmentQuestionAnswer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repositories\CourseRepository;
use App\Repositories\AssessmentQARepository;
use App\Repositories\CourseLearnerRepository;
use App\Models\AssessmentQuestionAnswer;
use App\Models\AssessmentUser;
use App\Models\Lecture;
use App\Models\Course;
use App\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FeedbackAssessmentLongAnswer;

class AssessnmentQAController extends Controller
{
    public function __construct(AssessmentQARepository $repository, CourseRepository $courseRepository, CourseLearnerRepository $clRepo)
    {
        $this->repository = $repository;
        $this->courseRepository = $courseRepository;
        $this->clRepo = $clRepo;
        // $this->middleware('permission:view_assignment');
        // $this->middleware('permission:add_assignment', ['only' => ['create','store']]);
        // $this->middleware('permission:edit_assignment', ['only' => ['edit','update']]);
        // $this->middleware('permission:delete_assignment', ['only' => ['destroy']]);
    }
    public function userLongAnswer($courseId,$id)
    {  
        $long_answer = AssessmentQuestionAnswer::where('id', $id)->first(); 
        $user_answers = AssessmentUser::where('assessment_question_answer_id', $id)->get();
        $course = Course::where('id', $courseId)->first();
        $canComment = auth()->user()->id === $course->user_id ? true : false;
        return view('frontend.member.assessment.user-long-answers', compact('long_answer', 'user_answers', 'canComment', 'courseId'));
    }

    public function reviewAnswer(Request $request)
    {   //dd($request->all());exit;
        $answer = AssessmentUser::findOrFail($request->id); //dd($answer->assessment_question_answer);exit; 
        $assessment = AssessmentQuestionAnswer::findOrFail($answer->assessment_question_answer_id);
        $course = Course::where('id', $assessment->course_id)->first();
        $findValue = 'assessment_'.$assessment->id;
        if (empty($answer->comment_by)) {
            $answer->comment_by = auth()->user()->id;
        }
        $answer->comment = $request->comment;
        $answer->pass_option = $request->pass_option;
        
        if($assessment->passing_option == 'after_sending_feedback') {
            $answer->score = 1;
            $this->clRepo->performCompletionLogic($course->id, $answer->user_id, $findValue, true);
            $this->performSubmitLogic($answer->id, $course->id,$course, 1, $assessment);
        }
        if($assessment->passing_option == 'after_setting_pass' && $answer->pass_option == 'pass') {
            $answer->score = 1;
            $this->clRepo->performCompletionLogic($course->id, $answer->user_id, $findValue, true);
            $this->performSubmitLogic($answer->id, $course->id,$course,  1, $assessment);
        }
        $answer->save();
       // Notification::send(User::query()->where('id', $answer->user_id)->first(), new FeedbackAssessmentLongAnswer($answer));

        return view('frontend.member.assessment.dynamic_long_answer_user_row', compact('assessment', 'answer'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($course_id)
    {
    	$types = AssessmentQuestionAnswer::ASSESSMENT_TYPES;
        $course = $this->courseRepository->find($course_id);
        return view('frontend.member.assessment.question-answer-form', compact('course', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\RequestAssessmentQuestionAnswer  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestAssessmentQuestionAnswer $request, $course_id)
    {   //dd($request->all());exit;
        $request->validated();
        $this->courseRepository->find($course_id);
        $this->repository->saveRecord($request);
        $this->courseRepository->updateLastModifiedPerson(auth()->user()->id);
        $id = $this->repository->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('member.course.assessment-qa.create', $course_id)
              ->with(
                  'success',
                  __('Assessment has been successfully saved. And you are ready to create a new assignment.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('member.course.assessment-qa.edit', $id)
              ->with(
                  'success',
                  __('Assessment has been successfully saved.')
              );
        } else {
            return redirect(route('member.course.show', $course_id).'#nav-assessment')
              ->with(
                  'success',
                  __('Assessment has been successfully saved.')
              );
        }
    }


    /**
     * Display the user's assignment.
     *
     * @param  int  $course_id, int $assignment_id
     * @return \Illuminate\Http\Response
     */
    public function userAssessment($id)
    {
        $assignment = $this->repository->find($id);
        $user_assignments = AssessmentUser::where('assignment_id', $id)->paginate();
        return view('frontend.member.assignment.user_assignment', compact('assignment', 'user_assignments'));
    }

    /**
     * Update the user's comment.
     *
     * @param  int  $course_id, int $assignment_id
     * @return \Illuminate\Http\Response
     */
    public function updateComment(RequestAssessmentQuestionAnswer $request)
    {
        $assignment_user = AssessmentUser::findOrFail($request->id);
        $assignment = $this->repository->find($assignment_user->assignment_id);
        if (empty($assignment_user->comment_by)) {
            $assignment_user->comment_by = auth()->user()->id;
        }
        $assignment_user->comment = $request->comment;
        $assignment_user->save();
        return view('frontend.member.assignment.dynamic_assignment_user_row', compact('assignment', 'assignment_user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = $this->repository->find($id);
        $course = $this->courseRepository->find($post->course_id);  
        $types = AssessmentQuestionAnswer::ASSESSMENT_TYPES;    
        return view('frontend.member.assessment.question-answer-form', compact('course', 'post', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\RequestAssessmentQuestionAnswer  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestAssessmentQuestionAnswer $request, $id)
    {
        $request->validated();
        $this->repository->saveRecord($request, $id);

        $iD = $this->repository->getKeyId();
        $assessment = $this->repository->find($iD);
        $this->courseRepository->updateLastModifiedPerson(auth()->user()->id);
        if ($request->input('btnSave')) {
            return redirect()->route('member.course.assessment-qa.edit', $iD)
              ->with(
                  'success',
                  __('Assessment has been successfully updated.')
              );
        } else {
            return redirect(route('member.course.show', $assessment->course_id).'#nav-assessment')
              ->with(
                  'success',
                  __('Assessment has been successfully updated.')
              );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->repository->find($id);
        /* TODO to check if there's already a user submission for the assessment */
        $post->assessment_user->each->delete();
        $post->delete();
        return redirect(route('member.course.show', $post->course_id). '#nav-assessment')
          ->with('success', 'Successfully deleted');
    }

    protected function performSubmitLogic($id, $courseId, $course, $score, $assessmentQ)
    {
            $assessmentA = AssessmentUser::findOrFail($id); 
            $attempts = $assessmentA->attempts > 0 ? $assessmentA->attempts + 1 : 1; 
            $allAnswers = AssessmentUser::where('course_id', $courseId)
                                    ->where('user_id',$assessmentA->user_id)->get();
            $hasPendingActionByCourseOwner = false; 
            foreach($allAnswers as $ans) {
                if($ans->pass_option == 'submitted' || $ans->pass_option == 'retake') {
                    $hasPendingActionByCourseOwner = true;
                }
            }
            $sum =0; 
            for($i =0; $i < count($allAnswers); $i++) {
                $sum += $allAnswers[$i]->score;
            }
            //dd($allAnswers);exit;
            $overallScore = ($sum / count($allAnswers) ) * 100; //echo $overallScore;exit;
            AssessmentUser::query()
                            ->where('id', $id)
                            ->update([                         
                                'score' => $score,
                                'attempts' => $attempts,
                                'status' =>  2,
                                'overall_score' => $overallScore
            ]);
            $added = AssessmentUser::query() // need to update other records for the course as well
                            ->where('course_id', $courseId)
                            ->where('user_id', $assessmentA->user_id) 
                            ->update([
                                'attempts' => $attempts,
                                'status' =>  2,
                                'overall_score' => $overallScore
            ]);
            //dd($added); exit;
            if($overallScore >= $course->acceptable_score_for_assessment ) {
                $this->clRepo->setPercentageAfterSubmittingAssessment($courseId, $assessmentA->user_id, 100);               
            } else {
                $this->clRepo->setPercentageAfterSubmittingAssessment($courseId, $assessmentA->user_id, 99);
                
            }
    }
}
