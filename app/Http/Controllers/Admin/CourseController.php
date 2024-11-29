<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RequestCourse as Request;
use App\Http\Controllers\Controller;
use App\Repositories\CourseRepository;
use App\Repositories\UserRepository;
use App\Models\CourseCategory;
use App\Repositories\CoursePermissionRepository;
use App\Repositories\LectureRepository;
use App\Repositories\AssignmentRepository;
use App\Repositories\QuizRepository;
use App\Models\Course;
use App\Models\Quiz;
use App\User;
use DB;

class CourseController extends Controller
{
    public function __construct(CourseRepository $repository, LectureRepository $lectureRepository, AssignmentRepository $assignmentRepository, QuizRepository $quizRepository)
    {
        $this->repository = $repository;
        $this->lectureRepository = $lectureRepository;
        $this->quizRepository = $quizRepository;
        $this->assignmentRepository = $assignmentRepository;
        $this->middleware('permission:view_course');
        $this->middleware('permission:add_course', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_course', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_course', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->repository->index(request());

        $categories = CourseCategory::getItemList();
        $categories->prepend('-Category-', '');
        $levels = ['' => '-Level-'] + Course::LEVELS;
        $approvalStatus = ['' => '-Approval Status-'] + Course::APPROVAL_STATUS;
        $uploaded_by = UserRepository::getAllUploaders();

        return view('backend.course.index', compact('posts', 'categories', 'levels', 'approvalStatus', 'uploaded_by'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = CourseCategory::getItemList();
        $categories->prepend('- Select Course Category -', '');
        $levels = ['' => '- Select Level -'] + Course::LEVELS;
        $downloadable_options = ['' => '- Select Downloadable Option -'] + Course::DOWNLOADABLE_OPTIONS;
        $userTypes = User::TYPES;
        $approvalStatus = Course::APPROVAL_STATUS;

        // Get default selected rights
        $default_rights = $this->repository->getDefaultRightsForCourseForm(currentUserType());

        $canPublish = CoursePermissionRepository::canPublish();
        $canApprove = CoursePermissionRepository::canApprove();
        $canLock = CoursePermissionRepository::canLock();

        return view(
            'backend.course.form',
            compact(
                'levels',
                'downloadable_options',
                'categories',
                'userTypes',
                'approvalStatus',
                'default_rights',
                'canPublish',
                'canApprove',
                'canLock'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ini_set('max_execution_time', 120);
        ini_set('upload_max_filesize', 5120);
        \Log::info('Loading......');
        $validated = $request->validated();
        $this->repository->saveRecord($request);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('admin.course.create')
              ->with(
                  'success',
                  __('Course has been successfully saved. And you are ready to create a new course.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('admin.course.edit', $id)
              ->with(
                  'success',
                  __('Course has been successfully saved.')
              );
        } elseif ($request->input('btnSaveNext')) {
            return redirect()->route('admin.lecture.create', $id)
              ->with(
                  'success',
                  __('Course has been successfully saved and you can add new lecture for this course.')
              );
        } elseif ($request->input('btnSaveNew')) {
            return redirect()->route('admin.course.create')
              ->with(
                  'success',
                  __('Course has been successfully saved and add new lecture for this course.')
              );
        } else {
            return redirect()->route('admin.course.index')
              ->with(
                  'success',
                  __('Course has been successfully saved.')
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
      $course = $this->repository->find($id);
      $lectures = $this->lectureRepository->getByCourse(request(), $id);
      $assignments = $this->assignmentRepository->getByCourse(request(), $id);
      $levels = Course::LEVELS;
      $userTypes = User::TYPES;
      $quiz_types = Quiz::QUIZ_TYPES;
      $quizs = $this->quizRepository->getByCourse(request(), $id);
      $quizs_for_only_course = $this->quizRepository->getForOnlyCourse(request(), $id);
      return view(
          'backend.course.show',
          compact(
              'course',
              'userTypes',   
              'levels',
              'lectures',
              'assignments',
              'quizs',
              'quiz_types',
              'quizs_for_only_course'
          )
      );
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
        $categories = CourseCategory::getItemList();
        $categories->prepend('- Select Course Category -', '');
        $levels = ['' => '- Select Level -'] + Course::LEVELS;
        $downloadable_options = ['' => '- Select Downloadable Option -'] + Course::DOWNLOADABLE_OPTIONS;
        $userTypes = User::TYPES;

        $approvalStatus = Course::APPROVAL_STATUS;

        // Get default selected rights
        $default_rights = $this->repository->getDefaultRightsForCourseForm(currentUserType());

        $canPublish = CoursePermissionRepository::canPublish($post);
        $canApprove = CoursePermissionRepository::canApprove();
        $canLock = CoursePermissionRepository::canLock($post);

        return view(
            'backend.course.form',
            compact(
                'post',
                'levels',
                'downloadable_options',
                'categories',
                'userTypes',
                'approvalStatus',
                'default_rights',
                'canPublish',
                'canApprove',
                'canLock'
            )
        );
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
        // dd($request->all());
        $this->repository->saveRecord($request, $id);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('admin.course.create')
              ->with(
                  'success',
                  __('Course has been successfully updated. And you are ready to create a new course.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('admin.course.edit', $id)
              ->with(
                  'success',
                  __('Course has been successfully updated.')
              );
        } elseif ($request->input('btnSaveNext')) {
            return redirect(route('admin.course.show', $id) . '#nav-lecture')
              ->with(
                  'success',
                  __('Course has been successfully updated and you can add new lecture for this course.')
              );
        } else {
            return redirect()->route('admin.course.index')
              ->with(
                  'success',
                  __('Course has been successfully updated.')
              );
        }
    }

    public function takeCourseUser($course_id)
    {
      $course = Course::findOrFail($course_id);
      // $learners = DB::table('course_learners')->where('course_id', $course_id)
      //                   join('users')
      return view('backend.course.take_course_user', compact('course'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      DB::beginTransaction();
        try {
          $this->repository->destroy($id);
          DB::commit();  
          return redirect()->back()
            ->with('success', 'Successfully deleted');        
        } catch (\PDOException $e) {
          \Log::emergency("File : " . $e->getFile() . "Message : " . $e->getMessage() . "Message : " . $e->getLine());
          DB::rollback();
        }
        return redirect()->back()
            ->with('danger', "This course #ID" . $id . " can't be deleted because it is used in other resources.");
    }
}
