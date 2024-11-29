<?php

namespace App\Http\Controllers\Admin;

// use Illuminate\Http\Request;
use App\Http\Requests\RequestQuestion as Request;
use App\Http\Controllers\Controller;
use App\Repositories\QuizRepository;
use App\Models\Lecture;
use App\Models\Course;
use App\Models\Quiz;
use App\Repositories\QuestionRepository;

class QuestionController extends Controller
{
    public function __construct(QuestionRepository $repository, QuizRepository $quizRepository)
    {
        $this->repository = $repository;
        $this->quizRepository = $quizRepository;
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
        return view('backend.question.form', compact('course', 'quiz'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $quiz_id)
    {

        $request->validated();
        $quiz = $this->quizRepository->find($quiz_id);
        $course = Course::findOrFail($quiz->course_id);
        $this->repository->saveRecord($request);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('admin.question.create', $quiz->id)
              ->with(
                  'success',
                  __('Question has been successfully saved. And you are ready to create a new question.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('admin.question.edit', $id)
              ->with(
                  'success',
                  __('Question has been successfully saved.')
              );
        } else {
            return redirect(route('admin.course.show', $course->id).'#nav-quiz')
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
        $quiz = $this->quizRepository->find($post->quiz_id);
        $course = Course::findOrFail($quiz->course_id);
        return view('backend.question.form', compact('course', 'quiz', 'post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      // dd($request->all());
        $request->validated();
        $question = $this->repository->find($id);
        $this->repository->saveRecord($request, $id);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('admin.question.create', $question->quiz->course->id)
              ->with(
                  'success',
                  __('Question has been successfully update. And you are ready to create a new question.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('admin.question.edit', [$id])
              ->with(
                  'success',
                  __('Question has been successfully update.')
              );
        } else {
            return redirect(route('admin.course.show', $question->quiz->course->id).'#nav-quiz')
              ->with(
                  'success',
                  __('Question has been successfully update.')
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
        return redirect(route('admin.course.show', $course_id). '#nav-quiz')
          ->with('success', 'Question has successfully deleted');
    }
}
