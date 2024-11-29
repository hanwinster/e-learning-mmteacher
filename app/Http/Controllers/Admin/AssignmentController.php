<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RequestAssignment as Request;
use App\Notifications\FeedbackAssignment;
use App\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Repositories\CourseRepository;
use App\Repositories\AssignmentRepository;
use App\Models\AssignmentUser;

class AssignmentController extends Controller
{
    public function __construct(AssignmentRepository $repository, CourseRepository $courseRepository)
    {
        $this->repository = $repository;
        $this->courseRepository = $courseRepository;
        $this->middleware('permission:view_assignment');
        $this->middleware('permission:add_assignment', ['only' => ['create', 'store']]);
        $this->middleware('permission:add_assignment', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_assignment', ['only' => ['edit', 'update']]);
        $this->middleware('permission:add_assignment_comment', ['only' => ['updateComment']]);

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
        return view('backend.assignment.form', compact('course'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $course_id)
    {
        $request->validated();
        $this->courseRepository->find($course_id);
        $this->repository->saveRecord($request);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('admin.assignment.create', $course_id)
              ->with(
                  'success',
                  __('Assignment has been successfully saved. And you are ready to create a new assignment.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('admin.assignment.edit', [$id])
              ->with(
                  'success',
                  __('Assignment has been successfully saved.')
              );
        } else {
            return redirect(route('admin.course.show', $course_id). '#nav-assignment')
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
        return view('backend.assignment.show', compact('assignment'));
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
        return view('backend.assignment.user_assignment', compact('assignment', 'user_assignments'));
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
        return view('backend.assignment.form', compact('course', 'post'));
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
        $request->validated();
        $assignment = $this->repository->find($id);
        $this->repository->saveRecord($request, $id);

        $id = $this->repository->getKeyId();
        if ($request->input('btnSaveNew')) {
            return redirect()->route('admin.assignment.create', $assignment->course_id)
              ->with(
                  'success',
                  __('Assignment has been successfully update. And you are ready to create a new assignment.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('admin.assignment.edit', $id)
              ->with(
                  'success',
                  __('Assignment has been successfully update.')
              );
        } else {
            return redirect(route('admin.course.show', $assignment->course_id). '#nav-assignment')
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
        $course_id = $post->course_id;
        $post->assignment_user->each->delete();
        $post->delete();
        return redirect(route('admin.course.show', $course_id). '#nav-assignment')
          ->with('success', 'Successfully deleted');
    }
}
