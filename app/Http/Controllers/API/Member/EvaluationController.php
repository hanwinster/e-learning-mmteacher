<?php

namespace App\Http\Controllers\API\Member;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\CourseEvaluation;
use App\Models\EvaluationUser;
use App\Models\Course;
use App\Repositories\CourseLearnerRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EvaluationController extends Controller
{
    protected $clRepo;

    public function __construct(CourseLearnerRepository $clRepository)
    {   
        $this->clRepo = $clRepository;
    }
    
    public function getEvaluations(Request $request, $courseId)
    {
        if(!$this->clRepo->isUserAlreadyTakenCourse(auth()->user(), $courseId)) {
            return response()->json(['code' => 403, 'message' => 'You should take this course to access this section'], 403);
        }
        $course = Course::where('id', $courseId)->first();
        if(!$course) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
        try {
            $evaluationQs = CourseEvaluation::orderBy('order')->get(); 
        } catch (ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Evaluation is not found'], 404);
        }
        if(!$evaluationQs) {
            return response()->json(['code' => 404, 'message' => 'Evaluation is not found'], 404);
        }
        
        $agreeLevels = CourseEvaluation::AGREE_LEVELS;       
        $excellentLevels =  CourseEvaluation::EXCELLENT_LEVELS;
        $likelyLevels = CourseEvaluation::LIKELY_LEVELS;
        $deviceOptions = CourseEvaluation::DEVICE_OPTIONS;
        $post = EvaluationUser::where('course_id', $course->id)->where('user_id',auth()->user()->id)->get()->first();
        $post = isset($post->id) ? $post : null;
        $isSubmitted = isset($post->id) && $post->status == 2 ? true : false;
        $route = route('courses.evaluation', [$course]);
        $this->clRepo->updatelastVisited($course->id, auth()->user()->id, $route); 
        $courseLearner = $this->clRepo->getCourseLearnerData($course->id, auth()->user()->id);
        $data = [
            'saved_data' => $post, 
            'evaluation_questions' => $evaluationQs,        
            'agree_levels' => $agreeLevels,
            'excellent_levels' => $excellentLevels,  
            'likely_levels' => $likelyLevels,  
            'device_options' => $deviceOptions,          
            'course_learner' => $courseLearner
        ];      
        return response()->json(['data' =>  $data], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveEvaluations(Request $request, $courseId)
    {
     
        $course = Course::where('id', $courseId)->first();
        if(!$course) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'feedbacks' => 'required|array'
		]);
		if ($validator->fails()) {
			return response(['errors'=> $validator->errors()->all()], 422);
		}
        $hasExisting = EvaluationUser::where('course_id', $courseId)->where('user_id', auth()->user()->id)->first();
        if($hasExisting) {
            return response()->json(['code' => 409, 'message' => 'Existing record in database!'], 409);
        }
        $isSaved = EvaluationUser::query()->create([
                'course_id' => $courseId,
                'user_id' => auth()->user()->id,
                'feedbacks' => $request->input('feedbacks'),
                'status' =>  1
        ]);
        return response()->json(['data' => 'Saved successfully' ]);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitEvaluations(Request $request, $courseId)
    {
     
        $course = Course::where('id', $courseId)->first();
        if(!$course) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'feedbacks' => 'required|array'
		]);
		if ($validator->fails()) {
			return response(['errors'=> $validator->errors()->all()], 422);
		}
        
        $feedbacks = $request->input('feedbacks');
        $evaluationQs = CourseEvaluation::where('type','!=','comment_box')->get();
        $totalWithoutComment = count($evaluationQs);
        if(count(array_keys($feedbacks)) < $totalWithoutComment) {
            return response()->json(['code' => 400, 'message' => 'All the questions should be answered upon submission'], 400);
        }
      
        $values = array_values($feedbacks);
        $filterValues = array_filter($values, static function($var){return $var !== null;} );
       
        if($filterValues < $totalWithoutComment) {
            return response()->json(['code' => 400, 'message' => 'All the questions should be answered upon submission'], 400);
        }        
        
        if(count(array_keys($feedbacks)) == $totalWithoutComment) {                  
            if( in_array(null, $values )) { //echo "here";exit;
                return response()->json(['code' => 400, 'message' => 'All the questions should be answered upon submission'], 400);
            } 
            if ( intval($values[count($values) -1] ) == 0 )  { //echo "heeeeeere";exit;
                return response()->json(['code' => 400, 'message' => 'All the questions should be answered upon submission'], 400);
            } 
        }
        array_pop($values);// remove last which is comment
                    
        $sum = 0;
        for($i=0; $i< sizeof($values); $i++) {
            if($i == 9) continue; // omit the index of device option
                $sum+= intval($values[$i]);
        }
        $totalToRate = count($values) -1;  //need to remove one for device option
        $hasExisting = EvaluationUser::where('course_id', $courseId)->where('user_id', auth()->user()->id)->first();
        if($hasExisting) { 
            $isSaved = EvaluationUser::query()
                ->where('id',$hasExisting->id)
                ->where('user_id', auth()->user()->id)
                ->where('course_id', $courseId)
                ->update([
                'feedbacks' => $request->input('feedbacks'),
                'status' =>  2
            ]);
        } else{
            $isSaved = EvaluationUser::query()->create([
                'course_id' => $courseId,
                'user_id' => auth()->user()->id,
                'feedbacks' => $request->input('feedbacks'),
                'status' =>  2,
                'overall_rating' => $sum/$totalToRate // values -1 coz need to remove device input as well!!
            ]);
        }
        
        return response()->json(['data' => 'Submitted successfully']);                     
        
    }

    /**
     * Update
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateEvaluations(Request $request, $courseId, $evaId)
    {
        $course = Course::where('id', $courseId)->first();
        if(!$course) {
            return response()->json(['code' => 404, 'message' => 'Course is not found'], 404);
        } 
        //dd($request->all());exit; 
        $validator = Validator::make($request->all(), [
            'feedbacks' => 'required|array'
		]);
		if ($validator->fails()) {
			return response(['errors'=> $validator->errors()->all()], 422);
		}
       
        $savedEva = EvaluationUser::where('id',$evaId)->first();
        if(!$savedEva) {
            return response()->json(['code' => 404, 'message' => 'Saved record is not found'], 404);
        }
        $isSaved = EvaluationUser::query()
                        ->where('id',$evaId)
                        ->where('user_id', auth()->user()->id)
                        ->where('course_id', $courseId)
                        ->update([
                        'feedbacks' => $request->input('feedbacks'),
                        'status' =>  1
                    ]);
        return response()->json(['data' => 'Submitted successfully']); 
        
    }
}