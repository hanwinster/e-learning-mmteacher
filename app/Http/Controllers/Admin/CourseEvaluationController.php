<?php

namespace App\Http\Controllers\Admin;

use App\Models\CourseEvaluation;
use App\Models\Course;
use App\Http\Requests\RequestCourseEvaluation as Request;
use App\Http\Controllers\Controller;
use App\Repositories\CourseEvaluationRepository;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Support\Facades\Auth;
class CourseEvaluationController extends Controller
{
    protected $repository;

    public function __construct(CourseEvaluationRepository $repository)
    {   
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $posts = $this->repository->index(request());
        $types = CourseEvaluation::EVALUATION_TYPES;
        return view('backend.course-evaluation.index', compact('posts', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = CourseEvaluation::EVALUATION_TYPES;
        return view('backend.course-evaluation.form', compact('types'));
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
            return redirect()->route('admin.course-evaluation.index')
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        } elseif ($request->input('btnApply')) {
            return redirect()->route('admin.course-evaluation.edit', $id)
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        } else {
            return redirect()->route('admin.course_level.index')
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
        $types = CourseEvaluation::EVALUATION_TYPES;
        return view('backend.course-evaluation.form', compact('post', 'types'));
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
            return redirect()->route('admin.course-evaluation.index')
              ->with(
                  'success',
                ' #' . $id . ' has been successfully updated.'
              );
        } elseif ($request->input('btnApply')) {
            return redirect()->route('admin.course-evaluation.edit', $id)
              ->with(
                  'success',
                ' #' . $id . ' has been successfully updated.'
              );
        } else {
            return redirect()->route('admin.course-evaluation.index')
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
        
        /* TODO need to check if the question was answered in one of the courses */
        // $courses = Course::where('course_evaluation_id', $id)->get();
        
        // if (count($courses) > 0)
        // {
        //     return redirect()->route('admin.course-evaluation.index')->with('warning', 
        //     'You cannot delete this resource because it is used in course.');
        // }        
        $post->delete();

        return redirect()->route('admin.course-evaluation.index')
          ->with('success', 'Successfully deleted');
    }
}
