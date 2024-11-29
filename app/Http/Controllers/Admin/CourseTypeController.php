<?php

namespace App\Http\Controllers\Admin;

use App\Models\CourseType;
use App\Models\Course;
use App\Http\Requests\RequestCourseType as Request;
use App\Http\Controllers\Controller;
use App\Repositories\CourseTypeRepository;


class CourseTypeController extends Controller
{
    protected $repository;

    public function __construct(CourseTypeRepository $repository)
    {  
        $this->repository = $repository;
        $this->middleware('permission:view_course_type',['only' => 'view']);
        $this->middleware('permission:add_course_type', ['only' => ['create','store']]);
        $this->middleware('permission:edit_course_type', ['only' => ['edit','update']]);
        $this->middleware('permission:delete_course_type', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $posts = $this->repository->index(request());
        return view('backend.course-type.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.course-type.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\RequestCourseCategory $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validated();

        $this->repository->saveRecord($request);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSave')) {
            return redirect()->back()
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        } elseif ($request->input('btnApply')) {
            return redirect()->route('admin.course-type.edit', $id)
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        } else {
            return redirect()->back()
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = $this->repository->find($id);

        return view('backend.course-type.form', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\RequestCourseCategory $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validated();

        $this->repository->saveRecord($request, $id);

        if ($request->input('btnSave')) {
            return redirect()->back()
              ->with(
                  'success',
                ' #' . $id . ' has been successfully updated.'
              );
        } elseif ($request->input('btnApply')) {
            return redirect()->route('admin.course-type.edit', $id)
              ->with(
                  'success',
                ' #' . $id . ' has been successfully updated.'
              );
        } else {
            return redirect()->back()
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->repository->find($id);
       
        /* check if it is used in course */
        $courses = Course::where('course_type_id', $id)->get();
        if (count($courses) > 0)
        {
            return redirect()->back()->with('warning', 
            'You cannot delete this course type because it is used in course.');
        }
        try {
          $post->delete();
          return redirect()->back()->with('success', 'Successfully deleted');
        } catch (\Exception $e) { 
         
          return redirect()->back()->with('error', 'Errors occur while deleting'. $e);
        }        
        

        
    }
}
