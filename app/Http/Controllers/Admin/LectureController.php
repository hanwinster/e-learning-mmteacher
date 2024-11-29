<?php

namespace App\Http\Controllers\Admin;

// use Illuminate\Http\Request;
use App\Http\Requests\RequestLecture as Request;
use App\Http\Controllers\Controller;
use App\Repositories\CourseRepository;
use App\Repositories\LectureRepository;

class LectureController extends Controller
{
    public function __construct(LectureRepository $repository, CourseRepository $courseRepository)
    {
        $this->repository = $repository;
        $this->courseRepository = $courseRepository;
        $this->middleware('permission:view_lecture');
        $this->middleware('permission:add_lecture', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_lecture', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_lecture', ['only' => ['destroy']]);

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
        return view('backend.lecture.form', compact('course'));
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
        $this->repository->saveRecord($request);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('admin.lecture.create', $course_id)
              ->with(
                  'success',
                  __('Lecture has been successfully saved. And you are ready to create a new lecture.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('admin.lecture.edit', [$id])
              ->with(
                  'success',
                  __('Lecture has been successfully saved.')
              );
        } elseif ($request->input('btnSaveNext')) {
            return redirect()->route('admin.quiz.create', [ 'course_id' => $course_id, 'lecture_id' => $id])
              ->with(
                  'success',
                  __('Lecture has been successfully saved and you can add new quiz.')
              );
        } else {
            return redirect(route('admin.course.show', $course_id). '#nav-lecture')
              ->with(
                  'success',
                  __('Lecture has been successfully saved.')
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
        return view('backend.lecture.form', compact('course', 'post'));
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
        $lecture = $this->repository->find($id);
        $this->repository->saveRecord($request, $id);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('admin.lecture.create', $lecture->course_id)
              ->with(
                  'success',
                  __('Lecture has been successfully update. And you are ready to create a new lecture.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('admin.lecture.edit', [$id])
              ->with(
                  'success',
                  __('Lecture has been successfully update.')
              );
        } elseif ($request->input('btnSaveNext')) {
            return redirect(route('admin.course.show', [ 'course_id' => $lecture->course_id]) . '#nav-quiz')
              ->with(
                  'success',
                  __('Lecture has been successfully saved and you can add new quiz.')
              );
        } else {
            return redirect(route('admin.course.show', $lecture->course_id). '#nav-lecture')
              ->with(
                  'success',
                  __('Lecture has been successfully update.')
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

        return redirect(route('admin.course.show', $course_id). '#nav-lecture')
          ->with('success', 'Successfully deleted');
    }
}
