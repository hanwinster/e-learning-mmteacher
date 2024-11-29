<?php

namespace App\Http\Controllers\Member;

use App\Http\Requests\RequestCourse as RequestCourse;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Repositories\CourseRepository;
use App\Repositories\UserRepository;
use App\Repositories\CoursePermissionRepository;
use App\Repositories\LectureRepository;
use App\Repositories\LearningActivityRepository;
use App\Repositories\SummaryRepository;
use App\Repositories\AssessmentQARepository;
use App\Repositories\QuizRepository;
use App\Repositories\CertificateRepository;
use App\Repositories\LiveSessionRepository;
use App\Repositories\DiscussionRepository;
use App\Repositories\DiscussionMessageRepository;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\CourseCategory;
use App\Models\CourseLevel;
use App\Models\CourseType;
use App\Models\CourseEvaluation;
use App\Models\EvaluationUser;
use App\User;
use DB;
use Spatie\MediaLibrary\Models\Media;
use stdClass;
use App\Notifications\NotifyAllCourseTakers;
use Notification;

class CourseController extends Controller
{
    public function __construct(CourseRepository $repository, LectureRepository $lectureRepository, 
        LearningActivityRepository $lAR, QuizRepository $quizRepository, CertificateRepository $certificateRepository,
        LiveSessionRepository $liveSessionRepository, DiscussionRepository $discussionRepository, 
        DiscussionMessageRepository $disMesRepo, SummaryRepository $summaryRepository, AssessmentQARepository $aqaRepo)
    {
        $this->repository = $repository;
        $this->lectureRepository = $lectureRepository;
        $this->quizRepository = $quizRepository;
        //$this->assignmentRepository = $assignmentRepository;
        $this->certificateRepository = $certificateRepository;
        $this->liveSessionRepository = $liveSessionRepository;
        $this->discussionRepository = $discussionRepository;
        $this->disMesRepo = $disMesRepo;
        $this->summaryRepository = $summaryRepository;
        $this->assessmentQARepository = $aqaRepo;
        $this->learningActivityRepository = $lAR;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $user_type = currentUserType();
        
        if ($user_type == User::TYPE_ADMIN) {
            $posts = $this->repository->index(request());
        } elseif ($user_type == User::TYPE_MANAGER) {
            if(auth()->user()->isUnescoManager()) {
                $posts = $this->repository->index(request());
            } else {
                $posts = $this->repository->indexForManager(request());
            }          
        } else {
            $posts = $this->repository->indexForMember(request());
        }
        // if(auth()->user()->id == 14736) {
        //     dd($posts) ;exit;
        // }
        //$categories = CourseCategory::getItemList(); // 1 => "Information Technology" ,2 => "Database Management"
        $categories = addTranslations(CourseCategory::getItemList());     
        $allLevels = addTranslations(CourseLevel::getItemList());
        $categories->prepend(trans('- Select Course Category -'), '');
        $levels = ['' => trans('- Select Level -')] + $allLevels->toArray(); // Course::LEVELS;
        $approvalStatus = ['' => trans('- Select Approval Status -')] + addTranslations(Course::APPROVAL_STATUS);
        
        $uploaded_by = null;
        if (auth()->user()->isAdmin() || auth()->user()->isUnescoManager()) {
            $uploaded_by = UserRepository::getAllUploaders();
        } elseif (auth()->user()->isManager()) {
            $uploaded_by = UserRepository::getAllUploadersFromSameCollege();
        }
        return view('frontend.member.course.index', compact('posts','categories', 'levels', 'approvalStatus', 'uploaded_by'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = CourseCategory::getItemList();
        $allLevels = CourseLevel::getItemList();
        $categories->prepend('- Select Course Category -', '');
        $levels = ['' => '- Select Level -'] + $allLevels->toArray();
        $allTypes = CourseType::getItemList();
        $types = $allTypes->toArray();
        $downloadable_options = ['' => '- Select Downloadable Option -'] + Course::DOWNLOADABLE_OPTIONS;
        if(auth()->user()->isAdmin() || auth()->user()->isUnescoManager()) {
            $userTypes = User::TYPES;
        } else {
            $userTypes = User::TYPES_FOR_EDC;
        }
        
        $approvalStatus = Course::APPROVAL_STATUS;
        $estimatedUnits = Course::ESTIMATED_DURATION_UNIT;
        // Get default selected rights
        $default_rights = $this->repository->getDefaultRightsForCourseForm(currentUserType());
        $isOwnerAndMgr =  true && auth()->user()->type == 'manager' ? true : false;
        $canPublish = CoursePermissionRepository::canPublish();
        $canApprove = CoursePermissionRepository::canApprove();
        $canLock = CoursePermissionRepository::canLock();
        $uploadedBy = null;
        if (auth()->user()->isAdmin() || auth()->user()->isUnescoManager()) {
            $uploadedBy = UserRepository::getAllUploaders();
        } elseif (auth()->user()->isManager() || auth()->user()->isTeacherEducator()) {
            $uploadedBy = UserRepository::getAllUploadersFromSameCollege();
        }
        
        return view(
            'frontend.member.course.form',
            compact(
                'levels',
                'types',
                'downloadable_options',
                'categories',
                'userTypes',
                'approvalStatus',
                'default_rights',
                'isOwnerAndMgr',
                'canPublish',
                'canApprove',
                'canLock',
                'estimatedUnits',
                'uploadedBy'
            )
        );
    }

    protected function refineThumbPath($path)
    {
        $coverThumb = '';
        if($path) {
            $temp = explode("//", $path);
            $coverThumb = sizeof($temp) > 1 && isset($temp[1]) ? $temp[1] : '';
        }
        return $coverThumb;
    }

    protected function performAddingDefaults($request, $id)
    {
        $certificate = $this->certificateRepository->getByCourse($request, $id)->first();       
        if(!$certificate) {
            $sampleRequest = new stdClass();
            $sampleRequest->title = config('cms.certificate_default_title');
            $sampleRequest->certify_text = config('cms.certificate_certify_text');
            $sampleRequest->completion_text = config('cms.certificate_completion_text').strip_tags($request->input('title'));
            $sampleRequest->description = "";
            $sampleRequest->certificate_date = "";
            $sampleRequest->course_id = $id;
            $sampleRequest->file = null;
            $this->certificateRepository->saveRecord($sampleRequest);
        }

        $discussion = $this->discussionRepository->getByCourse($request, $id)->first();  
        if(!$discussion) { // no discussion is configured and then set defaults
            $sampleDiss = new stdClass();
            $sampleDiss->title = strip_tags($request->input('title')); // just provide the course title
            $sampleDiss->description = "";
            $sampleDiss->course_id = $id;
            $sampleDiss->allow_takers = true; // by default
            $sampleDiss->allow_learners = false; // by default
            $this->discussionRepository->saveRecord($sampleDiss);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\RequestCourse $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestCourse $request)
    {  
        $validated = $request->validated();
        //dd($request->input('course_type_id'));exit;
        $this->repository->saveRecord($request);
        $id = $this->repository->getKeyId();
        if( $request->input('course_type_id') == 1 ) { // if certified, add default texts
            $this->performAddingDefaults($request,$id);
        }

        if( $request->input('allow_discussion') == 1 ) {
            $this->performAddingDefaults($request,$id);
        }

        if ($request->input('btnSaveNew')) {
            return redirect()->route('member.course.create')
              ->with(
                  'success',
                  __('Course has been successfully saved. And you are ready to create a new course.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('member.course.edit', $id)
              ->with(
                  'success',
                  __('Course has been successfully saved.')
              );
        }  elseif ($request->input('btnSaveNext')) {
            return redirect()->route('member.lecture.create', $id)
              ->with(
                  'success',
                  __('Course has been successfully saved and you can add new lecture for this course.')
              );
        } else {
            return redirect()->route('member.course.index')
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
        // $coverThumb = $this->refineThumbPath($course->getThumbnailPath());
        $lectures = $this->lectureRepository->getByCourse(request(), $id);
        //$assignments = $this->assignmentRepository->getByCourse(request(), $id);
        //$assignments_for_only_course = $this->assignmentRepository->getForOnlyCourse(request(), $id);
        $learningActivities = $this->learningActivityRepository->getByCourse(request(), $id);
        $learningActivities_for_only_course = $this->learningActivityRepository->getForOnlyCourse(request(), $id);     
        $quizs = $this->quizRepository->getByCourse(request(), $id);
        $quizs_for_only_course = $this->quizRepository->getForOnlyCourse(request(), $id);
        $summaries = $this->summaryRepository->getByCourse(request(), $id);
        $summary_for_only_course = $this->summaryRepository->getForOnlyCourse(request(), $id);  // dd($summary_for_only_course); exit;
        $assessmentQAs = $this->assessmentQARepository->getByCourse(request(), $id);
        $levels = CourseLevel::getItemList();
        $allTypes = CourseType::getItemList();
        $types = $allTypes->toArray();
        $quiz_types = Quiz::QUIZ_TYPES;
        $userTypes = User::TYPES;
        //dd($certificate->first()['title']);exit;
        $certificates = $this->certificateRepository->getByCourse(request(), $id);
        $certificate = $certificates->first();
        $sessions = $this->liveSessionRepository->getByCourse(request(), $id);  
        $sessions_for_only_course = $this->liveSessionRepository->getForOnlyCourse(request(), $id);
        $discussion = $this->discussionRepository->getByCourse(request(), $id)->first(); 
        $messages = [];
        $participants = [];
        $evaluationQs = CourseEvaluation::orderBy('order')->get(); //all();
        if(isset($discussion) && $discussion->count()) {
            $messages = $this->disMesRepo->getMessagesByDiscussionId($discussion->id);
            
            for($i = 0; $i < sizeof($messages); $i++) {
                $user = User::getUserById($messages[$i]['user_id']);
                $messages[$i]['username'] = $user->username;
                $messages[$i]['avatar'] = $user->getThumbnailPath();
                if(!in_array($messages[$i]['user_id'], $participants)) {
                    array_push($participants, $messages[$i]['user_id']);
                }
            }
        } 
        $categories = addTranslations(CourseCategory::getItemList());  
        $mainSectionsForFelxible = $this->repository->getMainSectionsForFlexibleOrder($id);
       
        return view(
          'frontend.member.course.show',
          compact(
              'course',
              'userTypes',
              'levels',
              'types',
              'lectures',
              'categories',
            //   'assignments',
            //   'assignments_for_only_course',
              'learningActivities',
              'learningActivities_for_only_course',
              'quizs',
              'quiz_types',
              'quizs_for_only_course',
              'summaries',
              'summary_for_only_course', 
              //'coverThumb',
              'certificate',
              'sessions', 
              'sessions_for_only_course', 
              'discussion',
              'messages',
              'participants',
              'assessmentQAs',
              'evaluationQs',
              'mainSectionsForFelxible'
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
        $coverThumb = $post->getThumbnailPath(); //$this->refineThumbPath($post->getThumbnailPath());
        $categories = addTranslations(CourseCategory::getItemList());   //dd($categories);exit;
        //$categories->prepend('- Select Course Category -', '');
        $allLevels = CourseLevel::getItemList();
        $levels = ['' => '- Select Level -'] + $allLevels->toArray(); //Course::LEVELS;
        $allTypes = CourseType::getItemList();
        $types = $allTypes->toArray();
        $downloadable_options = ['' => '- Select Downloadable Option -'] + Course::DOWNLOADABLE_OPTIONS;
        if(auth()->user()->isAdmin() || auth()->user()->isUnescoManager()) {
            $userTypes = User::TYPES;
        } else {
            $userTypes = User::TYPES_FOR_EDC;
        }
        $estimatedUnits = Course::ESTIMATED_DURATION_UNIT;
        $approvalStatus = Course::APPROVAL_STATUS;

        // Get default selected rights
        $isOwnerAndMgr = ( ( $post->user_id == auth()->user()->id ) && ( auth()->user()->type == 'manager' ) )? true : false;
        $default_rights = $this->repository->getDefaultRightsForCourseForm(currentUserType());
        if($isOwnerAndMgr) {
            if (($key = array_search('manager', $default_rights)) !== false) {
                unset($default_rights[$key]);
            }
        }
        //dd($default_rights);exit;
        $canPublish = CoursePermissionRepository::canPublish($post);
        $canApprove = CoursePermissionRepository::canApprove();
        $canLock = CoursePermissionRepository::canLock($post);
        $uploadedBy = null;
        if (auth()->user()->isAdmin() || auth()->user()->isUnescoManager()) {
            $uploadedBy = UserRepository::getAllUploaders();
        } elseif (auth()->user()->isManager() || auth()->user()->isTeacherEducator()) {
            $uploadedBy = UserRepository::getAllUploadersFromSameCollege();
        }
        return view(
            'frontend.member.course.form',
            compact(
                'post',
                'levels',
                'types',
                'downloadable_options',
                'categories',
                'userTypes',
                'approvalStatus',
                'default_rights',
                'isOwnerAndMgr',
                'canPublish',
                'canApprove',
                'canLock',
                'coverThumb',
                'estimatedUnits',
                'uploadedBy'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     * @param App\Http\Requests\RequestCourse $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestCourse $request, $id)
    {  
        $validated= $request->validated(); 
        $this->repository->saveRecord($request, $id);
        //$id = $this->repository->getKeyId();
        if( $request->input('course_type_id') == 1 ) { 
            $this->performAddingDefaults($request,$id);
        }

        if( $request->input('allow_discussion') == 1 ) {
            $this->performAddingDefaults($request,$id);
        }
        if ($request->input('btnSaveNew')) {
            return redirect()->route('member.course.create')
              ->with(
                  'success',
                  __('Course has been successfully updated. And you are ready to create a new course.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('member.course.edit', $id)
              ->with(
                  'success',
                  __('Course has been successfully updated.')
              );
        } elseif ($request->input('btnSaveNext')) {
            return redirect(route('member.course.show', $id).'#nav-lecture')
              ->with(
                  'success',
                  __('Course has been successfully updated and you can add new lecture for this course.')
              );
        } else {
            return redirect()->route('member.course.index')
              ->with(
                  'success',
                  __('Course has been successfully updated.')
              );
        }
    }

    public function takeCourseUser($course_id)
    {
      $course = Course::findOrFail($course_id);
     // dd($course->courseLearners);exit;
      return view('frontend.member.course.take_course_user', compact('course'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        
        //DB::beginTransaction();
        try {
          //$this->repository->destroy($id); 
          //DB::commit();
            $course = Course::findOrFail($id);
            if ( count($course->learners) ) { // || count($course->assessmentUsers) || count($course->evaluationUsers)){
                return redirect()->back()->with('error', trans('You cannot delete this course because it already had course takers!'));
            }
            if(isset($course->lectures) && count($course->lectures)) {
                foreach($course->lectures as $idx => $cl) {
                    if($cl) { 
                        foreach ($cl->quizzes as $key => $quiz) {
                            foreach ($quiz->questions as $key => $question) {
                                $question->true_false_answer()->delete();
                                $question->multiple_answers->each->delete();
                                $question->true_false_answer()->delete();
                                $question->blank_answer()->delete();
                                $question->rearrange_answer()->delete();
                                $question->matching_answer()->delete();
                            // $question->assignments->each->delete();
                            }
                            $quiz->questions()->delete();
                        }
                        $cl->quizzes()->delete();
                    }
                }
            }
            if(isset($course->quizzes) && count($course->quizzes)) {
                foreach ($course->quizzes as $key => $quiz) {
                    foreach ($quiz->questions as $key => $question) {
                        $question->true_false_answer()->delete();
                        $question->multiple_answers->each->delete();
                        $question->true_false_answer()->delete();
                        $question->blank_answer()->delete();
                        $question->rearrange_answer()->delete();
                        $question->matching_answer()->delete();
                    // $question->assignments->each->delete();
                    }
                    $quiz->questions()->delete();
                }
            }
            if(isset($course->summaries) && count($course->summaries)) {
                foreach ($course->summaries as $key => $sum) {
                    $sum->delete();
                }
            }
            if(isset($course->assessmentQuestionAnswers) && count($course->assessmentQuestionAnswers)) {
                foreach ($course->assessmentQuestionAnswers as $key => $aqa) {
                    $aqa->delete();
                }
            }
            if(isset($course->assessmentUsers) && count($course->assessmentUsers)) {
                foreach ($course->assessmentUsers as $key => $au) {
                    $au->delete();
                }
            }
            if(isset($course->evaluationUsers) && count($course->evaluationUsers)) {
                foreach ($course->evaluationUsers as $key => $eu) {
                    $eu->delete();
                }
            }
            if(isset($course->certificate) && $course->certificate) {      
                $course->certificate->delete();        
            }
            if(isset($course->discussion) && $course->discussion) {      
                $course->discussion->delete();        
            }
            if(isset($course->liveSessions) && count($course->liveSessions)) {
                foreach ($course->liveSessions as $key => $ls) {
                    $ls->delete();
                }
            }
            if(isset($course->ratingReviews) && count($course->ratingReviews)) {
                foreach ($course->ratingReviews as $key => $rr) {
                    $rr->delete();
                }
            }
            $course->delete();
            return redirect()->back()->with('success', 'Successfully deleted');
        } catch (\PDOException $e) {
          \Log::emergency("File : " . $e->getFile() . "Message : " . $e->getMessage() . "Message : " . $e->getLine());
          //DB::rollback();
          return redirect()->back()->with('error', 'Error occured while deleting');
        }
        // return redirect()->back()
        //     ->with('danger', "This course #ID" . $id . " can't be deleted because it is used in other resources.");

    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editAssessment($id)
    {
        $post = $this->repository->find($id);
        
        
        return view(
            'frontend.member.assessment.form',
            compact(
                'post'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     * @param App\Http\Requests\RequestCourse $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAssessment(\Illuminate\Http\Request $request, $id)
    {  
        //$validated= $request->validated(); 
        $this->repository->saveRecord($request, $id);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSave')) {
            return redirect()->route('member.course.assessment.edit', $id)
              ->with(
                  'success',
                  __('Assessment has been successfully updated.')
              );
        } else {
            return redirect()->route('member.course.show', $id)
              ->with(
                  'success',
                  __('Assessment has been successfully updated.')
              );
        }
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showEvaluations($courseId)
    {
        $evaluations = EvaluationUser::where('course_id',$courseId)->get();
        $course = Course::findOrFail($courseId);
        //dd($evaluations);exit;
        
        return view(
            'frontend.member.evaluation.user_evaluations',
            compact(
                'course','evaluations'
            )
        );
    }

    public function notifyCourseTakers(\Illuminate\Http\Request $request)
    {
        $data = $request->all();
        $courseId  = $data['course_id']; 
        $course = Course::findOrFail($courseId);
        
        try {
            $users = $course->courseLearners;
            Notification::send($users, new NotifyAllCourseTakers($course, $data['noti_subject'], $data['noti_message']));
            return redirect()->route('member.take-course-user', [$course->id])
              ->with(
                  'success',
                  __('The email has been sent successfully!')
              );
        } catch (Exception $e) {
            return redirect()->route('member.take-course-user', [$course->id])
                ->with(
                    'error',
                    __('Error occured while sending email!. Please try again')
                );
        }
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editOrder($id)
    {
        $post = $this->repository->find($id);
        $originalOrder = $this->repository->getMainSectionsForFlexibleOrder($id);// for drop-down values only 
        $all = $this->repository->getAllSectionsForCourse($id);
        $mainSectionsForFelxible = $post->orders && count($post->orders) === count($all) ?
                                     $post->orders : $originalOrder;
        $lectureSectionsForFlexible = $post->lecture_order_type == 'flexible' && $post->lecture_orders ?
                                      $post->lecture_orders : $this->repository->getLectureSectionsForFlexibleLectureOrder($id);
       
        $lectureTitles = [];
        $lectureIDs = [];
        if(count($post->lectures)) {
            foreach($post->lectures as $lecture) {
               array_push($lectureTitles, $lecture->lecture_title);
               array_push($lectureIDs, $lecture->id);
            }
        }
       // dd($post->lecture_orders);
        // echo "<br/>";
        //dd($this->repository->getLectureSectionsForFlexibleLectureOrder($id));
      //  exit;
        return view(
            'frontend.member.course.order_form',
            compact(
                'post',
                'mainSectionsForFelxible',
                'lectureSectionsForFlexible',
                'lectureTitles',
                'lectureIDs'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     * @param App\Http\Requests\RequestCourse $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateOrder(\Illuminate\Http\Request $request, $id)
    {  
        if($request->input('order_type') == 'flexible') { 
            $validator = Validator::make($request->all(), [
                'orders' => 'required|array'
            ]);
            if ($validator->fails()) {
                return redirect()->route('member.course.order.edit', $id)
                  ->with(
                      'error',
                      __('Orders should not be empty for flexible order')
                  );
            }
            if(hasDuplicatesInArray($request->all()['orders'])) {
                return redirect()->route('member.course.order.edit', $id)
                  ->with(
                      'error',
                      __('Duplicate values while saving the order data. Please revise them and save again!')
                  );
            }
        } 
        if($request->input('lecture_order_type') == 'flexible') { 
            $validator = Validator::make($request->all(), [
                'lecture_orders' => 'required|array'
            ]);
            if ($validator->fails()) {
                return redirect()->route('member.course.order.edit', $id)
                  ->with(
                      'error',
                      __('Lecture orders should not be empty for flexible lecture order')
                  );
            }
            if(hasDuplicatesInLectureOrderArray($request->all()['lecture_orders'])) {
                return redirect()->route('member.course.order.edit', $id)
                  ->with(
                      'error',
                      __('Duplicate values while saving the lecture order data. Please revise them and save again!')
                  );
            }
        } 
      //  dd($request->all());exit;
        $this->repository->updateOrder($request, $id);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSave')) {
            return redirect()->route('member.course.order.edit', $id)
              ->with(
                  'success',
                  __('Order has been successfully updated.')
              );
        } else {
            return redirect()->route('member.course.show', $id)
              ->with(
                  'success',
                  __('Order has been successfully updated.')
              );
        }
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cloneCourse(\Illuminate\Http\Request $request, $id)
    {  
       // dd($id);exit;
    }

}
