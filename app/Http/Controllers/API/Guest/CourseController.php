<?php

namespace App\Http\Controllers\API\Guest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CourseRepository;
use App\Repositories\CourseCategoryRepository;
use App\Repositories\CourseLevelRepository;
use App\Repositories\DiscussionMessageRepository;
use App\Repositories\CourseLearnerRepository;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\CourseLearner;
use App\Http\Resources\CourseResource;
use App\Models\LiveSessionUser;
use App\User;
use GuzzleHttp\Exception\GuzzleException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use GuzzleHttp\Client; 

class CourseController extends Controller
{
    protected $repository;
    protected $ccRepo;
    protected $clRepo;
    protected $disMesRepo;
    protected $courseLearnerRepo;
    public function __construct(CourseRepository $repository, CourseCategoryRepository $ccRepo, CourseLevelRepository $clRepo, 
        DiscussionMessageRepository $disMesRepo, CourseLearnerRepository $clRepository)
    {
        $this->repository = $repository;
        $this->ccRepo = $ccRepo;
        $this->clRepo = $clRepo;
        $this->disMesRepo = $disMesRepo;
        $this->courseLearnerRepo = $clRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)//NEED TO GUEST ROLE
    {  
        if( $request->header('Content-Language') ) {
            if(!setLanguageForSession($request->header('Content-Language'))) { // if language is set and it's not supported
                return response(['errors' => 'Provided language is not supported'], 404);
            }
            $lang = $request->header('Content-Language');
            if($token = $request->bearerToken()) {
                $courses = $this->repository->getAllPublishedCoursesByLanguage( auth()->user()->type, $lang);
            } else {
                $courses = $this->repository->getAllPublishedCoursesByLanguage(User::TYPE_GUEST, $lang);
            }
        } else { 
            return response(['data' => 'Language need to provide in the header'], 400);
        }
        if ($courses && count($courses)  > 0) {
            $list = [];
            $list = CourseResource::collection($courses);
            return response(['total' => count($list), 'data' => $list], 200); 
        } else {
            return response(['data' => 'There are no courses'], 404);
        }
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCoursesForHomePage(Request $request)
    {   
        // if(auth()->check()) {
        //     $currentUserType = auth()->user()->type;
        // } else {
        $currentUserType = 'guest';
        if(!setLanguageForSession($request->header('Content-Language'))) { // if language is set and it's not supported
            return response(['errors' => trans('Provided language is not supported')], 404);
        } 
        $lang = $request->header('Content-Language'); 
        try {
            $courses = $this->repository->getPublishedCoursesForHomeByLanguage($currentUserType, 8, $lang);
            return response()->json(['data' => $courses]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Resource Not Found'], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showSearchResults( Request $request )
    {   
        try {
            $searchWord = $request->input('search-word') ? $request->input('search-word') : '';
            $category = $request->input('category-id') ? $request->input('category-id') : null;
            $level = $request->input('level-id') ? $request->input('level-id') : null;
            $courses = Course::query()->where('is_published', 1)->with('privacies');
           
            if($level) {
                $courses = $courses->where('course_level_id', $level);
            }
           
            if($category) {  
                $courses = $courses->whereJsonContains('course_categories', $category);           
            }

            if($searchWord) {
                $courses = $courses->where('title', 'LIKE', '%'.$searchWord.'%'); 
            }

            if(auth()->check()) { //if($token = $request->bearerToken()) {
                $currentUserType = auth()->user()->type;
            } else {
                $currentUserType = 'guest';
            }
            $courseCollection = collect();

            foreach ($courses->latest()->get() as $course) { 
                $course['course_level'] = Course::LEVELS[$course->course_level_id];
                $course['cover_image'] = env('APP_URL').get_course_cover_image($course);
                foreach ($course->privacies as $privacy) {
                    if($privacy->user_type == $currentUserType ) {
                        $course->title = strip_tags($course->title);
                        $course->description = strip_tags($course->description);
                        $temp = [];
                        $categories = $this->ccRepo->getItems();
                        foreach($course->course_categories as $cc) {
                            array_push($temp, $categories[intval($cc)]);
                        }
                        $course->course_categories = $temp;
                        $courseCollection->push($course);
                        break;
                    }                      
                }
            }
            return response()->json(['data' => $courseCollection]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $courseId)
    {  
        try {
            $course = Course::findOrFail($courseId);
            $token = $request->bearerToken();
            if(!$token) {
                $course->view_count = $course->view_count + 1; // if a new user, then increase the count
                $course->save();
            } else {
                if ( ! auth()->check()) {
                    return response()->json(['message' => 'Bearer Token was expired. Please login again!'], 403);
                }
                $amICourseOwner = (isset(auth()->user()->id) && ( auth()->user()->id == $course->user_id)) ? true : false;
                $learner = CourseLearner::where('course_id', $courseId)->where('user_id',auth()->user()->id)->first();
                if(!$amICourseOwner && !$learner) { // no need to increase the count for learner & course owner                      
                    $course->view_count = $course->view_count + 1; // if a new user, then increase the count
                    $course->update();
                } 
            }
            $lectures = []; $temp = []; $courseQuizzes = [];
            $course->title = strip_tags($course->title);
            $course->description = $course->description;
            $course->objective = $course->objective;
            $course->learning_outcome = $course->learning_outcome;
            if ($course->is_published) {
                foreach($course->lectures as $lecture) {
                    $temp['lecture_id'] = $lecture->id;
                    $temp['lecture_title'] = strip_tags($lecture->lecture_title);
                    $temp['lecture_quiz'] =  [];
                    $temp2 =[];
                    if($course->quizzes && count($course->quizzes) > 0) {
                        foreach($course->quizzes()->where('lecture_id', $lecture->id)->get() as $quiz) {
                            $temp2['quiz_title'] = strip_tags($quiz->title);
                            $temp2['quiz_id'] = $quiz->id;
                            $temp2['no_of_quiz_questions'] = $quiz->questions ? count($quiz->questions) : 0;
                            array_push($temp['lecture_quiz'], $temp2);
                        }                      
                    }
                    array_push($lectures, $temp);
                }
                foreach($course->quizzes()->where('course_id', $courseId)->get() as $quiz) {
                    if($quiz->lecture_id == null) {
                        $temp3['quiz_id'] = $quiz->id;
                        $temp3['quiz_title'] = strip_tags($quiz->title);
                        $temp3['no_of_quiz_questions'] = $quiz->questions ? count($quiz->questions) : 0;
                        array_push($courseQuizzes, $temp3);
                    }                          
                }
                $ratings = $course->ratingReviews;
                $finalRating = 0; $ratingCount = 0;
                if(count($ratings) > 1) {
                    $sum = 0;
                    for($i=0; $i < count($ratings); $i++) {
                        $sum += $ratings[$i]->rating;
                        $ratingCount++;
                    }
                    $finalRating = ceil($sum/$ratingCount);
                } else {
                    $finalRating = isset($ratings[0]) ? $ratings[0]->rating : $finalRating;
                    $ratingCount = isset($ratings[0]) ? 1 : 0;
                }
                $amIParticipatedBefore = false;
                $discussion = null;
                $messages = [];
                if($token) {                   
                    $discussion = $course->discussion; //dd($discussion->id);exit;                                     
                    if($discussion && $discussion->count() > 0) {
                        $messages = DiscussionMessageRepository::getMessagesByDiscussionId($discussion->id);
                        for($i = 0; $i < sizeof($messages); $i++) {
                            $user = User::getUserById($messages[$i]['user_id']);
                            $messages[$i]['username'] = $user->username;
                            $messages[$i]['avatar'] = $user->getThumbnailPath();
                            if( isset(auth()->user()->id) && ( auth()->user()->id == $user->id) ) {
                                    $amIParticipatedBefore = true;
                            }
                        }
                    } 
                }
                unset($course->lectures);
                unset($course->quizzes);
                $course['cover_image'] = env('APP_URL').$course->getMediumPath();
                $course['cover_image_thumb'] = env('APP_URL').$course->getThumbnailPath();
                $course['cover_image_large'] = env('APP_URL').$course->getImagePath();
                $course['course_type'] = $course->getCourseType($course->course_type_id)->name;
                $course['enrollments'] = $course->learners->count() ? $course->learners->count() : 0;
                $course['total_lectures'] = $course->lectures->count() ? $course->lectures->count() : 0;     
                $course['total_quizzes'] = $course->quizzes->count() ? $course->quizzes->count() : 0;         
                $course['views_count'] = $course->view_count + 1;   
                $course['no_of_people_who_rated'] = $ratingCount;
                $course['final_rating'] = $finalRating;
                $course['can_share_on_social'] = false;
                $course['course_learner']    = null;
                unset($course['learners']);
                $course['course_live_sessions'] = null;
                $course['course_learning_activites'] = null;
                $course['course_quizzes'] = null;
               // $course['course_quizzes']['questions'] = [];
                $course['course_summaries'] = null;
                if($token) { 

                    $course['can_share_on_social'] = true;
                    $course['can_rate_the_course'] = auth()->check() && $course->allow_feedback;                 
                    $isAllLearnersAllowed = $discussion ? $discussion->allow_learners: null;
                    $isCourseTakersAllowed = $discussion ? $discussion->allow_takers	: null;
                    $lastDiscussedTime = $discussion && count($messages) ? count($messages) - 1 : null;
                    $course['can_participate_in_discussion'] = ($isCourseTakersAllowed && CourseLearnerRepository::isAlreadyTakenCourse(auth()->user(), $course))
                                                            || $isAllLearnersAllowed || $amICourseOwner;
                    $course['is_user_taken_this_course'] = $this->courseLearnerRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId) ? true : false;                                      
                    $course['ratingReviews']  = $course->ratingReviews ?  $course->ratingReviews : null;    
                    $course['discussion']  = $discussion && $discussion->count() > 0 ?  $discussion : null;
                    $course['messages']  = count($messages) > 0 ?  $messages : []; 
                    $course['amIParticipatedBefore'] = $amIParticipatedBefore;    
                    $course['lastDiscussedTime'] = $lastDiscussedTime;                      
                    
                    if($learner) { 
                        $learner->completed = $this->repository->convertCompletedArrayToAPISupportedFormat($learner->completed, $course);
                        $course['course_learner']    = $learner;
                    } 
                    
                    //$course['course_live_sessions'] = $course->liveSessions->where('lecture_id',null);
                    $lsArr = [];
                    foreach($course->liveSessions as $ls) {
                        if($ls->lecture_id === null) {
                            array_push($lsArr, $ls);
                        }
                    }
                    foreach($lsArr as $idx => $session) {         
                        $liveSessionUser = LiveSessionUser::where('session_id', $session->id)
                                            ->where('user_id', auth()->user()->id)->first();       
                        $session['is_user_registered'] = $liveSessionUser ? true : false;                                               
                    }
                    $course['course_live_sessions'] = $lsArr;
                    $course['course_learning_activites'] = $course->learningActivities;
                    $laArr = [];
                    foreach($course->learningActivities as $la) {
                        if($la->lecture_id === null) {
                            array_push($laArr, $la);
                        }
                    }
                    $course['course_learning_activites'] = $laArr;
                    if($course->quizzes && count($course->quizzes) > 0) {
                        $temp = [];
                        foreach($course->quizzes as $idx => $cq) {
                            if($cq->lecture_id === null) {
                               array_push($temp, $cq);
                            }                          
                        }
                        foreach($temp as $idx => $cq) {                
                            $cq['questions'] = Question::where('quiz_id',$cq->id)->get();                                                
                        }
                    } 
                    $course['course_quizzes_data'] = $temp;
                    $lsumArr = [];
                    foreach($course->summaries as $ls) {
                        if($ls->lecture_id === null) {
                            array_push($lsumArr, $ls);
                        }
                    }
                    $course['course_summaries'] = $lsumArr;
                    foreach($course->liveSessions as $cl) {
                        $liveSessionUser = LiveSessionUser::where('session_id', $cl->id)
                                            ->where('user_id', auth()->user()->id)->first();
                        $cl['is_user_registered'] = $liveSessionUser ? true : false;  
                    }                   
                } else {
                    
                    
                    $course['can_rate_the_course'] = false;
                    $course['can_participate_in_discussion'] = false;
                    $course['is_user_taken_this_course'] = false;
                    $course['ratingReviews']  = $course->ratingReviews ?  $course->ratingReviews : null;    
                    $course['discussion']  = null;
                    $course['messages']  = count($messages) > 0 ?  $messages : []; 
                    $course['amIParticipatedBefore'] = false;    
                    $course['lastDiscussedTime'] = null; 
                }
                if( isset($course->rating_reviews) ) { 
                    unset($course->rating_reviews);
                }
                $course['course_lectures'] =count($lectures)  ? $lectures : [];
                $course['course_quizzes'] = count($courseQuizzes) ? $courseQuizzes : [];
                $course['evaluation_message'] = $course->course_type_id == 1 ? 'There will be evaluation session before certification!' :
                         'There will be evaluation session where you can provide feedback for this course!';
                
                $course['live_sessions'] = count($course->liveSessions) ? $course->liveSessions : [];
                $course['live_sessions_login_message'] = 'Please login to take the course and join the live sessions!';
                $course['discussion_login_message'] = 'Please login to view the discussion and chat with the course takers.';
                $course['base_url'] = route('courses.show', [$course]);
                unset($course->liveSessions);
                    unset($course->learningActivities);
                    unset($course->quizzes);
                    unset($course->summaries);
                return response(['data' => $course], 200); 
            } else {
                return response(['data' => "This course is currently unpublished"], 504); 
            }
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
    }

    public function getRelatedResources(Request $request, $courseId)
    {
        try {
            $course = Course::findOrFail($courseId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
        // $categoriesAndKeywords = config('cms.categories-and-keywords');
        // $keyword = $categoriesAndKeywords[$course->course_categories[0]];
        // $client = new \GuzzleHttp\Client(); 
        // $res = $client->get( env('ELIBRARY_ENDPOINT')."/search/".$keyword );
        $categoriesAndKeywords = config('cms.categories-and-keywords'); 

            $keywords = $course->related_resources ? changeToRelatedResourceGetRequestFormat($course->related_resources) : 
                ["keywords" => [$categoriesAndKeywords[$course->course_categories[0]]] ];  
              //dd($keywords);exit;       
            $client = new \GuzzleHttp\Client();       //dd($client);exit;
            try {
                $res = $client->get( env('ELIBRARY_ENDPOINT')."/search/resources", [
                    'query' => $keywords
                ]);          
                $relatedResources = $res && (int)$res->getStatusCode() == 200 ? json_decode($res->getBody()->getContents()) : null; 
            }  catch (ClientErrorResponseException $e) {
                $response = $e->getResponse(); dd($response);exit;
                $responseBodyAsString = $response->getBody()->getContents();
            }   
        return response()->json( ['data' => $relatedResources], $res->getStatusCode());
    }

    public function getLastUpdatedOfACourse(Request $request, $courseId)
    {
        $course = Course::where('id',$courseId)->first();
        if(!$course) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
        $lastUpdated = $course->updated_at;
        $section = 'course';
        foreach($course->lectures as $cl) {
            if($cl->updated_at > $lastUpdated) {
                $lastUpdated = $cl->updated_at;
                $section = 'lecture';
            }
        }
        foreach($course->quizzes as $cq) {
            if($cq->updated_at > $lastUpdated) {
                $lastUpdated = $cq->updated_at;
                $section = 'quiz';
            }
            foreach($cq->questions as $cqq) {
                if($cqq->updated_at > $lastUpdated) {
                    $lastUpdated = $cqq->updated_at;
                    $section = 'quiz_question';
                }
            }
        }
        foreach($course->learningActivities as $cla) { 
            if($cla->updated_at > $lastUpdated) {
                $lastUpdated = $cla->updated_at;
                $section = 'learning_activities';
            }
        }
        foreach($course->liveSessions as $cls) {
            if($cls->updated_at > $lastUpdated) {
                $lastUpdated = $cls->updated_at;
                $section = 'live_session';
            }
        }
        foreach($course->summaries as $sum) {
            if($sum->updated_at > $lastUpdated) {
                $lastUpdated = $sum->updated_at;
                $section = 'summary';
            }
        }
        return response(['last_updated' => $lastUpdated, 'section_updated' => $section], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCourseCategories(Request $request)
    {  
        if( $request->header('Content-Language') ) { 
            if(!setLanguageForSession($request->header('Content-Language'))) { // if language is set and it's not supported
                return response(['errors' => 'Provided language is not supported'], 404);
            }
            $lang = $request->header('Content-Language');
            $cc = $this->ccRepo->getCourseCategories($lang);
            $list = [];
            foreach ($cc as $key => $value) {
                $list[] = ['id' => $key, 'name' => $value];
            }
            return response()->json(['data' => $list], 200);
        } else { 
            return response(['errors' => 'Content Language is missing in the header'], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCourseLevels(Request $request)
    {  
        if( $request->header('Content-Language') ) { 
            if(!setLanguageForSession($request->header('Content-Language'))) { // if language is set and it's not supported
                return response(['errors' => 'Provided language is not supported'], 404);
            }
            $lang = $request->header('Content-Language');
            $cc = $this->clRepo->getCourseLevels($lang);
            $list = [];
            foreach ($cc as $key => $value) {
                $list[] = ['id' => $key, 'name' => $value];
            }
            return response()->json(['data' => $list], 200);
        } else { 
            return response(['errors' => 'Content Language is missing in the header'], 400);
        }
    }

}
