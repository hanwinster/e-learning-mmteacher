<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CoursePrivacy;
use App\Models\CourseEvaluation;
use App\Models\Lecture;
use App\Models\Discussion;
use App\Repositories\CourseRepository;
use App\Repositories\DiscussionMessageRepository;
use App\Repositories\CourseLearnerRepository;
use App\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use mysql_xdevapi\Collection;
use Spatie\MediaLibrary\Models\Media;
use PDF;
use App\Notifications\CourseEnrollment;
use Notification;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client; 

class CourseController extends Controller
{
    private $repository;
    private $disMesRepo;
    private $clRepo;
    private $currentUserType;

    public function __construct(CourseRepository $repository, DiscussionMessageRepository $disMesRepo,
    CourseLearnerRepository $clRepository)
    {
        $this->repository = $repository;
        $this->disMesRepo = $disMesRepo;
        $this->clRepo = $clRepository;
        $this->middleware(function($request, $next)  {
            if(auth()->check()) {
                $this->currentUserType = auth()->user()->type;
            } else {
                $this->currentUserType = 'guest';
            }
            return $next($request);
        });
    }

    public function index()
    {
        $levels = Course::LEVELS;
        $courseCategories = CourseCategory::all();

        $user_type = currentUserType();
        $selectedCategory = null;
        return view('frontend.courses.index', compact('levels', 'courseCategories','selectedCategory')); //, 'how_to_slug'));
    }
    
    public function browse()
    {   //echo "here";exit;
        $levels = Course::LEVELS;
        $courseCategories = CourseCategory::all();
        $selectedCategory = null;
        return view('frontend.courses.index', compact('levels', 'courseCategories','selectedCategory'));
    }

    public function browseByCategory(Request $request)
    {   
        $levels = Course::LEVELS;
        $courseCategories = CourseCategory::all();
        $selectedCategory = $request->has('category') ? $request->input('category') : ""; 
        return view('frontend.courses.index', compact('levels', 'courseCategories', 'selectedCategory'));
    }

    public function show(Course $course)
    { 
        if(auth()->user()) { //login
            if( !$course->is_published && 
                ($course->user_id !== auth()->user()->id) &&
                    ($course->collaborators && !in_array(auth()->user()->id, $course->collaborators) )) {  //allow access for the course creator before publishing
                return redirect()->back()->with('message', 'This course is currently unpublished');
            }
        } else {
            if( !$course->is_published ) {  
                return redirect()->back()->with('message', 'This course is currently unpublished');
            }
        }
        $amICourseOwner = (isset(auth()->user()->id) && ( auth()->user()->id == $course->user_id)) ? true : false;
        $ratingReviews = $course->ratingReviews;
        if($this->repository->isAccessible($course, $this->currentUserType)) {
            $lectures = $course->lectures;           
            $discussion = $course->discussion; //discussionRepository->getByCourse(request(), $id)->first();          
           $messages = [];
           $amIParticipatedBefore = false;
           if(isset($discussion) && $discussion->count() > 0) {
                $messages = $this->disMesRepo->getMessagesByDiscussionId($discussion->id);
               for($i = 0; $i < sizeof($messages); $i++) {
                   $user = User::getUserById($messages[$i]['user_id']);
                   $messages[$i]['username'] = $user->username;
                   $messages[$i]['avatar'] = $user->getThumbnailPath();
                   if( isset(auth()->user()->id) && ( auth()->user()->id == $user->id) ) {
                        $amIParticipatedBefore = true;
                   }
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
            $authUserId = auth()->check() ? auth()->user()->id : null;
            $liveSessions = $course->liveSessions()->get();           
            $course->view_count = $course->view_count + 1;
            $course->save();
            $categoriesAndKeywords = config('cms.categories-and-keywords');

            $keywords = $course->related_resources ? changeToRelatedResourceGetRequestFormat($course->related_resources) : 
                ["keywords" => [$categoriesAndKeywords[$course->course_categories[0]]] ];  
              //  dd($keywords);exit;       
            $client = new \GuzzleHttp\Client();    
            $relatedResources = null;
            try {
                $res = $client->get( env('ELIBRARY_ENDPOINT')."/search/resources", [
                    'query' => $keywords
                ]);          
                $relatedResources = $res && (int)$res->getStatusCode() == 200 ? json_decode($res->getBody()->getContents()) : null; 
            } catch (GuzzleHttp\Exception\ClientException $e) {
                $response = $e->getResponse();
                $responseBodyAsString = $response->getBody()->getContents();
            }       
                    
            return view('frontend.courses.show', compact('course', 'lectures', 'finalRating', 'ratingCount', 'relatedResources',
                        'amICourseOwner', 'ratingReviews', 'discussion','messages', 'liveSessions','authUserId', 'amIParticipatedBefore')); //, 'lecturesMedias'));
        }
        //return \response()->json(['error' => 'invalid accessible right']);
        abort(403,trans('Invalid Accessible Right'));
    }

    public function filterCourses(Request $request)
    {   
        $currentLang = config('app.locale');
        // if ($currentLang=='en') {
        //     $courses = Course::query()->where('is_published', true)
        //                             ->where('lang', 'en')
        //                             ->Orwhere('lang', 'both')
        //                             ->where('approval_status', 1)->with('privacies');
        // } else {
        //     $courses = Course::query()->where('is_published', true)
        //                             ->where('lang', 'my-MM')
        //                             ->Orwhere('lang', 'both')
        //                             ->where('approval_status', 1)->with('privacies');
        // }
        $courses = Course::query()->where('is_published', true)
                 ->where('approval_status', 1)->with('privacies');
        $categories = CourseCategory::all()->pluck('name','id');
        // if ($currentLang=='en') {
        //     $courses = $courses->where('lang', 'en')->Orwhere('lang', 'both');
        // } else {
        //     $courses = $courses->where('lang', 'my-MM')->Orwhere('lang', 'both');
        // }
       // dd($request->courseCategories);exit;
        if($request->courseCategories) {
            $courses = $courses->whereJsonContains('course_categories', $request->courseCategories);
        }

        if($request->courseLevels) {
            $courses = $courses->whereIn('course_level_id', $request->courseLevels);
        }

        if($request->keyword) {  // echo "it is correct to be here for keyword ".$request->keyword."\n";
            $courses = $courses->where('title', 'LIKE', '%'.$request->keyword.'%');            
        }
       
        $courseCollection = collect();
        foreach ($courses->latest()->get() as $course) { //dd($course->course_category);exit;
            
            foreach ($course->privacies as $privacy) {
                // if($request->keyword) { 
                //     if($privacy->user_type == $this->currentUserType && strpos($course->title, $request->keyword) != false  ) {
                //         $courseCollection->push($course);
                //         break;
                //     }
                // } else {
                    if($privacy->user_type == $this->currentUserType ) {
                        $courseCollection->push($course);
                        break;
                    }
                //}
            }
        }
        
                         
        $courseCollectionArray = CourseResource::collection($this->collectionPaginator($courseCollection, 5));
        return $courseCollectionArray;
    }

    public function takeCourse(Course $course)
    {      
        if(!$course->is_published && $course->user_id != auth()->user()->id) {
            return redirect()->back()->with('message', trans('This course is currently unpublished'));
        }

        if($this->repository->isAccessible($course, $this->currentUserType)) {
            $user = auth()->user();

            if (CourseLearnerRepository::isAlreadyTakenCourse($user, $course)) {
                return redirect()->route('courses.show', $course)->with('message', trans('You Already Take This Course'));
            }
            // $user->learningCourses()->attach($course->id, [
            //     'status' => 'not_started'
            // ]);
            //$isOwner = auth()->user()->user_id == $course->user_id ? true : false;
            $completed = $course->order_type === 'flexible' ? $course->orders : $this->repository->getAllSectionsForCourse($course->id);
            //dd($completed);exit;
            $courseLearner = [
                'course_id' => $course->id,
                'user_id' => $user->id,
                'status' => 'not_started',
                'percentage'=> 0,
                'notify_count' => 0,
                'active' => 1,
                'completed' =>  $completed, 
                'last_visited' => ""
            ];
            $courseLearner = $this->clRepo->saveRecord($courseLearner, null);
            $courseCreator = User::findOrFail($course->user_id);
            $courseEnrollArr = array(
                'courseTaker' => $user->name,
                'courseTakerEmail' => $user->email,               
                'userType'=> $user->type,
                'id' => $course->id,
                'courseTitle' => $course->title,
                'course'=> $course
            );
            if($course->user_id != auth()->user()->id) { // enrollment by other people only
                Notification::send($courseCreator, new CourseEnrollment($courseEnrollArr));
            }
            //return redirect(CourseRepository::goToLastLecture($user, $course));
            if($course->order_type == 'default')  {
                $route = route('courses.view-course', [$course]); // redirect to the main section after taking course everytime!
            } else {
                $firstSection = array_keys($course->orders[0]);
                $route = CourseRepository::getRouteFromValue($firstSection[0]); // redirect to the main section after taking course everytime!
            }
            
            $this->clRepo->updatelastVisited($course->id, $user->id, $route);  
            return  redirect($route); //redirect(CourseLearnerRepository::goToLastSection($user->id, $course));
        }
        return response()->json(['error' => 'invalid accessible right']);
    }

    public function cancelCourse(Course $course)
    {
       
        if($this->repository->isAccessible($course, $this->currentUserType) || 
            CourseLearnerRepository::isAlreadyTakenCourse(auth()->user(), $course)) { // added this as we can't cancel course if it's not allowed for the user's role
            $user = auth()->user();
            $this->clRepo->cancelCourse($course->id, auth()->user()->id);
           // exit;
            
            $route = route('courses.my-courses'); // redirect to my courses page
            return  redirect($route); //redirect(CourseLearnerRepository::goToLastSection($user->id, $course));
        }
        return response()->json(['error' => 'invalid accessible right']);
    }

    public function myCourses(Request $request)
    {
        $courseCategories = CourseCategory::all();
        $categories = addTranslations(CourseCategory::getItemList()); 
        $user = auth()->user();
        $courses = $user->learningCourses(); //->isPublished();

        if($request->course_category) {
            $courses = $courses->whereJsonContains('course_categories', $request->course_category);
        }

        if($request->progress) {
            $courses = $courses->wherePivot('status', $request->progress);
        }

        if($request->sort_by) {
            $courses = $courses->orderBy($request->sort_by);
        }

        $courses = $courses->paginate(6);

        $userLectures = $user->learningLectures;
        $statusAndPercent = []; //dd(count($courses));exit;
        foreach($courses as $key => $course) { 
            $courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
            // if($key) { echo 'key is '.$key;
            //     //print_r($courseLearner);exit;
            // }
            if($courseLearner) { 
                array_push($statusAndPercent, ['status' => $courseLearner->status, 'percentage' => $courseLearner->percentage ]);
            } else {  
                array_push($statusAndPercent, ['status' => null, 'percentage' => null ]);
            }               
        }
    //dd($statusAndPercent);exit;
        return view('frontend.courses.my-courses', compact('courses', 'userLectures', 'courseCategories', 'categories', 'request', 'statusAndPercent'));
    }

    public function viewCourse(Course $course)
    {  
        if(!$course->is_published && $course->user_id != auth()->user()->id) {
            return redirect()->back()->with('message', 'This course is currently unpublished');
        }

        $user = auth()->user();
        if (!$course) {
            return redirect()->route('courses.my-courses')->with('message', 'This course is not taken!');
        }

        $currentSection = null; // $lecture;
        //$previousSection = null; //$course->lectures()->orderBy('id', 'desc')->where('id', '<', $currentSection->id)->first();
        
        //$nextSection = $course->lectures()->orderBy('id')->first();
        
        // $user->learningCourses()->updateExistingPivot($course->id, [
        //     'status' => $status
        // ]);    
        $lectures = $course->lectures()->orderBy('id')->get();
        $userLectures = $user->learningLectures;
        $downloadOption = $course->downloadable_option;
        $courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
        $completed = $courseLearner->completed;
        $status = $courseLearner->status; 
        $percentage = $courseLearner->percentage;
        $previousSection = $this->repository->getRouteFromValue($this->repository->getPrevSection('intro_'.$course->id, $completed));
        $nextSection = $this->repository->getRouteFromValue($this->repository->getNextSection('intro_'.$course->id, $completed));
        //dd($previousSection);exit;
        $route = route('courses.view-course', [$course]);
        $this->clRepo->updatelastVisited($course->id, $user->id, $route);      
        return view('frontend.courses.course-info-page',
            compact('course', 'currentSection', 'nextSection', 'previousSection','lectures',
                'userLectures', 'downloadOption', 'completed','status','percentage'
            ));
    }

    public function learnCourse(Lecture $lecture)
    {
        $courseId = $lecture->course_id;
        $course = Course::findOrFail($courseId);
        if(!$course->is_published && $course->user_id != auth()->user()->id) {
            return redirect()->back()->with('message', 'This course is currently unpublished');
        }

        $user = auth()->user();
        if (!$course) {
            return redirect()->route('courses.my-courses')->with('message', 'This course is not taken!');
        }

        // if (! $user->learningLectures->contains('id', $lecture->id)) {
        //     $user->learningLectures()->attach($lecture->id);
        // }

        $lectures = $course->lectures()->orderBy('id')->get();
        $lecturesMedias = Media::all()->where('model_type', Lecture::class);
        $currentSection = $lecture;
        
        // $course->lectures()->orderBy('id', 'desc')->where('id', '<', $currentSection->id)->first();
        //$nextSection = $course->lectures()->orderBy('id')->where('id', '>', $currentSection->id)->first();

        // $user->learningCourses()->updateExistingPivot($course->id, [
        //     'status' => $status
        // ]);    
        $userLectures = $user->learningLectures;
        $downloadOption = $course->downloadable_option;
        $courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
        $completed = $courseLearner->completed;
        $status = $courseLearner->status;
        $percentage = $courseLearner->percentage;
        $previousSection = $this->repository->getRouteFromValue($this->repository->getPrevSection('lect_'.$lecture->id, $completed));
        $nextSection = $this->repository->getRouteFromValue($this->repository->getNextSection('lect_'.$lecture->id, $completed));
        if($course->item_affect_certification == 0 && $this->clRepo->isPartTheLastONe($completed, 'lect_'.$lecture->id)) {
            $nextSection = route('courses.evaluation', [$course]);
        } 
        $route = route('courses.learn-course', [$lecture]);
        $this->clRepo->updatelastVisited($course->id, $user->id, $route);  
       
        return view('frontend.courses.course-lecture-page',
            compact('course', 'lectures', 'lecturesMedias', 'currentSection', 'nextSection', 'previousSection',
                'userLectures', 'downloadOption', 'completed','status','percentage'
            ));
    }

    public function downloadLecture(Lecture $lecture)
    {
        return response()->download($lecture->getMedia('lecture_attached_file')->first()->getPath(), $lecture->media->first()->file_name);
    }

    public function downloadCourse(Course $course)
    {
        if(!$course->is_published) {
            return redirect()->back()->with('message', 'This course is currently unpublished');
        }

        if( ( $course->getMedia('course_resource_file')->first() ) && file_exists( $course->getMedia('course_resource_file')->first()->getPath() ) ) {
            return response()->download($course->getMedia('course_resource_file')->first()->getPath(), $course->media()->first()->filename);
        }

        return redirect()->back()->with('error' , 'file not found');
    }

    private function collectionPaginator($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
        return (new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options))
            ->withPath(asset('/e-learning/courses'));
    }

    // public function updateCompletion(Request $request)
    // {
    //     $courseId = $request->all()['course_id'];
    //     $findValue = $request->all()['find_val'];
    //     $userId = $request->all()['user_id'];
    //     $lectureId = $request->all()['lecture_id'];
    //     $nextRoute = $request->all()['next'];
    //     if(auth()->user()->id == 15029) {
    //         //dd($nextRoute);exit;
    //     }
    //     if( $this->clRepo->performCompletionLogic($courseId, $userId, $findValue, true) ) {
    //         if($findValue == 'intro_'.$courseId) { //completion of intro section
    //             $course = Course::findOrFail($courseId); 
    //             if(auth()->user()->id == 15029) {
    //                 return redirect($nextRoute)->with('success', 'Updated the previous section!');
    //             } else {
    //                 return redirect()->route('courses.view-course', [$course] )->with('success', 'Updated!');
    //             }
                
    //         } else { 
    //             $lecture = Lecture::findOrFail($lectureId);
    //             if(auth()->user()->id == 15029) {

    //             } else {
    //                 return redirect()->route('courses.learn-course', [$lecture] )->with('success', 'Updated the previous section!');
    //             }             
    //         }        
    //     } else {
    //         return response()->json(['error' => 'error occured while updating!']);
    //     }
    // }

    public function updateCompletionPrev(Request $request)
    {
        $courseId = $request->all()['course_id'];
        $findValue = $request->all()['find_val'];
        $userId = $request->all()['user_id'];
        $lectureId = $request->all()['lecture_id'];
        $prevRoute = $request->all()['previous'];
        if( $this->clRepo->performCompletionLogic($courseId, $userId, $findValue, true) ) {
            if($findValue == 'intro_'.$courseId) { //completion of intro section
                $course = Course::findOrFail($courseId); 
                return redirect($prevRoute)->with('success', trans('Updated the previous section!'));             
            } else { 
                $lecture = Lecture::findOrFail($lectureId);
                return redirect($prevRoute)->with('success', trans('Updated the previous section!'));            
            }        
        } else {
            return response()->json(['error' => trans('error occured while updating!')]);
        }
    }

    public function updateCompletionNext(Request $request)
    {
        $courseId = $request->all()['course_id'];
        $findValue = $request->all()['find_val'];
        $userId = $request->all()['user_id'];
        $lectureId = $request->all()['lecture_id'];
        $nextRoute = $request->all()['next'];
        if( $this->clRepo->performCompletionLogic($courseId, $userId, $findValue, true) ) {
            // if($findValue == 'intro_'.$courseId) { //completion of intro section
            //     $course = Course::findOrFail($courseId); 
            //     return redirect($nextRoute)->with('success', trans('Updated the previous section!'));             
            // } else { 
            //     $lecture = Lecture::findOrFail($lectureId);
            //     return redirect($nextRoute)->with('success', trans('Updated the previous section!'));            
            // }        
            return redirect($nextRoute)->with('success', trans('Updated the previous section!')); 
        } else {
            return response()->json(['error' => trans('error occured while updating!')]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateCertificate(Request $request)
    {   
        $courseId = $request->all()['course_id'];
        $userId = $request->all()['user_id'];
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
        return $pdf->download($userName."_".time().'_Course Completetion Certificate.pdf');  
    }
}
