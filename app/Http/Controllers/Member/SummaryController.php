<?php

namespace App\Http\Controllers\Member;

use App\Http\Requests\RequestSummary as RequestSummary;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repositories\CourseRepository;
use App\Repositories\SummaryRepository;
use App\Models\Lecture;

class SummaryController extends Controller
{
    public function __construct(SummaryRepository $repository, CourseRepository $courseRepository)
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
        return view('frontend.member.summary.form', compact('course','lectures'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\RequestSummary  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestSummary $request, $course_id)
    {
        $request->validated();
        $this->courseRepository->find($course_id);
        $this->repository->saveRecord($request);
        $this->courseRepository->updateLastModifiedPerson(auth()->user()->id);
        $id = $this->repository->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('member.summary.create', $course_id)
              ->with(
                  'success',
                  __('Summary has been successfully saved. And you are ready to create a new summary.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('member.summary.edit', $id)
              ->with(
                  'success',
                  __('Summary has been successfully saved.')
              );
        } else {
            return redirect(route('member.course.show', $course_id).'#nav-summary')
              ->with(
                  'success',
                  __('Summary has been successfully saved.')
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
        $summary = $this->repository->find($id);
        return view('frontend.member.summary.show', compact('summary'));
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
        return view('frontend.member.summary.form', compact('course', 'post','lectures'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\RequestSummary  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestSummary $request, $id)
    {
        $request->validated();
        $summary = $this->repository->find($id);
        $this->repository->saveRecord($request, $id);
        $this->courseRepository->updateLastModifiedPerson(auth()->user()->id);
        $id = $this->repository->getKeyId();

        if ($request->input('btnSave')) {
            return redirect()->route('member.summary.edit', [$id])
              ->with(
                  'success',
                  __('Summary has been successfully update.')
              );
        } else {
            return redirect(route('member.course.show', $summary->course_id).'#nav-summary')
              ->with(
                  'success',
                  __('Summary has been successfully update.')
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
        return redirect(route('member.course.show', $post->course_id). '#nav-summary')
          ->with('success', 'Successfully deleted');
    }

}
