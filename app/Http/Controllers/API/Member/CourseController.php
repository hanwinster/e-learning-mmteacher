<?php

namespace App\Http\Controllers\API\Member;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Repositories\CourseRepository;
use App\Repositories\DiscussionMessageRepository;
use App\Repositories\CourseLearnerRepository;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CoursePrivacy;
use App\Models\CourseEvaluation;
use App\Models\Discussion;
use App\Models\Quiz;
use App\Models\LongAnswerUser;
use App\User;
use mysql_xdevapi\Collection;
use Spatie\MediaLibrary\Models\Media;
//use Notification;
use App\Notifications\CourseEnrollment;
use App\Notifications\SubmitLongAnswer;
use Illuminate\Support\Facades\Notification;
use PDF;
use stdClass;

class CourseController extends Controller
{
    protected $repository;
    protected $disMesRepo;
    protected $clRepo;
    protected $currentUserType;

    public function __construct(CourseRepository $repository, DiscussionMessageRepository $disMesRepo,
        CourseLearnerRepository $clRepository)
    {   
        $this->repository = $repository;
        $this->disMesRepo = $disMesRepo;
        $this->clRepo = $clRepository;       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request) //NEED TO ADD ROLES
    // {  
    //     if( $request->header('Content-Language') ) {
    //         if(!setLanguageForSession($request->header('Content-Language'))) { // if language is set and it's not supported
    //             return response(['errors' => trans('Provided language is not supported')], 404); 
    //         }
    //         $lang = $request->header('Content-Language');
    //         $courses = $this->repository->getAllPublishedCoursesByLanguage(auth()->user()->type, $lang);
    //     } else { 
    //         $courses = $this->repository->getAllPublishedCourses(auth()->user()->type);
    //     }
    //     if ($courses && count($courses)  > 0) {
    //         $list = [];
    //         $list = CourseResource::collection($courses);
    //         return response(['total' => count($list),'data' => $list], 200);
    //     } else {
    //         return response(['data' => trans('There are no courses')], 404);
    //     }
    // }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function getCoursesForHomePage($lang='en')
    // {   
    //     if(auth()->check()) {
    //         $currentUserType = auth()->user()->type;
    //     } else {
    //         $currentUserType = 'guest';
    //     }
    //     try {
    //         $courses = $this->repository->getPublishedCoursesForHomeByLanguage($currentUserType, 8, $lang);
            
    //         //$courseCollection = collect();
            
    //         return response()->json(['data' => $courses]);
    //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    //         return response()->json(['code' => 404, 'message' => 'Resource Not Found'], 404);
    //     }
    // }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function showSearchResults( Request $request )
    // {   
    //     try {
    //         $searchWord = $request->input('search-word') ? $request->input('search-word') : '';
    //         $category = $request->input('category-id') ? $request->input('category-id') : null;
    //         $level = $request->input('level-id') ? $request->input('level-id') : null;
    //         $courses = Course::query()->where('is_published', 1)->with('privacies');
           
    //         if($level) {
    //             $courses = $courses->where('course_level_id', $level);
    //         }
           
    //         if($category) {  
    //             $courses = $courses->whereJsonContains('course_categories', $category);           
    //         }

    //         if($searchWord) {
    //             $courses = $courses->where('title', 'LIKE', '%'.$searchWord.'%'); 
    //         }

    //         if(auth()->check()) {
    //             $currentUserType = auth()->user()->type;
    //         } else {
    //             $currentUserType = 'guest';
    //         }
    //         $courseCollection = collect();
    //         foreach ($courses->latest()->get() as $course) { 
    //             foreach ($course->privacies as $privacy) {
    //                 if($privacy->user_type == $currentUserType ) {
    //                     $courseCollection->push($course);
    //                     break;
    //                 }                      
    //             }
    //         }
    //         return response()->json(['data' => $courseCollection]);
    //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    //         return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
    //     }
        
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function show(Request $request,$courseId)
    // {  
    //     try {
    //         $course = Course::findOrFail($courseId);
    //         $lectures = []; $temp = []; $courseQuizzes = [];
    //         if ($course->is_published) {
    //             foreach($course->lectures as $lecture) {
    //                 $temp['lecture_id'] = $lecture->id;
    //                 $temp['lecture_title'] = $lecture->lecture_title;
    //                 $temp['lecture_quiz'] =  [];
    //                 $temp2 =[];
    //                 if($course->quizzes && count($course->quizzes) > 0) {
    //                     foreach($course->quizzes()->where('lecture_id', $lecture->id)->get() as $quiz) {
    //                         $temp2['quiz_title'] = $quiz->title;
    //                         $temp2['quiz_id'] = $quiz->id;
    //                         $temp2['no_of_quiz_questions'] = $quiz->questions ? count($quiz->questions) : 0;
    //                         array_push($temp['lecture_quiz'], $temp2);
    //                     }                     
    //                 }
    //                 array_push($lectures, $temp);
    //             }
    //             foreach($course->quizzes()->where('course_id', $courseId)->get() as $quiz) {
    //                 if($quiz->lecture_id == null) {
    //                     $temp3['quiz_id'] = $quiz->id;
    //                     $temp3['quiz_title'] = $quiz->title;
    //                     $temp3['no_of_quiz_questions'] = $quiz->questions ? count($quiz->questions) : 0;
    //                     array_push($courseQuizzes, $temp3);
    //                 }                          
    //             }
    //             $ratings = $course->ratingReviews;
    //             $finalRating = 0; $ratingCount = 0;
    //             if(count($ratings) > 1) {
    //                 $sum = 0;
    //                 for($i=0; $i < count($ratings); $i++) {
    //                     $sum += $ratings[$i]->rating;
    //                     $ratingCount++;
    //                 }
    //                 $finalRating = ceil($sum/$ratingCount);
    //             } else {
    //                 $finalRating = isset($ratings[0]) ? $ratings[0]->rating : $finalRating;
    //                 $ratingCount = isset($ratings[0]) ? 1 : 0;
    //             }
    //             $discussion = $course->discussion; //dd($discussion->id);exit;
    //             $messages = [];
    //             $amIParticipatedBefore = false;
    //             if(isset($discussion) && $discussion->count() > 0) {
    //                 $messages = DiscussionMessageRepository::getMessagesByDiscussionId($discussion->id);
    //                 for($i = 0; $i < sizeof($messages); $i++) {
    //                     $user = User::getUserById($messages[$i]['user_id']);
    //                     $messages[$i]['username'] = $user->username;
    //                     $messages[$i]['avatar'] = $user->getThumbnailPath();
    //                     if( isset(auth()->user()->id) && ( auth()->user()->id == $user->id) ) {
    //                             $amIParticipatedBefore = true;
    //                     }
    //                 }
    //             } 
    //             unset($course->lectures);
    //             unset($course->quizzes);
    //             $course['course_type'] = $course->getCourseType($course->course_type_id)->name;
    //             $course['enrollments'] = $course->learners->count() ? $course->learners->count() : 0;
    //             $course['total_lectures'] = $course->lectures->count() ? $course->lectures->count() : 0;     
    //             $course['total_quizzes'] = $course->quizzes->count() ? $course->quizzes->count() : 0;         
    //             $course['views_count'] = $course->view_count + 1;   
    //             $course['no_of_people_who_rated'] = $ratingCount;
    //             $course['final_rating'] = $finalRating;       
    //             $course['can_share_on_social'] = true;
    //             $course['can_rate_the_course'] = auth()->check() && $course->allow_feedback;
    //             $amICourseOwner = (isset(auth()->user()->id) && ( auth()->user()->id == $course->user_id)) ? true : false;
    //             $isAllLearnersAllowed = $discussion ? $discussion->allow_learners: null;
    //             $isCourseTakersAllowed = $discussion ? $discussion->allow_takers	: null;
    //             $lastDiscussedTime = $discussion && count($messages) ? count($messages) - 1 : null;
    //             $course['can_participate_in_discussion'] = ($isCourseTakersAllowed && CourseLearnerRepository::isAlreadyTakenCourse(auth()->user(), $course))
    //                                                      || $isAllLearnersAllowed || $amICourseOwner;
    //             $course['is_user_taken_this_course'] = $this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId) ? true : false;                                      
    //             $course['ratingReviews']  = $course->ratingReviews ?  $course->ratingReviews : null;    
    //             $course['discussion']  = $discussion && $discussion->count() > 0 ?  $discussion : null;
    //             $course['messages']  = count($messages) > 0 ?  $messages : []; 
    //             $course['amIParticipatedBefore'] = $amIParticipatedBefore;    
    //             $course['lastDiscussedTime'] = $lastDiscussedTime;                    
    //             $course['course_lectures'] = $lectures;
    //             $course['course_quizzes'] = $courseQuizzes;
    //             if(count($messages) == 0) {
    //                 $course['no_discussion_message'] = 'No discussion for this board yet!';
    //                 if( $isAllLearnersAllowed || 
    //                     ( $isCourseTakersAllowed && \App\Repositories\CourseLearnerRepository::isAlreadyTakenCourse(auth()->user(), $course) ) ) {
    //                         $course['no_discussion_message'] = 'No discussion for this board yet! Be the first one to participate'; 
    //                     }
    //             }
    //             $course['evaluation_message'] = $course->course_type_id == 1 ? 'There will be evaluation session before certification!' :
    //                      'There will be evaluation session where you can provide feedback for this course!';
    //             $course['live_sessions'] = count($course->liveSessions) ? $course->liveSessions()->get() : 'There is no live session planned yet!';
    //            // $course['live_sessions_login_message'] = 'Please login to take the course and join the live sessions!';
    //            // $course['discussion_login_message'] = 'Please login to view the discussion and chat with the course takers.';
    //             return response(['data' => $course], 200); 
    //         } else {
    //             return response(['data' => "This course is currently unpublished"], 504); 
    //         }
            
    //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    //         return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
    //     }
    // }
 
    public function takeCourse(Request $request, $courseId) 
    {
        try {
            $course = Course::findOrFail($courseId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
        if(!$course->is_published && $course->user_id != auth()->user()->id) {
            return response()->json(['code' => 403, 'message' => 'This course is currently unpublished'], 403);
        }

        if($this->repository->isAccessible($course, auth()->user()->type)) {
            $user = auth()->user();

            if (CourseLearnerRepository::isAlreadyTakenCourse($user, $course)) {
                return response()->json(['code' => 200, 'message' => 'You Already Take This Course'], 200);
            }
            
            $isOwner = auth()->user()->user_id == $course->user_id ? true : false;
            $completed = $course->order_type === 'flexible' ? $course->orders : $this->repository->getAllSectionsForCourse($course->id); //$this->repository->getAllSectionsForCourse($course->id);
            //dd($completed);exit;
            $route = route('courses.view-course', [$course]);
            $courseLearner = [
                'course_id' => $course->id,
                'user_id' => $user->id,
                'status' => 'not_started',
                'percentage'=> 0,
                'notify_count' => 0,
                'active' => 1,
                'completed' =>  $completed, 
                'last_visited' => $route
            ];
            $clrecord = $this->clRepo->saveRecord($courseLearner, null, true);
            $courseCreator = User::findOrFail($course->user_id);
            $courseEnrollArr = array(
                'courseTaker' => $user->name,
                'courseTakerEmail' => $user->email,               
                'userType'=> $user->type,
                'id' => $course->id,
                'courseTitle' => $course->title,
                'course'=> $course
            );
            if(!$isOwner) { // enrollment by other people only
                Notification::send($courseCreator, new CourseEnrollment($courseEnrollArr));
            }
            //return redirect(CourseRepository::goToLastLecture($user, $course));
             // redirect to the main section after taking course everytime!
            //$this->clRepo->updatelastVisited($course->id, $user->id, $route);           
            $clrecord['completed'] = $this->repository->convertCompletedArrayToAPISupportedFormat($clrecord['completed'], $course);
            
            return response()->json(['data' => $clrecord], 200); //redirect(CourseLearnerRepository::goToLastSection($user->id, $course));
        }
        return response()->json(['error' => 'invalid accessible right'], 403);
    }

    public function cancelCourse(Request $request, $courseId)
    {
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        try {
            $course = Course::findOrFail($courseId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
        if($this->repository->isAccessible($course, auth()->user()->type) || 
            CourseLearnerRepository::isAlreadyTakenCourse(auth()->user(), $course)) {
            $user = auth()->user();
            $courseLearner = $this->clRepo->getCourseLearnerData($courseId, auth()->user()->id);
            if($courseLearner->percentage === 100) {
                return  response()->json(['error' => "Completed courses cannot be cancelled!"], 403);
            }
            $this->clRepo->cancelCourse($course->id, auth()->user()->id);
            return  response()->json(['data' => "Successfully cancelled"], 200);  //redirect(CourseLearnerRepository::goToLastSection($user->id, $course));
        }
        return response()->json(['error' => 'invalid accessible right']);
    }

    public function myCourses(Request $request)
    {
        //$courseCategories = CourseCategory::all();
        $categories = CourseCategory::getItemList(); 
        $user = auth()->user();
        $courses = $user->learningCourses; //->isPublished();
      
        //$courses = $courses->paginate(6);
        //$userLectures = $user->learningLectures;
        $statusAndPercent = [];  
        if(count($courses) > 0 ) {
            if($request->course_category) {
               // $courses = $courses->whereJsonContains('course_categories', $request->course_category);
                $coursesTemp = $courses;
                $courses = [];
                foreach($coursesTemp as $course) { 
                    if(in_array($request->course_category, $course->course_categories)) { 
                        array_push($courses, $course);
                    }
                }
            }
    
            if($request->progress) {
                $coursesTemp = $courses;
                $courses = [];
                foreach($coursesTemp as $course) { 
                    if($course->pivot['status'] == $request->progress) {
                        array_push($courses, $course);
                    }
                }
            }
    
            if($request->sort_by) {
                $col = array_column( $courses->toArray(), "title" );
                array_multisort( $col, SORT_ASC, $courses->toArray() );
            }

            if(count($courses) > 0 ) {
                foreach($courses as $key => $course) { 
                    $courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
                    $course['title'] = strip_tags($course['title']);
                    $course['description'] = strip_tags($course['description']);
                    unset($course['objective']);
                    unset($course['learning_outcome']);
                    unset($course['orders']);
                    $course['course_level'] = Course::LEVELS[$course->course_level_id];
                    $course['cover_image'] = env('APP_URL').get_course_cover_image($course);
                    if($courseLearner) { 
                        array_push($statusAndPercent, ['status' => $courseLearner->status, 'percentage' => $courseLearner->percentage ]);
                    } else {  
                        array_push($statusAndPercent, ['status' => null, 'percentage' => null ]);
                    }               
                }
                $data = [
                    'courses' => $courses,
                    'categories' => $categories, 
                    'statusAndPercent' => $statusAndPercent
                ];
                return response()->json(['data' => $data], 200); 
            } else {
                return response()->json(['data' => 'No Records'], 200);
            }
        } else {
            return response()->json(['data' => 'No Records'], 200);
        }
        
    }

    public function viewCourseIntro(Request $request, $courseId)
    {  
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        try {
            $course = Course::findOrFail($courseId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }

        if(!$course->is_published && $course->user_id != auth()->user()->id) {
            return response()->json(['data' => 'This course is currently unpublished'], 403);
        }

        $user = auth()->user();
        
        $currentSection = null; // $lecture;
        $previousSection = null; //$course->lectures()->orderBy('id', 'desc')->where('id', '<', $currentSection->id)->first();
        $nextSection = $course->lectures()->orderBy('id')->first();
  
        $lectures = $course->lectures()->orderBy('id')->get();
        //$userLectures = $user->learningLectures;
        $downloadOption = $course->downloadable_option;
        $courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
        //$completed = $courseLearner->completed;
      //  $status = $courseLearner->status; 
       // $percentage = $courseLearner->percentage;
        $route = route('courses.view-course', [$course]);
        $this->clRepo->updatelastVisited($course->id, $user->id, $route);
        $course['cover_image'] = env('APP_URL').get_course_cover_image($course);
        $course['title'] = strip_tags($course['title']);
        $courseLearner['completed'] =  $this->repository->convertCompletedArrayToAPISupportedFormat($courseLearner['completed'], $course);
        
        $data = [
            'course' => $course,
            //'nextSection' => $nextSection,
            //'previousSection' => $previousSection,
           // 'lectures' => $lectures,
            //'downloadOption' => $downloadOption, // can get from course
            // 'completed' => $completed, // can get from courseLearner
            // 'status' => $status,
            // 'percentage' => $percentage,
            'course_learner' => $courseLearner
        ];      
        return response()->json(['data' =>  $data], 200);
    }

    public function downloadCourse(Request $request, $courseId)
    {
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        try {
            $course = Course::findOrFail($courseId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }

        if(!$course->is_published) {
            return response()->json(['code' => 404, 'message' => 'This course is currently unpublished'], 403);
        }
        
        $downloadPath = null;
        $tempPath = $course->getMedia('course_resource_file')->first()->getPath();
        $temp = $tempPath ? explode('/public', $tempPath) : null;
        if($temp && count($temp) == 2) {
            $downloadPath = env('APP_URL').'/storage'.$temp[1];
        }
        if( ( $course->getMedia('course_resource_file')->first() ) && file_exists( $course->getMedia('course_resource_file')->first()->getPath() ) ) {
            return response()->json(['data' => ['file_path' => $downloadPath ] ], 200);
        }

        return response()->json(['code' => 404, 'message' =>  'file not found'], 404);
    }

    public function updateCompletion(Request $request, $courseId)
    {
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        try {
            $course = Course::findOrFail($courseId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'current_section' => 'required|string',
            'redirect' => ['required', Rule::in(['previous', 'next']) ]
		]);
		if ($validator->fails()) {
			return response(['errors'=> $validator->errors()->all()], 422);
		}
        $findValue = $request->all()['current_section'];
        if (strpos($findValue, 'assessment_') !== false ) {
            return response()->json(['error' => 'Cannot mark assessment as completed by using this API!' ]);
        }
        if (strpos($findValue, 'assignment_') !== false ) {
            return response()->json(['error' => 'Cannot mark assignment as completed by using this API!' ]);
        }
       
        $redirect = $request->all()['redirect'];
        $userId = auth()->user()->id;
        $courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
        $completed = $courseLearner->completed;

        $redirectTo = $this->clRepo->getSectionToRedirect($completed, $findValue, $redirect);
        if (strpos($findValue, '_overview') !== false ) { // if it's a lecture, need to update the lect for web and overview for mobile!!
            $realFindValue = explode('_overview', $findValue); 
            $redirectTo = $this->clRepo->getSectionToRedirect($completed, $realFindValue[0], $redirect);
        }
        
        if($redirectTo == -1 || strpos($redirectTo, "assessment_") !== false) { // last section or assessment
           
            if (( strpos($findValue, 'lq_') !== false ) || 
                ( strpos($findValue, 'quiz_') !== false )) {  // for -1 cases (last section is not assessment)
                $temp = explode('_', $findValue);
                $quiz = Quiz::where('id', $temp[1])->first(); 
                if ($quiz && $quiz->type == 'long_question') {
                    $longAnswer = $quiz->questions[0]->long_answer;                   
                    return $this->performLongAnswerLogicAndReturn($request, $quiz, $longAnswer, $redirectTo, $courseLearner, $findValue, $course);                
                } 
            }
            //dd($findValue);exit;
            $setCompleted = $this->clRepo->performCompletionLogic($courseId, $userId, $findValue, true);// set completed to current section if it is not la       
            if($course->order_type === 'default') {
                $completedMobileFormat = $this->repository->modifyCompletedToSupportOverview($setCompleted);
            }
            $courseLearner->completed = $completedMobileFormat;  
            
            if($course->course_type_id == 1 ) {
                if($course->assessmentQuestionAnswers) { //has assessment
                    if(CourseLearnerRepository::isReadyToAssess($setCompleted) ) { 
                        $firstAssessment = $this->clRepo->getFirstAssessment($setCompleted);                   
                        $redirecToObj = new stdClass(); 
                        $redirecToObj->key = $firstAssessment;
                        $redirecToObj->isCompleted = $this->repository->getIsCompletedFromValue($firstAssessment, $setCompleted); 
                        $redirecToObj->id = CourseRepository::getIdFromValue($firstAssessment, $course);
                        $redirecToObj->type = $this->repository->getTypeFromValue($firstAssessment, $course);
                        $redirecToObj->title = CourseRepository::getTitleFromValue($firstAssessment, $course);
                       
                        return response()->json(['message' => 'Updated the current section!', 'redirect_to' => $redirecToObj, //$redirectTo, 
                                         'data' => $courseLearner], 200);     
                    } else {
     
                        return response()->json(['message' => 'Updated the current section!', 'redirect_to' => null, //$redirectTo, 
                                         'data' => $courseLearner], 200);   
                    }
                } else {
                    if(CourseLearnerRepository::isReadyToEvaluate($completed) ) {
                        $redirecToObj = new stdClass(); 
                        $redirecToObj->key = 'evaluation';
                        $redirecToObj->isCompleted = 0; 
                        $redirecToObj->id = null;
                        $redirecToObj->type = 'evaluation';
                        $redirecToObj->title = 'Course Evaluations';
                        return response()->json(['message' => 'Updated the current section!', 'redirect_to' => $redirecToObj, //$redirectTo, 
                                         'data' => $courseLearner], 200);  
                    } else {
                        return response()->json(['message' => 'Updated the current section!', 'redirect_to' => null, //$redirectTo, 
                        'data' => $courseLearner], 200); 
                    }
                }
            } else {
                //return evaluation
                if(CourseLearnerRepository::isReadyToEvaluate($completed) ) {
                    $redirecToObj = new stdClass(); 
                    $redirecToObj->key = 'evaluation';
                    $redirecToObj->isCompleted = 0; 
                    $redirecToObj->id = null;
                    $redirecToObj->type = 'evaluation';
                    $redirecToObj->title = 'Course Evaluations';
                    return response()->json(['message' => 'Updated the current section!', 'redirect_to' => $redirecToObj, //$redirectTo, 
                                     'data' => $courseLearner], 200);  
                } else {
                    return response()->json(['message' => 'Updated the current section!', 'redirect_to' => null, //$redirectTo, 
                    'data' => $courseLearner], 200); 
                }
            }
            return response()->json(['error' => 'The path provided for the current section does not exist '.$redirect ]);
        }

        $redirecToObj = new stdClass(); 
        $redirecToObj->key = $redirectTo;
        $redirecToObj->isCompleted = $this->repository->getIsCompletedFromValue($redirectTo, $completed); 
        $redirecToObj->id = CourseRepository::getIdFromValue($redirectTo, $course);
        $redirecToObj->type = $this->repository->getTypeFromValue($redirectTo, $course);
        $redirecToObj->title = CourseRepository::getTitleFromValue($redirectTo, $course);
        if (($course->order_type === 'default') && (strpos($findValue, 'lect_') !== false) && (strpos($findValue, '_overview') == false) ) {
            $redirecToObj->key = $findValue."_overview";
            $redirecToObj->isCompleted = $this->repository->getIsCompletedFromValue($findValue, $completed); 
            $redirecToObj->id = $findValue."_overview";
            $redirecToObj->type = "overview";
            $redirecToObj->title = "Overview";
        }
       
        if (( strpos($findValue, 'lq_') !== false ) || 
            ( strpos($findValue, 'quiz_') !== false )) { 
            $temp = explode('_', $findValue);
            $quiz = Quiz::where('id', $temp[1])->first(); 
            if($quiz && $quiz->type == 'long_question') {
                $longAnswer = $quiz->questions[0]->long_answer;
                return $this->performLongAnswerLogicAndReturn($request, $quiz, $longAnswer, $redirectTo, $courseLearner, $findValue, $course);                
            }
        }
        
        if (strpos($findValue, '_overview') !== false ) {
            $realFindValue = explode('_overview', $findValue); 
            $findValue = $realFindValue[0];
        }
        if( $updatedCompleted = $this->clRepo->performCompletionLogic($courseId, $userId, $findValue, true) ) {
            //dd($updatedCompleted);exit;
            //decide if the whole lecture section is completed or not here even if the current section is not lecture 
               $completedMobileFormat = $course->order_type === 'default' ? $this->repository->modifyCompletedToSupportOverview($updatedCompleted) : $updatedCompleted;
               $courseLearner->completed = $completedMobileFormat;          
            return response()->json(['message' => 'Updated the current section!', 'redirect_to' => $redirecToObj, //$redirectTo, 
                                         'data' => $courseLearner], 200);                                
        } else {
            return response()->json(['error' => 'error occured while updating!' ]);
        }
    }

    protected function performLongAnswerLogicAndReturn($request, $quiz, $longAnswer, $redirectTo, $courseLearner, $findValue, $course)
    {
        //validation for answers
        app()->setLocale('en');
        $validator = Validator::make($request->all(), [
            'answers' => 'required|array',
		]);
		if ($validator->fails()) {
			return response(['errors'=> $validator->errors()->all()], 422);
		}
        $laUser = auth()->user()->long_answer_user->where('long_answer_id', $longAnswer->id)->first();
        if($laUser) {
            $laUser->update([
                'submitted_answer' => $request->input('answers')
            ]);
        } else {
            $laUser = LongAnswerUser::query()->create([
                'long_answer_id' => $longAnswer->id,
                'user_id' => auth()->user()->id,
                'submitted_answer' => $request->input('answers'),
                'status' => 'submitted'
            ]);
        }
        $redirecToObj = new stdClass(); 
        $redirecToObj->key = $redirectTo;
        $redirecToObj->isCompleted = $this->repository->getIsCompletedFromValue($redirectTo, $courseLearner->completed); 
        $redirecToObj->id = CourseRepository::getIdFromValue($redirectTo, $course);
        $redirecToObj->type = $this->repository->getTypeFromValue($redirectTo, $course);
        $redirecToObj->title = CourseRepository::getTitleFromValue($redirectTo, $course);
        Notification::send( User::query()->where('id', $course->user_id)->first(), new submitLongAnswer($laUser, $course->id) );
        if($longAnswer->passing_option == 'after_providing_answer') {
            if( $this->clRepo->performCompletionLogic($course->id, auth()->user()->id, $findValue, true) ) {          
                return response()->json(['message' => 'Your answer was successfully submitted and updated the completion status', 
                                             'redirect_to' => $redirecToObj, //$redirectTo, 
                                             'data' => $courseLearner], 200);                                
            } else {
                return response()->json(['error' => 'error occured while updating!' ]);
            }
        } elseif ($longAnswer->passing_option == 'after_sending_feedback') {
            return response()->json(['message' => 'Your answer was successfully submitted and this section will be completed after the course owner provides you a feedback!', 'redirect_to' => $redirecToObj, //$redirectTo, 
                                             'data' => $courseLearner], 200);    
            //return response()->json(['code' => 200, 'data' => 'Your answer was successfully submitted and this section will be completed after the course owner provides you a feedback!' ], 200);
        } else { 
            return response()->json(['message' => 'Your answer was successfully submitted and this section will be completed after the course owner considers that your answer is satisfactory!', 'redirect_to' => $redirecToObj, //$redirectTo, 
                                             'data' => $courseLearner], 200);    
           // return response()->json(['code' => 200, 'data' => 'Your answer was successfully submitted and this section will be completed after the course owner considers that your answer is satisfactory!' ], 200);
        }
        
    }

    public function isReadyToGenerateCerti(Request $request, $courseId)
    {
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        $course = Course::where('id', $courseId)->first();
        if(!$course) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
        if( CourseLearnerRepository::isReadyToGenerateCerti($course) ) {
            return response()->json(['data' => true ], 200 );
        } else {
            return response()->json(['data' => false ], 200 );
        }
    }

    public function isReadyToEvaluate(Request $request, $courseId)
    { 
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        $course = Course::where('id', $courseId)->first();
        if(!$course) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
        if( CourseLearnerRepository::isReadyToEvaluate($course) ) { 
            return response()->json(['data' => true ], 200 );
        } else {
            return response()->json(['data' => false ], 200 );
        }
    }

    public function isReadyToAssess(Request $request, $courseId)
    { 
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        $course = Course::where('id', $courseId)->first();
        if(!$course) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
        $courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
        $completed = $courseLearner->completed;
        if( CourseLearnerRepository::isReadyToAssess($completed) ) { 
            return response()->json(['data' => true ], 200 ); 
        } else {
            return response()->json(['data' => false ], 200 );
        }
    }

    public function isThisSectionLast(Request $request, $courseId, $currentSection)
    { 
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        $course = Course::where('id', $courseId)->first();
        if(!$course) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
        $courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
        $completed = $courseLearner->completed;
        if( CourseLearnerRepository::isPartTheLastONe($completed, $currentSection) ) { 
            return response()->json(['data' => true ], 200 ); 
        } else {
            return response()->json(['data' => false ], 200 );
        }
    }

    public function getTitleFromValue(Request $request, $courseId, $currentSection)
    {
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        $course = Course::where('id', $courseId)->first();
        if(!$course) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
        $title = CourseRepository::getTitleFromValue($currentSection, $course);
        if($title) {
            return response()->json(['data' => $title ], 200 ); 
        } else {
            return response()->json(['error' => 'not found' ], 404 );
        }
    }

    public function checkIfCourseTaken(Request $request, $courseId) 
    {
        if($this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['data' => true ], 200 ); 
        } else {
            return response()->json(['data' => false ], 200 ); 
        }

    }

    public function generateCertificate(Request $request,$courseId)
    {   
       // $courseId = $request->all()['course_id'];
        $userId = auth()->user()->id;
        $userName = auth()->user()->name; //$request->all()['user_name'];
        $course = Course::findOrFail($courseId);
        //set_time_limit(00); //loaded slow coz of external css
        $certificate = $course->certificate;
        $data = [
            'title' => $certificate->title,
            'name' => $userName,
            'certify' => $certificate->certify_text,
            'completion' => $certificate->completion_text,
            'isPreview' => false,
            'today' => date("d/m/Y"),
            'logoText' => "Myanmar Teacher Platform"          
        ];       
        //$pdf = PDF::loadView('frontend.certificates.template_1_pdf', $data)->setPaper('a4', 'landscape');  
        $courseOwner = User::findOrFail($certificate->course->user_id);
        if($courseOwner->type == "teacher_educator") {
            $pdf = PDF::loadView('frontend.certificates.template_1_pilot_pdf', $data)->setPaper('a4', 'landscape');  
        } else {         
            $options = PDF::getDomPDF()->getOptions();
            $options->set('isFontSubsettingEnabled', true);
            $options->set('defaultFont', 'Helvetica');
            $pdf = PDF::setPaper('a4', 'landscape');
            $pdf->getDomPDF()->setOptions($options);
            $pdf = PDF::loadView('frontend.certificates.template_1_pdf_modified', $data)->setPaper('a4', 'landscape')
                    ->setOptions(['defaultFont' => 'cloisterblack', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);        
        }
        $this->clRepo->updateGenerateCertificate($courseId, $userId);
        //return $pdf->download($userName.'_Course Completetion Certificate.pdf');
        return response()->json(['file_name' => $userName."_".time().'_Course Completetion Certificate.pdf', 'base64_data' => base64_encode($pdf->stream()) ], 200 ); 
    }

}
