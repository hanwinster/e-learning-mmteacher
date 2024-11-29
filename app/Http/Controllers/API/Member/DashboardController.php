<?php

namespace App\Http\Controllers\API\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Repositories\CourseLearnerRepository;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $clRepo;

    public function __construct(CourseLearnerRepository $clRepository)
    {   
        $this->clRepo = $clRepository;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        // if(Auth::guard('api')->check()) {
        //    return response()->json(Auth::guard('api')->user());
        // } else {
        //     return response()->json(['message' => 'Token is not valid anymore'], 200);
        // }
        $user = auth()->user();
        $totalResources = $user->resources()->where('approval_status', Course::APPROVAL_STATUS_APPROVED)->count();
        $totalNotifications = $user->notifications->count();
        $myCourses = $this->clRepo->indexForLearnerMobile(request());

        $json = [ 
            'data' => [
                'taken_courses' => count($myCourses), 
                'total_notifications' => $totalNotifications, 
                'my_courses' => $myCourses,
            ]
        ];


        return response()->json($json,200);
    }
}
