<?php

namespace App\Http\Controllers\Admin;

// use Illuminate\Http\Request;
use App\Http\Requests\RequestQuiz as Request;
use App\Http\Controllers\Controller;
use App\Repositories\CourseRepository;
use App\Repositories\QuizRepository;
use App\Models\Quiz;
use App\Models\Lecture;
use DB;

class QuizController extends Controller
{
    public function __construct(QuizRepository $repository, CourseRepository $courseRepository)
    {
        $this->repository = $repository;
        $this->courseRepository = $courseRepository;
        // $this->middleware('permission:view_lecture');
        // $this->middleware('permission:add_lecture', ['only' => ['create', 'store']]);
        // $this->middleware('permission:edit_lecture', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:delete_lecture', ['only' => ['destroy']]);

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
    public function create($course_id)
    {
        $course = $this->courseRepository->find($course_id);
        $quiz_types = Quiz::QUIZ_TYPES;
        $lectures = Lecture::where('course_id', $course->id)->get()->pluck('lecture_title', 'id');
        $lectures->prepend($course->title, '');
        return view('backend.quiz.form', compact('course', 'lectures','quiz_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $course_id)
    {

        $this->courseRepository->find($course_id);
        DB::beginTransaction();

        try {
            $this->repository->saveRecord($request);
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollback();
            \Log::emergency("File : " . $e->getFile() . "\n Message : " . $e->getMessage());
            return redirect()->route('admin.quiz.create', $course_id)
              ->with(
                  'danger',
                  __('Quiz add failed.')
              );
        }

        $id = $this->repository->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('admin.quiz.create', $course_id)
              ->with(
                  'success',
                  __('Quiz has been successfully saved. And you are ready to create a new quiz.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('admin.quiz.edit', [$id])
              ->with(
                  'success',
                  __('Quiz has been successfully saved.')
              );
        } elseif ($request->input('btnSaveAddQuestion')) {
            return redirect()->route('admin.question.create', [$id])
              ->with(
                  'success',
                  __('Quiz has been successfully saved and you are ready to create new question.')
              );
        } else {
            return redirect(route('admin.course.show', $course_id).'#nav-quiz')
              ->with(
                  'success',
                  __('Quiz has been successfully saved.')
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
        $course = $this->courseRepository->find($post->course_id);
        $quiz_types = Quiz::QUIZ_TYPES;
        $lectures = Lecture::where('course_id', $course->id)->get()->pluck('lecture_title', 'id');
        $lectures->prepend($course->title, '');
        return view('backend.quiz.form', compact('course', 'post', 'lectures', 'quiz_types'));
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
        $course = $this->repository->find($id);
        DB::beginTransaction();
        try {
            $this->repository->saveRecord($request, $id);
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollback();
            \Log::emergency("File : " . $e->getFile() . "\n Message : " . $e->getMessage());
            return redirect()->route('admin.quiz.create', $course->course_id)
              ->with(
                  'danger',
                  __('Quiz update failed.')
              );
        }
        $id = $this->repository->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('admin.quiz.create', $course->course_id)
              ->with(
                  'success',
                  __('Quiz has been successfully updated. And you are ready to create a new quiz.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('admin.quiz.edit', [$id])
              ->with(
                  'success',
                  __('Quiz has been successfully updated.')
              );
        } elseif ($request->input('btnSaveAddQuestion')) {
            return redirect()->route('admin.question.create', [$id])
              ->with(
                  'success',
                  __('Quiz has been successfully updated and you are ready to create new question.')
              );
        } else {
            return redirect(route('admin.course.show', $course->course_id).'#nav-quiz')
              ->with(
                  'success',
                  __('Quiz has been successfully updated.')
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
        $course_id = $post->course_id;
        $post->delete();
        return redirect(route('admin.course.show', $course_id). '#nav-quiz')
          ->with('success', 'Successfully deleted');          
    }
}
