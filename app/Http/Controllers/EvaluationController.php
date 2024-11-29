<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\CourseEvaluation;
use App\Models\EvaluationUser;
use Illuminate\Support\Facades\Notification;
use Spatie\MediaLibrary\Models\Media;
use App\Repositories\CourseLearnerRepository;
use App\Repositories\CourseRepository;

class EvaluationController extends Controller
{
   
    private $repository;
    private $cRepository;

    public function __construct(CourseLearnerRepository $repository, CourseRepository $cRepository)
    {
        $this->repository = $repository;
        $this->cRepository = $cRepository;
    }

    public function show(Course $course)
    {
        //$course = Course::findOrFail($course->id);
        $lectures = $course->lectures()->orderBy('id')->get();
        $lecturesMedias = Media::all()->where('model_type', Lecture::class);
        $userLectures = auth()->user()->learningLectures;
        $courseLearner = $this->repository->getCourseLearnerData($course->id, auth()->user()->id);
        $completed = $courseLearner->completed;
        $status = $courseLearner->status;
        $percentage = $courseLearner->percentage;
        $evaluationQs = CourseEvaluation::orderBy('order')->get();   
        $agreeLevels = CourseEvaluation::AGREE_LEVELS;       
        $excellentLevels =  CourseEvaluation::EXCELLENT_LEVELS;
        $likelyLevels = CourseEvaluation::LIKELY_LEVELS;
        $deviceOptions = CourseEvaluation::DEVICE_OPTIONS;
        $post = EvaluationUser::where('course_id', $course->id)->where('user_id',auth()->user()->id)->get()->first();
        $post = isset($post->id) ? $post : null;
        $isSubmitted = isset($post->id) && $post->status == 2 ? true : false;   
        $lastSection = $completed[count($completed)-1];
        $lastValArr = array_keys($lastSection);
        $lastVal = $lastValArr[0];
      //  print_r($isSubmitted);exit;
        $previousSection = $this->cRepository->getRouteFromValue($lastVal);
        $route = route('courses.evaluation', [$course]);
        $this->repository->updatelastVisited($course->id, auth()->user()->id, $route);   
        return view('frontend.courses.evaluation', compact('course', 'lectures','lecturesMedias','post',
         'userLectures','completed','status','percentage', 'evaluationQs','isSubmitted', 'agreeLevels', 'excellentLevels',
        'likelyLevels', 'deviceOptions', 'previousSection'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\Illuminate\Http\Request $request)
    {
     
        $courseId = $request->input('course_id');
        $course = Course::findOrFail($courseId);
        $isFinalSubmit = $request->input('submit-eva') == 2 ? true : false;
        if($isFinalSubmit) { // need validation
            //dd($request->all());exit;
            $feedbacks = $request->input('feedbacks');
            $evaluationQs = CourseEvaluation::where('type','!=','comment_box')->get();
            $totalWithoutComment = count($evaluationQs);
            if(!$feedbacks) {
                return $this->returnToView($course, 'error', trans('All the questions should be answered upon submission'));
            }
            if(count(array_keys($feedbacks)) < $totalWithoutComment) {
                return $this->returnToView($course, 'error', trans('All the questions should be answered upon submission'));
            }
            $values = array_values($feedbacks);
            if(count(array_keys($feedbacks)) == $totalWithoutComment) {                  
                if( in_array(null, $values )) { //echo "here";exit;
                    return $this->returnToView($course, 'error', trans('All the questions should be answered upon submission'));
                } 
                if ( intval($values[count($values) -1] ) == 0 )  { //echo "heeeeeere";exit;
                    return $this->returnToView($course, 'error', trans('All the questions should be answered upon submission'));
                } 
            }
                    // all the questions are answered except comment
                    array_pop($values);// remove last which is comment
                    
                    $sum = 0;
                    for($i=0; $i< sizeof($values); $i++) {
                        if($i == 9) continue; // omit the index of device option
                        $sum+= intval($values[$i]);
                    }
                   
                    $totalToRate = count($values) -1;  //need to remove one for device option
                   
                    $isSaved = EvaluationUser::query()->create([
                        'course_id' => $courseId,
                        'user_id' => auth()->user()->id,
                        'feedbacks' => $request->input('feedbacks'),
                        'status' =>  2,
                        'overall_rating' => $sum/$totalToRate // values -1 coz need to remove device input as well!!
                    ]);
                
                    return $this->returnToView($course, 'success', trans('Saved successfully'));                     
        } else { // just save
            $isSaved = EvaluationUser::query()->create([
                'course_id' => $courseId,
                'user_id' => auth()->user()->id,
                'feedbacks' => $request->input('feedbacks'),
                'status' =>  1
            ]);
            return $this->returnToView($course, 'success', trans('Saved successfully'));
        }
    }

    protected function returnToView($course, $messageType, $message)
    {
        return redirect()->route('courses.evaluation', $course)
              ->with(
                  $messageType,
                  __($message)
              );
    }

    /**
     * Update
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(\Illuminate\Http\Request $request, $id)
    {
     
        $courseId = $request->input('course_id');
        $course = Course::findOrFail($courseId);
        $isFinalSubmit = $request->input('submit-eva') == 2 ? true : false;
        if($isFinalSubmit) { // need validation
            $feedbacks = $request->input('feedbacks');
            $evaluationQs = CourseEvaluation::where('type','!=','comment_box')->get();
            $totalWithoutComment = count($evaluationQs);
            if(!$feedbacks) {
                return $this->returnToView($course, 'error', trans('All the questions should be answered upon submission'));
            }
            if(count(array_keys($feedbacks)) < $totalWithoutComment) {
                return $this->returnToView($course, 'error', trans('All the questions should be answered upon submission'));
            }
            $values = array_values($feedbacks);
            if(count(array_keys($feedbacks)) == $totalWithoutComment) { // all the questions are answered except comment                  
                if( in_array(null, $values )) { 
                    return $this->returnToView($course, 'error', trans('All the questions should be answered upon submission'));
                } 
                if ( intval($values[count($values) -1] ) == 0 )  { 
                    return $this->returnToView($course, 'error', trans('All the questions should be answered upon submission'));
                } 
            }
                    
                    array_pop($values);// remove last
                    $sum = 0;
                    for($i=0; $i< sizeof($values); $i++) {
                        $sum+= intval($values[$i]);
                    }
                    $avg = $sum/count($values); 
                    $isSaved = EvaluationUser::query()
                            ->where('id',$id)
                            ->update([
                                'course_id' => $courseId,
                                'user_id' => auth()->user()->id,
                                'feedbacks' => $request->input('feedbacks'),
                                'status' =>  2,
                                'overall_rating' => number_format((float)$avg, 2, '.', '')
                            ]);
                
                    return $this->returnToView($course, 'success', trans('Saved successfully'));                     
        } else { // just save
            $isSaved = EvaluationUser::query()
                        ->where('id',$id)
                        ->update([
                        'course_id' => $courseId,
                        'user_id' => auth()->user()->id,
                        'feedbacks' => $request->input('feedbacks'),
                        'status' =>  1
                    ]);
            return $this->returnToView($course, 'success', trans('Saved successfully'));
        }
    }
}
