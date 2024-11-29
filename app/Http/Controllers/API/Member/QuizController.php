<?php

namespace App\Http\Controllers\API\Member;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Assignment;
use App\Models\AssignmentUser;
use App\Models\LongAnswerUser;
use App\Models\Course;
use Spatie\MediaLibrary\Models\Media;
use App\Repositories\CourseRepository;
use App\Repositories\DiscussionMessageRepository;
use App\Repositories\CourseLearnerRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Notification;
use App\Notifications\SubmitAssignment;
use App\Notifications\submitLongAnswer;
use stdClass;

class QuizController extends Controller
{
    protected $repository;
    protected $disMesRepo;
    protected $clRepo;

    public function __construct(CourseRepository $repository, DiscussionMessageRepository $disMesRepo,
                                CourseLearnerRepository $clRepository)
    {   
        $this->repository = $repository;
        $this->disMesRepo = $disMesRepo;
        $this->clRepo = $clRepository;
        
    }
    
    public function getQuizContents(Request $request, $courseId, $quizId)
    {
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        try {
            $currentQuiz = Quiz::where('id',$quizId)->where('course_id',$courseId)->first();
        } catch (ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Quiz is not found'], 404);
        }
        if(!$currentQuiz) {
            return response()->json(['code' => 404, 'message' => 'Quiz is not found'], 404);
        }
        $assignment = null;
        $assignmentMedia = null;
        $assignmentInfo = null;
        $questionMedia = null;
        $questions = Question::where('quiz_id',$quizId)->get(); 
        $currentQuizAnswers = [];
        if(!$questions) {
            return response()->json(['code' => 404, 'message' => 'Question is not found'], 404);
        }
        if($currentQuiz->type == 'assignment') {
            $question = Question::where('quiz_id',$quizId)->first(); 
            $assignment = Assignment::where('question_id',$question->id)->first(); 
            $assignmentInfo = $assignment->assignment_user->where('user_id',auth()->user()->id)->first();       
            $assignmentMedia = Media::all()->where('model_type', Question::class)
                                ->where('model_id', $question->id)
                                ->where('collection_name', 'assignment_attached_file')->first();
        } else {
            $questionMedia = array();
            foreach($currentQuiz->questions as $q) {
               $temp =  Media::all()->where('model_type', Question::class)
                ->where('model_id', $q->id)
                ->where('collection_name', 'question_attached_file')->first();
                array_push($questionMedia, $temp);
                if($currentQuiz->type == 'multiple_choice') {
                    array_push($currentQuizAnswers, $q->multiple_answers);
                }
                if($currentQuiz->type == 'true_false') {
                    array_push($currentQuizAnswers, $q->true_false_answer);
                }
                if($currentQuiz->type == 'blank') { //changeBlankParagraphFormat
                    $temp2 = $q->blank_answer->paragraph;
                    $optKeywords = $q->blank_answer->optional_keywords ? explode(',', $q->blank_answer->optional_keywords) : null;
                    $temp2 = changeBlankParagraphFormat($temp2, $optKeywords);
                    $q->blank_answer->paragraph = $temp2;
                    array_push($currentQuizAnswers, $q->blank_answer);
                }
                if($currentQuiz->type == 'short_question') {
                    array_push($currentQuizAnswers, $q->short_answer);
                }
                if($currentQuiz->type == 'long_question') {
                    array_push($currentQuizAnswers, $q->long_answer);
                }
                if($currentQuiz->type == 'rearrange') {
                    $temp3 = [];
                    foreach($q->rearrange_answer->answer as $idx => $raa) {
                        array_push($temp3, strip_tags($raa));
                    }
                    $q->rearrange_answer['right_answers'] = $temp3;
                    array_push($currentQuizAnswers, $q->rearrange_answer);
                }
                if($currentQuiz->type == 'matching') {
                    $temp4 = [];
                    foreach($q->matching_answer->answer as $idx => $raa) { //dd($raa);exit;
                        array_push($temp4, strip_tags($raa['second']));
                    }
                    $q->matching_answer['right_answers'] = $temp4;
                    array_push($currentQuizAnswers, $q->matching_answer);
                }
            }
            
        }
       
        //$isLectureNull = $currentQuiz->lecture_id == null ? true : false;
        //$currentSection = $currentQuiz->lecture;
        $course = $currentQuiz->course;
        $downloadOption = $course->downloadable_option;
        $route = route('quiz.show', [$quizId]);
        $this->clRepo->updatelastVisited($course->id, auth()->user()->id, $route);  
        //$courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
        unset($currentQuiz->course);
        $data = [
            'quiz' => $currentQuiz,
         //   'question' => $questions,
           // 'nextSection' => $nextSection,
          //  'previousSection' => $previousSection,
            'question_media' => $questionMedia,
            'assignment' => $assignment,
            'assignment_media' => $assignmentMedia,
            'download_option' => $downloadOption,
            'quiz_question_answers' => $currentQuizAnswers
           // 'completed' => $completed,
           // 'status' => $status,
           // 'percentage' => $percentage,
         //   'course_learner' => $courseLearner

        ];      
        return response()->json(['data' =>  $data], 200);
    }

    public function checkAnswer(Request $request, $courseId, $quizId)
    {
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        try {
            $currentQuiz = Quiz::where('id',$quizId)->where('course_id',$courseId)->first();
        } catch (ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Quiz is not found'], 404);
        }
        if(!$currentQuiz) {
            return response()->json(['code' => 404, 'message' => 'Quiz is not found'], 404);
        }
        $quiz = Quiz::findOrFail($quizId); //$request->quiz);
        $questions = [];

        if($quiz->type == 'true_false') {
            foreach($quiz->questions()->with('true_false_answer')->get() as $question) {
                $questions[] = $question;
            }
        } elseif ($quiz->type == 'multiple_choice') {
            foreach($quiz->questions()->with('multiple_answers')->get() as $question) {
                $questions[] = $question;
            }
        } elseif($quiz->type == 'matching') {
            foreach($quiz->questions()->with('matching_answer')->get() as $question) {
                $questions[] = $question;
            }
        } elseif($quiz->type == 'blank') {
            foreach($quiz->questions()->with('blank_answer')->get() as $question) {
                $questions[] = $question;
            }
        } else if($quiz->type == 'short_question') {
            foreach($quiz->questions()->with('short_answer')->get() as $question) {
                $questions[] = $question;
            }
        } else if($quiz->type == 'long_question') {
            foreach($quiz->questions()->with('long_answer')->get() as $question) {
                $questions[] = $question;
            }
        } else {
            foreach($quiz->questions()->with('rearrange_answer')->get() as $question) {
                $questions[] = $question;
            }
        }

        return response()->json(['question' => $questions]);
    }

    public function submitAssignment(Request $request, $courseId, $quizId)
    {
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        try {
            $currentQuiz = Quiz::where('id',$quizId)->where('course_id',$courseId)->first();
        } catch (ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Quiz is not found'], 404);
        }
        if(!$currentQuiz) {
            return response()->json(['code' => 404, 'message' => 'Quiz is not found'], 404);
        }
              
        $validator = Validator::make($request->all(), [
            'assignment_file' => 'required|mimes:pdf,docx,doc,avi,mp4,mpeg,ppt,pptx,jpeg,jpg,png,bmp,gif,svg',
            'redirect' => ['required', Rule::in(['previous', 'next']) ],
            'current_section' => 'required|string',
            'assignment_id' => 'required|string'
		]);  
		if ($validator->fails()) {
			return response(['errors'=> $validator->errors()->all()], 422);
		}
        $courseLearner = $this->clRepo->getCourseLearnerData($courseId, auth()->user()->id);
        $completed = $courseLearner->completed;
        $findValue = $request->all()['current_section'];
        $redirect = $request->all()['redirect'];
        $redirectTo = $this->clRepo->getSectionToRedirect($completed, $findValue, $redirect);  
        if($redirectTo == -1) {
            return response()->json(['error' => 'There is no '.$redirect.' path for the current section' ]);
        }
        $course = Course::where('id',$courseId)->first();
        $assignmentId = $request->all()['assignment_id'];
        $assignmentUser = auth()->user()->assignment_user->where('assignment_id', $assignmentId)->first();
        if($assignmentUser) {
            $assignmentUser->update([
                'attached_file' => $request->file('assignment_file')->getClientOriginalName()
            ]);
        } else {
            $assignmentUser = AssignmentUser::query()->create([
                'assignment_id' => $assignmentId,
                'user_id' => auth()->user()->id,
                'attached_file' => $request->file('assignment_file')->getClientOriginalName()
            ]);
        }
        $redirecToObj = new stdClass(); 
        $redirecToObj->key = $redirectTo;
        $redirecToObj->isCompleted = $this->repository->getIsCompletedFromValue($redirectTo, $completed); 
        $redirecToObj->id = CourseRepository::getIdFromValue($redirectTo, $course);
        $redirecToObj->type = $this->repository->getTypeFromValue($redirectTo, $course);
        $markCompleted = $this->clRepo->performCompletionLogic($courseId, auth()->user()->id, $findValue, true);          
        Notification::send( User::query()->where('id', $course->user_id)->first(), new SubmitAssignment($assignmentUser) );
        $assignmentUser->addMediaFromRequest('assignment_file')->toMediaCollection('user_assignment_attached_file');
       return response()->json(['data' =>  ['message' => 'Your assignment was successfully submitted and updated the completion status',
                                            'redirect_to' => $redirecToObj, 'data' => $assignmentUser] ],200);
    }

    public function submitLongAnswer(Request $request, $courseId, $quizId)
    { // deleted this api and added together with other sections in "Mark complete for current section" => courseController
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        try {
            $currentQuiz = Quiz::where('id',$quizId)->where('course_id',$courseId)->first();
        } catch (ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Quiz is not found'], 404);
        }
        if(!$currentQuiz) {
            return response()->json(['code' => 404, 'message' => 'Quiz is not found'], 404);
        }
              
        $validator = Validator::make($request->all(), [
            'answers' => 'required',
            'redirect' => ['required', Rule::in(['previous', 'next']) ],
            'current_section' => 'required|string',
            'long_answer_id' => 'required|string'
		]);  
		if ($validator->fails()) {
			return response(['errors'=> $validator->errors()->all()], 422);
		}
        $courseLearner = $this->clRepo->getCourseLearnerData($courseId, auth()->user()->id);
        $completed = $courseLearner->completed;
        $findValue = $request->all()['current_section'];
        $redirect = $request->all()['redirect'];
        $redirectTo = $this->clRepo->getSectionToRedirect($completed, $findValue, $redirect);
        if($redirectTo == -1) {
            return response()->json(['error' => 'There is no '.$redirect.' path for the current section' ]);
        }
        $course = Course::where('id',$courseId)->first();
        
        $longAnswerId = $request->all()['long_answer_id'];
        $laUser = auth()->user()->long_answer_user->where('long_answer_id', $longAnswerId)->first();
       
        if($laUser) {
            $laUser->update([
                'submitted_answer' => $request->input('answers')
            ]);
        } else {
            $laUser = LongAnswerUser::query()->create([
                'long_answer_id' => $longAnswer->id,
                'user_id' => auth()->user()->id,
                'submitted_answer' => $request->input('answers'),
                'status' => 'submitted'
            ]);
        }
        
        $markCompleted = $this->clRepo->performCompletionLogic($courseId, auth()->user()->id, $findValue, true);
        $redirecToObj = new stdClass(); 
        $redirecToObj->key = $redirectTo;
        $redirecToObj->isCompleted = $this->repository->getIsCompletedFromValue($redirectTo, $completed); 
        $redirecToObj->id = CourseRepository::getIdFromValue($redirectTo, $course);
        $redirecToObj->type = $this->repository->getTypeFromValue($redirectTo, $course); 
        Notification::send( User::query()->where('id', $course->user_id)->first(), new submitLongAnswer($laUser) );
        
        if( $longAnswer->passing_option == 'after_providing_answer' ) {
            $this->clRepo->performCompletionLogic($course->id, auth()->user()->id, $findValue, true);
            return response()->json(['data' =>  ['message' => 'Your long answer was successfully submitted and updated the completion status',
                                            'redirect_to' => $redirecToObj, 'data' => $laUser] ],200);
        } elseif ($longAnswer->passing_option == 'after_sending_feedback') {         
            return response()->json(['data' =>  ['message' => 'Your answer was successfully submitted and this section will be completed after the course owner provides you a feedback!',
                                            'redirect_to' => $redirecToObj, 'data' => $laUser] ],200);
        } else {          
            return response()->json(['data' =>  ['message' => 'Your answer was successfully submitted and this section will be completed after the course owner considers that your answer is satisfactory!',
                                            'redirect_to' => $redirecToObj, 'data' => $laUser] ],200);
        }
    }

}