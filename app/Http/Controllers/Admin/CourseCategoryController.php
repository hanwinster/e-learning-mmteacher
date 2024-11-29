<?php

namespace App\Http\Controllers\Admin;

use App\Models\CourseCategory;
use App\Models\Course;
use App\Http\Requests\RequestCourseCategory as Request;
use App\Http\Controllers\Controller;
use App\Repositories\CourseCategoryRepository;
use Spatie\MediaLibrary\Models\Media;

class CourseCategoryController extends Controller
{
    protected $repository;

    public function __construct(CourseCategoryRepository $repository)
    {
        $this->repository = $repository;
        $this->middleware('permission:view_course_category',['only' => 'view']);
        $this->middleware('permission:add_course_category', ['only' => ['create','store']]);
        $this->middleware('permission:edit_course_category', ['only' => ['edit','update']]);
        $this->middleware('permission:delete_course_category', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->repository->index(request());

        return view('backend.course-category.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.course-category.form');
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
            return redirect()->route('admin.course-category.index')
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        } elseif ($request->input('btnApply')) {
            return redirect()->route('admin.course-category.edit', $id)
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        } else {
            return redirect()->route('admin.course-category.index')
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

        return view('backend.course-category.form', compact('post'));
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
            return redirect()->route('admin.course-category.index')
              ->with(
                  'success',
                ' #' . $id . ' has been successfully updated.'
              );
        } elseif ($request->input('btnApply')) {
            return redirect()->route('admin.course-category.edit', $id)
              ->with(
                  'success',
                ' #' . $id . ' has been successfully updated.'
              );
        } else {
            return redirect()->route('admin.course-category.index')
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
        $courses = Course::where('course_category_id', $id)->get();
        
        if (count($courses) > 0)
        {
            return redirect()->route('admin.course-category.index')->with('warning', 'You cannot delete this resource because it is used in course.');
        }        
        $post->delete();

        return redirect()->route('admin.course-category.index')
          ->with('success', 'Successfully deleted');
    }
}
