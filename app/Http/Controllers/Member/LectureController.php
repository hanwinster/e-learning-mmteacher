<?php

namespace App\Http\Controllers\Member;

use App\Http\Requests\RequestLecture as RequestLecture;
use App\Http\Controllers\Controller;
use App\Models\Lecture;
use App\Repositories\CourseRepository;
use App\Repositories\LectureRepository;
use PhpOffice\PhpPresentation\IOFactory;

class LectureController extends Controller
{
    public function __construct(LectureRepository $repository, CourseRepository $courseRepository)
    {
        $this->repository = $repository;
        $this->courseRepository = $courseRepository;
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
        return view('frontend.member.lecture.form', compact('course'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\RequestLecture  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestLecture $request, $course_id)
    {
        $request->validated();
        $this->courseRepository->find($course_id);
        $this->repository->saveRecord($request);
        $this->courseRepository->updateLastModifiedPerson(auth()->user()->id);
        $id = $this->repository->getKeyId();

        $lecture = Lecture::find($id);

        if( $lecture->resource_type == 'attach_file' ) {
            $fileToConvert = $lecture->media->first()->getPath();
            // if($request->file('attached_file')->getClientOriginalExtension() == 'pptx' ||
            //      $request->file('attached_file')->getClientOriginalExtension() == 'ppt') {
            //     $this->repository->convertPresentationFile($fileToConvert, $lecture);
            // }
        }
        
        if ($request->input('btnSaveNew')) {
            return redirect()->route('member.lecture.create', $course_id)
              ->with(
                  'success',
                  __('Lecture has been successfully saved. And you are ready to create a new lecture.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('member.lecture.edit', [$id])
              ->with(
                  'success',
                  __('Lecture has been successfully saved.')
              );
        } elseif ($request->input('btnSaveNext')) {
            return redirect()->route('member.quiz.create', ['course_id' => $course_id, 'lecture_id' => $id])
              ->with(
                  'success',
                  __('Lecture has been successfully saved.')
              );
        } else {
            return redirect(route('member.course.show', $course_id).'#nav-lecture')
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
        return view('frontend.member.lecture.form', compact('course', 'post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\RequestLecture  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestLecture $request, $id)
    {
        $request->validated(); 
        $lecture = $this->repository->find($id);
        $this->repository->saveRecord($request, $id);
        $this->courseRepository->updateLastModifiedPerson(auth()->user()->id);
        if($request->file('attached_file')) {
            $fileToConvert = $lecture->media->first()->getPath();
            // if( $request->file('attached_file')->getClientOriginalExtension() == 'ppt' || 
            //     $request->file('attached_file')->getClientOriginalExtension() == 'pptx') {
            //     $this->repository->convertPresentationFile($fileToConvert, $lecture, 
            //     $request->file('attached_file')->getClientOriginalExtension());
            // }
        }
        $id = $this->repository->getKeyId();
        if ($request->input('btnSaveNew')) {
            return redirect()->route('member.lecture.create', $lecture->course_id)
              ->with(
                  'success',
                  __('Lecture has been successfully update. And you are ready to create a new lecture.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('member.lecture.edit', [$id])
              ->with(
                  'success',
                  __('Lecture has been successfully update.')
              );
        } elseif ($request->input('btnSaveNext')) {
            return redirect(route('member.course.show', $lecture->course_id)."#nav-quiz")
              ->with(
                  'success',
                  __('Lecture has been successfully saved and you can add new quiz.')
              );
        } else {
            return redirect(route('member.course.show', $lecture->course_id).'#nav-lecture')
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
//        $post->lectureLearners()->detach();
        $course_id = $post->course_id;
        $post->delete();

        return redirect(route('member.course.show', $course_id). '#nav-lecture')
          ->with('success', 'Successfully deleted');
    }
}
