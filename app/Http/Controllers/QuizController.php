<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Support\Facades\Validator;
use App\Models\Lecture;
use App\Models\Quiz;
use App\Models\Assignment;
use App\Models\AssignmentUser;
use App\Models\LongAnswer;
use App\Models\LongAnswerUser;
use App\Models\Question;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;
use App\Notifications\SubmitAssignment;
use App\Notifications\SubmitLongAnswer;
use Illuminate\Support\Facades\Notification;
use App\Repositories\CourseLearnerRepository;
use App\Repositories\CourseRepository;
use Jenssegers\Agent\Agent;

class QuizController extends Controller
{
    private $resource;
    private $clRepo;
    private $repo; 
    private $assignmentUser;

    public function __construct(Quiz $quiz, CourseRepository $repo, CourseLearnerRepository $clRepo, 
        AssignmentUser $assignUser, LongAnswerUser $laUser)
    {
        $this->middleware('auth');
        $this->resource = $quiz;
        $this->repo = $repo;
        $this->clRepo = $clRepo;
        $this->assignmentUser = $assignUser;
        $this->laUser = $laUser;
    }

    public function showQuiz($id)
    {
        $currentQuiz = $this->resource->with('questions')->findOrFail($id); //echo $currentQuiz->type;exit;
        $assignment = null;
        $assignmentMedia = null;
        $assignmentInfo = null;
        $questionMedia = null; 
        $blankAnswers = [];
        $matchingAnswers = [];    
        $question = Question::where('quiz_id',$id)->first();   
        if($currentQuiz->type === 'blank') {
            $answers = $currentQuiz->questions()->with('blank_answer')->get();
            foreach($answers as $ans) {
                array_push($blankAnswers, $ans->blank_answer);
            }
        }  
        //dd($blankAnswers);exit;
        if($currentQuiz->type === 'matching') {
            $matchingAnswers = $currentQuiz->questions()->with('matching_answer')->get();
        }
        
        $longAnswerUser = null;
        $longAnswer = null;
        if($currentQuiz->type == 'assignment') {
            $assignment = Assignment::where('question_id',$question->id)->first(); 
            $assignmentInfo = $assignment->assignment_user->where('user_id',auth()->user()->id)->first();       
            $assignmentMedia = Media::all()->where('model_type', Question::class)
                                ->where('model_id', $question->id)
                                ->where('collection_name', 'assignment_attached_file')->first();
        } else if($currentQuiz->type == 'long_question') {
            $longAnswer = LongAnswer::where('question_id', $question->id)->first(); //dd($longAnswer);exit;
            $longAnswerUser = auth()->user()->long_answer_user->where('long_answer_id', $longAnswer->id)->first();
        } else {
            $questionMedia = array();
            foreach($currentQuiz->questions as $q) {
               $temp =  Media::all()->where('model_type', Question::class)
                ->where('model_id', $q->id)
                ->where('collection_name', 'question_attached_file')->first();
                array_push($questionMedia, $temp);
            }
        }
        //if(auth()->user()->id == 14996) {
          //dd($questionMedia);exit;
        //   foreach($currentQuiz->questions as $idx=>$q) { 
        //     echo $questionMedia[$idx]->model_id." -- ".$q->id." \n";
        //     if(isset($questionMedia[$idx]) && ($q->id == $questionMedia[$idx]->model_id)) {
        //         echo "same".asset($questionMedia[$idx]->getUrl());
        //     }
        //     exit;
        //   }
        //}
        $isLectureNull = $currentQuiz->lecture_id == null ? true : false;
        $currentSection = $currentQuiz->lecture;
        $course = $currentQuiz->course;
        unset($currentQuiz->course);
        $lectures = $course->lectures;
        $userLectures = auth()->user()->learningLectures;
        $lecturesMedias = Media::all()->where('model_type', Lecture::class);
        // if ($currentSection) {
        //     $nextQuiz = $currentSection->quizzes()->orderBy('id')->where('id', '>', $currentQuiz->id)->first();
        // } else {
        //     $nextQuiz = $course->quizzes()->orderBy('id')->where('id', '>', $currentQuiz->id)->where('lecture_id', null)->first();
        // }
        // $previousQuiz = $course->quizzes()->orderBy('id', 'desc')->where('id', '<', $currentQuiz->id)->first();
        $downloadOption = $course->downloadable_option;
        $courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
        $completed = $courseLearner->completed;
        $status = $courseLearner->status;
        $percentage = $courseLearner->percentage;

        $route = route('quiz.show', [$id]);
        $this->clRepo->updatelastVisited($course->id, auth()->user()->id, $route);  
        
        if($isLectureNull) { 
            $previousSection = $this->repo->getRouteFromValue($this->repo->getPrevSection('quiz_'.$id, $completed));
            $nextSection = $this->repo->getRouteFromValue($this->repo->getNextSection('quiz_'.$id, $completed));
            if($course->item_affect_certification == 0 && $this->clRepo->isPartTheLastONe($completed, 'quiz_'.$id)) {
                $nextSection = route('courses.evaluation', [$course]);
            }
        } else { 
            $previousSection = $this->repo->getRouteFromValue($this->repo->getPrevSection('lq_'.$id, $completed)); 
            $nextSection = $this->repo->getRouteFromValue($this->repo->getNextSection('lq_'.$id, $completed)); 
            if($course->item_affect_certification == 0 && $this->clRepo->isPartTheLastONe($completed, 'lq_'.$id)) {
                $nextSection = route('courses.evaluation', [$course]);
            }
        }
          //dd($blankAnswers[0]->paragraph);exit;
        $agent = new Agent();    
        return view('frontend.courses.quiz', compact(
                'currentSection', 'course', 'nextSection', 'previousSection', 'currentQuiz', 'agent',
                'lectures', 'userLectures', 'lecturesMedias', 'downloadOption', 'completed','status','percentage',
                'assignment','assignmentMedia', 'assignmentInfo', 'questionMedia', 'longAnswerUser','longAnswer', 'blankAnswers', 'matchingAnswers'
        ));
       // } 
        // return view('frontend.courses.quiz', compact(
        //     'currentQuiz', 'currentSection', 'course', 'nextQuiz','previousQuiz', 'lectures', 'userLectures',
        //     'lecturesMedias', 'downloadOption','completed','status','percentage'
        // ));
    }

    public function submitAssignment(Request $request, Assignment $assignment)
    {
        $this->assignmentUser = auth()->user()->assignment_user->where('assignment_id', $assignment->id)->first();
        $courseId = $request->all()['course_id'];
        $findValue = $request->all()['find_val'];
        $quizId = $request->all()['quiz_id'];
        $this->validate($request, [
            'assignment_file' => 'required|mimes:pdf,docx,doc,avi,mp4,mpeg,ppt,pptx'
        ]);

        if($this->assignmentUser) {
            $this->assignmentUser->update([
                'attached_file' => $request->file('assignment_file')->getClientOriginalName()
            ]);
        } else {
            $this->assignmentUser = AssignmentUser::query()->create([
                'assignment_id' => $assignment->id,
                'user_id' => auth()->user()->id,
                'attached_file' => $request->file('assignment_file')->getClientOriginalName()
            ]);
        }
        $this->clRepo->performCompletionLogic($courseId, auth()->user()->id, $findValue, true);
        //dd($this->assignmentUser->assignment->question);
        //dd(User::query()->where('id', $this->assignmentUser->user_id)->first());
        //exit;
        Notification::send( User::query()->where('id', $this->assignmentUser->assignment->question->user_id)->first(), new SubmitAssignment($this->assignmentUser) );

        $this->assignmentUser->addMediaFromRequest('assignment_file')->toMediaCollection('user_assignment_attached_file');

       // return redirect()->route('courses.view-assignment-feedback', $this->assignmentUser)->with('message', 'Your assignment was successfully submitted');
       return redirect()->route('quiz.show', $quizId)->with('message', 'Your assignment was successfully submitted and updated the completion status');
    }

    public function submitLongAnswer(Request $request)
    {
        $quizId = $request->all()['quiz_id'];
        $currentQuiz = $this->resource->with('questions')->findOrFail($quizId);
        
       // $courseId = $request->all()['course_id'];
        $questionId = $request->all()['question_id']; //dd($question);exit;
        $longAnswer =  LongAnswer::where('question_id', $questionId)->first();
        $this->laUser = auth()->user()->long_answer_user->where('long_answer_id', $longAnswer->id)->first();
        $course = $currentQuiz->course;
        $findValue = $request->all()['find_val']; 
        
        $validator = Validator::make($request->all(), [
            'answers' => 'required|array'
		]);
		if ($validator->fails()) {
			return response(['errors'=> $validator->errors()->all()], 422);
		}
        if($this->laUser) {
            $this->laUser->update([
                'submitted_answer' => $request->input('answers')
            ]);
        } else {
            $this->laUser = LongAnswerUser::query()->create([
                'long_answer_id' => $longAnswer->id,
                'user_id' => auth()->user()->id,
                'submitted_answer' => $request->input('answers'),
                'status' => 'submitted'
            ]);
        }
        Notification::send( User::query()->where('id', $course->user_id)->first(), new submitLongAnswer($this->laUser, $course->id) );
        //print_r($longAnswer);exit;
        if( $longAnswer->passing_option == 'after_providing_answer' ) {
            $this->clRepo->performCompletionLogic($course->id, auth()->user()->id, $findValue, true);
            return redirect()->route('quiz.show', $quizId)->with('success', 'Your answer was successfully submitted and updated the completion status');
        } elseif ($longAnswer->passing_option == 'after_sending_feedback') {
            return redirect()->route('quiz.show', $quizId)->with('success', 'Your answer was successfully submitted and this section will be completed after the course owner provides you a feedback!');
        } else {
            return redirect()->route('quiz.show', $quizId)->with('success', 'Your answer was successfully submitted and this section will be completed after the course owner considers that your answer is satisfactory!');
        }
       
       
    }


    public function checkAnswer(Request $request)
    {
        $quiz = $this->resource->findOrFail($request->quiz);
        $questions = [];

        if($quiz->type == 'true_false') {
            foreach($quiz->questions()->with('true_false_answer')->get() as $question) {
                $questions[] = $question;
            }
        } elseif ($quiz->type == 'multiple_choice') {
            foreach($quiz->questions()->with('multiple_answers')->get() as $question) {
                $questions[] = $question;
            }
            foreach($questions as $idx=>$q) {
                if( isset($q->multiple_answers) && sizeof($q->multiple_answers) > 0 ) {
                    //print_r($q->multiple_answers);exit;
                    foreach($q->multiple_answers as $idx2=>$qm) { //print_r($qm->answer);exit;
                        $qm->answer = strip_tags($qm->answer);
                    }
                }
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

    public function checkBlankAnswer(Request $request)
    {
        $quiz = $this->resource->findOrFail($request->quiz);
        
        foreach($quiz->questions()->with('blank_answer')->get() as $question) {
            $questions[] = $question;
        }
        

        return response()->json(['question' => $questions]);
    }

    // public function updateCompletion(Request $request)
    // {
    //     $courseId = $request->all()['course_id'];
    //     $findValue = $request->all()['find_val'];
    //     $userId = $request->all()['user_id'];
    //     $quizId = $request->all()['quiz_id'];
    //     if( $this->clRepo->performCompletionLogic($courseId, $userId, $findValue, true) ) {
    //         //$quiz = Lecture::findOrFail($quizId);
    //         //$course = Course::findOrFail($courseId);
    //         //$this->learnCourse($course, $lecture); 
    //         return redirect()->route('quiz.show', [$quizId] )->with('success', 'Updated!');
    //     } else {
    //         return response()->json(['error' => 'error occured while updating!']);
    //     }
    // }

    public function updateCompletionPrev(Request $request)
    {
        $courseId = $request->all()['course_id'];
        $findValue = $request->all()['find_val'];
        $userId = $request->all()['user_id'];
        $quizId = $request->all()['quiz_id'];
        $prevRoute = $request->all()['previous'];
        if( $this->clRepo->performCompletionLogic($courseId, $userId, $findValue, true) ) {
            return redirect($prevRoute)->with('success', trans('Updated the previous section!'));  
        } else {
            return response()->json(['error' => trans('error occured while updating!')]);
        }
    }

    public function updateCompletionNext(Request $request)
    {
        $courseId = $request->all()['course_id'];
        $findValue = $request->all()['find_val'];
        $userId = $request->all()['user_id'];
        $quizId = $request->all()['quiz_id'];
        $nextRoute = $request->all()['next'];
        if( $this->clRepo->performCompletionLogic($courseId, $userId, $findValue, true) ) {
            return redirect($nextRoute)->with('success', trans('Updated the previous section!'));  
        } else {
            return response()->json(['error' => trans('error occured while updating!')]);
        }
    }

}
