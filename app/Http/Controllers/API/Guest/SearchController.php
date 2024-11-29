<?php

namespace App\Http\Controllers\API\Guest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CourseRepository;
use App\Models\Course;
use App\Http\Resources\CourseResource;

class SearchController extends Controller
{
    protected $repository;

    public function __construct(CourseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($lang='en')
    {   
        if(auth()->check()) {
            $currentUserType = auth()->user()->type;
        } else {
            $currentUserType = 'guest';
        }
        try {
            $courses = $this->repository->getPublishedCoursesForHomeByLanguage($currentUserType, 8, $lang);
            
            //$courseCollection = collect();
            
            return response()->json(['data' => $courses]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Resource Not Found'], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($lang='en', $searchWord='')
    {   
        try {
            $courses = Course::where('is_published', 1)
                            ->where('approval_status', 1)
                            ->with('privacies');
            if(auth()->check()) {
                $currentUserType = auth()->user()->type;
            } else {
                $currentUserType = 'guest';
            }
            $courseCollection = collect();
            foreach ($courses->latest()->get() as $course) { 
                foreach ($course->privacies as $privacy) {
                    if($privacy->user_type == $currentUserType ) {
                        $courseCollection->push($course);
                        break;
                    }                      
                }
            }
            return response()->json(['data' => $courseCollection]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Resource Not Found'], 404);
        }
        
    }
}
