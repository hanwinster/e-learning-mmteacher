<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Requests\RequestQuestion as RequestQuestion;
use App\Http\Controllers\Controller;
use App\Repositories\QuizRepository;
use App\Repositories\CourseRepository;
use App\User;
use App\Models\Lecture;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\LongAnswer;
use App\Models\LongAnswerUser;
use App\Repositories\QuestionRepository;
use App\Repositories\CourseLearnerRepository;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FeedbackLongAnswer;

class QuestionController extends Controller
{
    private $repository;
    private $clRepo;
    private $quizRepository; 

    public function __construct(QuestionRepository $repository, QuizRepository $quizRepository, CourseRepository $courseRepository, CourseLearnerRepository $clRepo )
    {
        $this->repository = $repository;
        $this->quizRepository = $quizRepository;
        $this->clRepo = $clRepo;
        $this->courseRepository = $courseRepository;
        // $this->middleware('permission:view_question');
        // $this->middleware('permission:add_question', ['only' => ['create','store']]);
        // $this->middleware('permission:edit_question', ['only' => ['edit','update']]);
        // $this->middleware('permission:delete_question', ['only' => ['destroy']]);
    }

    /**
     * Display the user's assignment.
     *
     * @param  int  $course_id, int $assignment_id
     * @return \Illuminate\Http\Response
     */
    public function userLongAnswer($id, $courseId)
    {  
        $long_answer = LongAnswer::where('id', $id)->first(); 
        $user_answers = LongAnswerUser::where('long_answer_id', $id)->get();
        $course = Course::where('id', $courseId)->first();
        $canComment = auth()->user()->id === $course->user_id ? true : false;
        return view('frontend.member.long-answer.user-long-answers', compact('long_answer', 'user_answers', 'canComment', 'courseId'));
    }

    public function reviewAnswer(Request $request)
    {
        $answer = LongAnswerUser::findOrFail($request->id);
        $la = LongAnswer::findOrFail($answer->long_answer_id);
      //  $question = $la->question; 
        $quiz = $answer->long_answer->question->quiz; //$question->quiz;
        $course = Course::where('id', $quiz->course_id)->first();
        $findValue = $quiz->lecture_id == null ? 'quiz_'.$quiz->id : 'lq_'.$quiz->id;
        if (empty($answer->comment_by)) {
            $answer->comment_by = auth()->user()->id;
        }
        $answer->comment = $request->comment;
        $answer->status = $request->status;
        $answer->save();
        //TODO: to add logic to complete the section for the user!!!
        if($la->passing_option == 'after_sending_feedback') {
            $this->clRepo->performCompletionLogic($course->id, $answer->user_id, $findValue, true);
        }
        if($la->passing_option == 'after_setting_pass' && $answer->status == 'pass') {
            $this->clRepo->performCompletionLogic($course->id, $answer->user_id, $findValue, true);
        }
        Notification::send(User::query()->where('id', $answer->user_id)->first(), new FeedbackLongAnswer($answer));

        return view('frontend.member.long-answer.dynamic_long_answer_user_row', compact('la', 'answer'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($quiz_id)
    {
        $quiz = $this->quizRepository->find($quiz_id);
        $course = Course::findOrFail($quiz->course_id);
        $count = 0; // 0 will be always sentence for paragraph in fill in the blank
        $paragraph =  []; // fill in the blank
        $alphabets = config('cms.alphabets');
        $numbers = config('cms.rearrange_numbers');
        return view('frontend.member.question.form', compact('course', 'quiz',  'count', 'paragraph', 'alphabets', 'numbers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\RequestQuestion  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestQuestion $request, $quiz_id)
    {   
        $request->validated();
        $quiz = $this->quizRepository->find($quiz_id);
        $course = Course::findOrFail($quiz->course_id);
        $this->repository->saveRecord($request);
        $this->courseRepository->updateLastModifiedPerson(auth()->user()->id);
        $id = $this->repository->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('member.question.create', $quiz->id)
              ->with(
                  'success',
                  __('Question has been successfully saved. And you are ready to create a new question.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('member.question.edit', $id)
              ->with(
                  'success',
                  __('Question has been successfully saved.')
              );
        } else {
            return redirect(route('member.course.show', $course->id).'#nav-quiz')
              ->with(
                  'success',
                  __('Question has been successfully saved.')
              );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $count = isset($post->blank_answer) && $post->blank_answer->paragraph ? count($post->blank_answer->paragraph) + 1 : 0;
        $paragraph = isset($post->blank_answer) && $post->blank_answer->paragraph ? $post->blank_answer->paragraph : [];
        $quiz = $this->quizRepository->find($post->quiz_id);
        $course = Course::findOrFail($quiz->course_id); //dd($post->blank_answer->paragraph);exit;
        $alphabets = config('cms.alphabets');
        $numbers = config('cms.rearrange_numbers');
        return view('frontend.member.question.form', compact('course', 'quiz', 'post', 'count', 'paragraph','alphabets', 'numbers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\RequestQuestion  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestQuestion $request, $id)
    {   
    	//print_r($request->all()); 
        
        /* TODO: Validations */
        $validator = $request->validated();
        $question = $this->repository->find($id);
        $this->repository->saveRecord($request, $id);
        $this->courseRepository->updateLastModifiedPerson(auth()->user()->id);
        $id = $this->repository->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('member.question.create', $question->quiz->course->id)
              ->with(
                  'success',
                  __('Question has been successfully updated. And you are ready to create a new question.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('member.question.edit', [$id])
              ->with(
                  'success',
                  __('Question has been successfully updated.')
              );
        } else {
            return redirect(route('member.course.show', $question->quiz->course->id).'#nav-quiz')
              ->with(
                  'success',
                  __('Question has been successfully updated.')
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
        $course_id = $post->quiz->course->id;
        $post->delete();
        return redirect(route('member.course.show', $course_id). '#nav-quiz')
          ->with('success', 'Question has successfully deleted');
    }
}
