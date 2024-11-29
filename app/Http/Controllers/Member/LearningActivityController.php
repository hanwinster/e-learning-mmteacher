<?php

namespace App\Http\Controllers\Member;

use App\Http\Requests\RequestLearningActivity as RequestLearningActivity;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repositories\CourseRepository;
use App\Repositories\LearningActivityRepository;
use App\Models\Lecture;

class LearningActivityController extends Controller
{
    public function __construct(LearningActivityRepository $repository, CourseRepository $courseRepository)
    {
        $this->repository = $repository;
        $this->courseRepository = $courseRepository;
        // $this->middleware('permission:view_assignment');
        // $this->middleware('permission:add_assignment', ['only' => ['create','store']]);
        // $this->middleware('permission:edit_assignment', ['only' => ['edit','update']]);
        // $this->middleware('permission:delete_assignment', ['only' => ['destroy']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($course_id)
    {
        $course = $this->courseRepository->find($course_id);
        $lectures = Lecture::where('course_id', $course->id)->get()->pluck('lecture_title', 'id');
    	$lectures->prepend($course->title, '');
        return view('frontend.member.learning-activity.form', compact('course','lectures'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\RequestLearningActivity  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestLearningActivity $request, $course_id)
    {
        $request->validated();
        $this->courseRepository->find($course_id);
        $this->repository->saveRecord($request);
        $this->courseRepository->updateLastModifiedPerson(auth()->user()->id);
        $id = $this->repository->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('member.learning-activity.create', $course_id)
              ->with(
                  'success',
                  __('LearningActivity has been successfully saved. And you are ready to create a new LearningActivity.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('member.learningActivity.edit', $id)
              ->with(
                  'success',
                  __('LearningActivity has been successfully saved.')
              );
        } else {
            return redirect(route('member.course.show', $course_id).'#nav-learning-activity')
              ->with(
                  'success',
                  __('LearningActivity has been successfully saved.')
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
        $LearningActivity = $this->repository->find($id);
        return view('frontend.member.learning-activity.show', compact('LearningActivity'));
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
        $lectures = Lecture::where('course_id', $course->id)->get()->pluck('lecture_title', 'id');
    	$lectures->prepend($course->title, '');
        return view('frontend.member.learning-activity.form', compact('course', 'post','lectures'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\RequestLearningActivity  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestLearningActivity $request, $id)
    {
        $request->validated();
        $LearningActivity = $this->repository->find($id);
        $this->repository->saveRecord($request, $id);
        $this->courseRepository->updateLastModifiedPerson(auth()->user()->id);
       // $id = $this->repository->getKeyId();

        if ($request->input('btnSave')) {
            return redirect()->route('member.learning-activity.edit', [$id])
              ->with(
                  'success',
                  __('LearningActivity has been successfully update.')
              );
        } else {
            return redirect(route('member.course.show', $LearningActivity->course_id).'#nav-learning-activity')
              ->with(
                  'success',
                  __('LearningActivity has been successfully update.')
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
        $post->delete();
        return redirect(route('member.course.show', $post->course_id). '#nav-learning-activity')
          ->with('success', 'Successfully deleted');
    }

}
