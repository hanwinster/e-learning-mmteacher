<?php

namespace App\Http\Controllers\Member;

use App\Http\Requests\RequestAssignment as RequestAssignment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repositories\CourseRepository;
use App\Repositories\AssignmentRepository;
use App\Models\AssignmentUser;
use App\Models\Lecture;
//use App\Models\Question;

class AssignmentController extends Controller
{
    public function __construct(AssignmentRepository $repository, CourseRepository $courseRepository)
    {
        $this->repository = $repository;
        $this->courseRepository = $courseRepository;
        $this->middleware('permission:view_assignment');
        $this->middleware('permission:add_assignment', ['only' => ['create','store']]);
        $this->middleware('permission:edit_assignment', ['only' => ['edit','update']]);
        $this->middleware('permission:delete_assignment', ['only' => ['destroy']]);
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
        return view('frontend.member.assignment.form', compact('course','lectures'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\RequestAssignment  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestAssignment $request, $course_id)
    {
        $request->validated();
        $this->courseRepository->find($course_id);
        $this->repository->saveRecord($request);
        $this->courseRepository->updateLastModifiedPerson(auth()->user()->id);
        $id = $this->repository->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('member.assignment.create', $course_id)
              ->with(
                  'success',
                  __('Assignment has been successfully saved. And you are ready to create a new assignment.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('member.assignment.edit', $id)
              ->with(
                  'success',
                  __('Assignment has been successfully saved.')
              );
        } else {
            return redirect(route('member.course.show', $course_id).'#nav-assignment')
              ->with(
                  'success',
                  __('Assignment has been successfully saved.')
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
        $assignment = $this->repository->find($id);
        return view('frontend.member.assignment.show', compact('assignment'));
    }

    /**
     * Display the user's assignment.
     *
     * @param  int  $course_id, int $assignment_id
     * @return \Illuminate\Http\Response
     */
    public function userAssignment($id)
    {  
        $assignment = $this->repository->find($id);
        $user_assignments = AssignmentUser::where('assignment_id', $id)->paginate();
        //$question = Question::findOrFail($assignment->question_id);
        //dd($assignment->question->quiz->course_id);exit;
        return view('frontend.member.assignment.user_assignment', compact('assignment', 'user_assignments'));
    }

    /**
     * Update the user's comment.
     *
     * @param  int  $course_id, int $assignment_id
     * @return \Illuminate\Http\Response
     */
    public function updateComment(RequestAssignment $request)
    {
        $assignment_user = AssignmentUser::findOrFail($request->id);
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
        $lectures = Lecture::where('course_id', $course->id)->get()->pluck('lecture_title', 'id');
    	$lectures->prepend($course->title, '');
        return view('frontend.member.assignment.form', compact('course', 'post','lectures'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\RequestAssignment  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestAssignment $request, $id)
    {
        $request->validated();
        $assignment = $this->repository->find($id);
        $this->repository->saveRecord($request, $id);
        $this->courseRepository->updateLastModifiedPerson(auth()->user()->id);
        $id = $this->repository->getKeyId();

        if ($request->input('btnSave')) {
            return redirect()->route('member.assignment.edit', [$id])
              ->with(
                  'success',
                  __('Assignment has been successfully update.')
              );
        } else {
            return redirect(route('member.course.show', $assignment->course_id).'#nav-assignment')
              ->with(
                  'success',
                  __('Assignment has been successfully update.')
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
        $post->assignment_user->each->delete();
        $post->delete();
        return redirect(route('member.course.show', $post->course_id). '#nav-assignment')
          ->with('success', 'Successfully deleted');
    }

}
