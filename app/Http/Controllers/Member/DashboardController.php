<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseLearner;
use App\User;
use App\Models\Course;
use App\Repositories\UserRepository;
use App\Repositories\CourseRepository;
use App\Repositories\CourseLearnerRepository;
use App\Exports\BulkExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\NotifyUserCompleteCourse;
use Carbon\Carbon;
use stdClass;

class DashboardController extends Controller
{
    public function __construct(CourseRepository $repository, UserRepository $userRepository, CourseLearnerRepository $clRepo)
    {
        $this->courseRepository = $repository;
        $this->userRepository = $userRepository;
        $this->clRepo = $clRepo;
    }

    public function export(Request $request) 
    { 
        if($request->all()['startDate'] && $request->all()['endDate']) {
            return Excel::download(new BulkExport($request->all()['startDate'], $request->all()['endDate']), 'E-learning-SignupData.xlsx');
        } else {
            return 'missing data';
        }
    }

    
    public function notify(Request $request)
    {
        $data = $request->all();
        $temp  = explode( "_", $data['user_id'] ); 
        $user = User::getUserById($temp[0]);
        try {
            $user->notify(new NotifyUserCompleteCourse($user, $data['noti_subject'], $data['noti_message'] )); 
            $learner = CourseLearner::findOrFail($temp[1]); // temp1 has courseLearnerId
            $learner->notify_count = $learner->notify_count + 1;
            $learner->save();
            return redirect()->route('member.dashboard')
              ->with(
                  'success',
                  __('The email has been sent successfully!')
              );
        } catch (Exception $e) {
            return redirect()->route('member.dashboard')
                ->with(
                    'error',
                    __('Error occured while sending email!. Please try again')
                );
        }
    }

    public function remove(Request $request)
    {
        $data = $request->all();
        $learner = CourseLearner::findOrFail($data['remove_user_id']);
        
        try {
           
            $this->clRepo->cancelCourse($learner->course_id, $learner->user_id);
           
            return redirect()->route('member.dashboard')
              ->with(
                  'success',
                  __('Removed the user from the course successfully!')
              );
        } catch (Exception $e) {
            return redirect()->route('member.dashboard')
            ->with(
                'error',
                __('Error occured while removing the user from the course')
            );
        }
    }

    protected function adminRedirect()
    {
        $user = auth()->user(); 
        
        $totalFavourites = $user->favourites()->count();
        $totalNotifications = $user->notifications->count();
        $user_type = currentUserType();
        
        $courses = $this->courseRepository->index(request()); // paginate and get only 10
        $totalCourses = Course::where('approval_status', '!=', null)->get();
        
        $dt = Carbon::now();
            
        // $endDate = $dt->toDateString(); //Carbon::parse($dt->toDateString())->toDateString(); //TODO: be replaced with Carbon::now()
        $endDate = $dt->toDateString(); //Carbon::parse('2021-01-01')->toDateString(); //exit;
        $startDate = Carbon::parse(Carbon::parse($endDate)->timestamp - (86400 * 365))->toDateString();
        $oneYearData = UserRepository::getTotalSignupsByPeriod($startDate, $endDate);
        $signupData = $this->formattedMonthlyResultForLineChart($oneYearData);  
        $lineGraphLabel = $signupData->label;
        $lineGraphData = $signupData->data;
        $donutChart = UserRepository::getGenderDataByDate(null,null);
        $donutChartLabel = $donutChart->label;
        $donutChartData = $donutChart->data;
        $usersPerEc = UserRepository::getUsersByEc(); 
        $visitorsPerEc = UserRepository::getUserVisitPercentageByEc($startDate, $endDate);
            
        return view('frontend.member.dashboard.index', compact('startDate', 'endDate','lineGraphLabel', 'totalCourses',
                'lineGraphData', 'donutChartLabel','donutChartData','usersPerEc', 'visitorsPerEc')); 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $user = auth()->user(); 
        $totalFavourites = $user->favourites()->count();
        $totalNotifications = $user->notifications->count();
        $user_type = currentUserType();
        if($user_type == User::TYPE_ADMIN) {
            //$courses = $this->courseRepository->index(request()); // paginate and get only 10
            //$totalCourses = Course::where('approval_status', '!=', null)->get();
            return $this->adminRedirect();
        } elseif ($user_type == User::TYPE_MANAGER) {
                if($user->isUnescoManager()) {
                    $courses = $this->courseRepository->indexNoPaginate(request());
                    $totalCoursesUnescoMgr = Course::where('approval_status', '!=', null)->get();
                } else {
                    $courses = $this->courseRepository->indexForManager(request());
                }              
        } elseif($user_type == User::TYPE_TEACHER_EDUCATOR) {
            $courses = $this->courseRepository->indexForMember(request());
        } else {
            $courses = $this->clRepo->indexForLearner(request());
        }
        if( $user->isUnescoManager()) {
            $dt = Carbon::now();
            
           // $endDate = $dt->toDateString(); //Carbon::parse($dt->toDateString())->toDateString(); 
            $endDate = $dt->toDateString(); //Carbon::parse('2022-06-15')->toDateString(); //exit;
            $startDate = Carbon::parse(Carbon::parse($endDate)->timestamp - (86400 * 365))->toDateString();
          // echo "<br />".$startDate." -- ".Carbon::parse(Carbon::parse($dt->toDateString())->timestamp - (86400 * 30))->toDateString()."<br/>";
            $oneMonthData = UserRepository::getTotalSignupsByPeriod($startDate, $endDate);
            //dd($oneMonthData);exit;
            $signupData = $this->formattedMonthlyResultForLineChart($oneMonthData); 
           // dd($signupData);exit;
            $lineGraphLabel = $signupData->label;
            $lineGraphData = $signupData->data;
            $donutChart = UserRepository::getGenderDataByDate(null,null); //'2022-01-01','2022-06-01');
            $donutChartLabel = $donutChart->label;
            $donutChartData = $donutChart->data;
            $usersPerEc = UserRepository::getUsersByEc();
            $endDateEdc = Carbon::parse(Carbon::now())->toDateString();
            $startDateEdc = Carbon::parse( Carbon::parse($endDate)->timestamp - (86400 * 365) )->toDateString(); //exit;           
            $visitorsPerEc = UserRepository::getUserVisitPercentageByEc($startDateEdc, $endDateEdc);
            $visitorsPerRole = UserRepository::getUserVisitPercentageByRole($startDateEdc, $endDateEdc);                      
        } 
        
       
            $temp = [];
            $notifyList = []; 
            // if(auth()->user()->id == 14736) {
            //     dd(count($courses));exit;
            // }
            if(count($courses) > 0) {
                for ($i=0; $i< count($courses); $i++) {  
                    $learners = CourseLearner::getLearnersByCourseId($courses[$i]->id);
                    //if($user_type == User::TYPE_TEACHER_EDUCATOR && $i == 3) {
                      // dd(count($learners) > 0);exit;
                    //}
                    if (count($learners) > 0) {  //echo "learners".$courses[$i]->title;
                        foreach($learners as $idx => $data) {  
                            if ($data->status != 'completed') {
                                //echo Carbon::parse(Carbon::now())->timestamp." - ".Carbon::parse($data->created_at)->timestamp;exit;
                                $allowedDuration = 0;
                                switch($courses[$i]->estimated_duration_unit) {
                                    case 'hour(s)': $allowedDuration = (3600) * $courses[$i]->estimated_duration;break; 
                                    case 'week(s)': $allowedDuration = (86400 * 7) * $courses[$i]->estimated_duration;break;
                                    case 'day(s)': $allowedDuration = 86400 * $courses[$i]->estimated_duration;break;                              
                                    case 'month(s)': $allowedDuration = (86400 * 30) * $courses[$i]->estimated_duration;break; 
                                    default: $allowedDuration = (86400 * 365 ) * $courses[$i]->estimated_duration;break; // year
                                }
                                
                                $daysOverdue = round ( ( Carbon::parse(Carbon::now())->timestamp - 
                                    (Carbon::parse($data->created_at)->timestamp + $allowedDuration)) / 86400);
                                    // if($user_type == User::TYPE_TEACHER_EDUCATOR && $i == 3) {
                                    //   echo $allowedDuration."  --  ".$daysOverdue."   ---  ".$courses[$i]->grace_period_to_notify." is over ? ";
                                    //   echo   $daysOverdue >= $courses[$i]->grace_period_to_notify; exit; 
                                    // }
                                if($daysOverdue >= $courses[$i]->grace_period_to_notify) { // echo "here";exit;
                                    $userInfo = User::getUserById($data->user_id);
                                    $temp['course_learner_id'] = $data->id;
                                    $temp['course_id'] =$data->course_id;
                                    $temp['course_title'] = $courses[$i]->title;
                                    $temp['user_id'] = $data->user_id;
                                    $temp['username'] = isset($userInfo) ? $userInfo->name : "-Deleted User-";
                                    $temp['overPeriod'] = $daysOverdue - $courses[$i]->grace_period_to_notify;
                                    $temp['email'] =  isset($userInfo) ? $userInfo->email : "-Deleted User-";
                                    $temp['notify_count'] = $data->notify_count;
                                    $temp['created_at'] = Carbon::parse($data->created_at)->toDateString();
                                    array_push($notifyList, $temp);
                                }
                            }
                        }
                    } else {
                        //echo "no learners".$courses[$i]->title;
                    }
                   
                } // end o f course loop
                //exit;
            } //end of courses > 0
                      
            if($user->isUnescoManager()) {  //echo 'mgr';exit;
                $totalUsers = \App\User::count();  //echo (auth()->user()->isManager() && auth()->user()->isUnescoManager());exit;
                return view('frontend.member.dashboard.index',
                    compact('startDate', 'endDate','lineGraphLabel', 
                    'lineGraphData', 'donutChartLabel','donutChartData','usersPerEc', 'visitorsPerEc',
                    'totalNotifications', 'totalCoursesUnescoMgr', 'totalFavourites','notifyList', 'courses','totalUsers', 'visitorsPerRole'));
            } else { //echo 'non mgr';exit;
                $totalUsersFromSameEC = count(User::getUsersByEcId( auth()->user()->ec_college ));
                $totalCourses = count($courses);
                return view('frontend.member.dashboard.index',
                compact('totalNotifications', 'totalCourses', 'totalFavourites', 'totalUsersFromSameEC','notifyList', 'courses'));
            }              
    }

    public function getSignupUsersByPeriod(Request $request)
    {
        if (!$request->route('start') || !$request->route('end')) return "missing start or end date";
        $diffTs =  Carbon::parse($request->route('end'))->timestamp - Carbon::parse($request->route('start'))->timestamp;
        $diffDay = $diffTs / 86400;
        $diffMonth = $diffDay / 30;
        $diffYear = $diffDay / 365;
        //echo "$diffTs - $diffDay  - $diffMonth  - $diffYear";
        //echo "\n " . round($diffTs) . " - " . round($diffDay) . "  - " . round($diffMonth) . "  - " . round($diffYear);
        //echo App::getLocale();exit;
        try {
            $result = UserRepository::getTotalSignupsByPeriod($request->route('start'), $request->route('end'));
            $final = null;
           // if ($diffYear > 1) {
                // call to get data by yearly
           // } else
            //dd($result);exit;
           //dd($this->formattedMonthlyResultForLineChart($result));exit;
           if ( $diffMonth >= 1 ) {
                // call to get data by monthly
                $final = $this->formattedMonthlyResultForLineChart($result);
            } else { //if( round($diffDay) > 0 &&  round($diffDay) < 31) {
                
                $final = $this->formattedDailyResultForLineChart($result);
             }             
           return response(['data' => $final], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    protected function formattedMonthlyResultForLineChart($result) {
        $final = new stdClass(); //echo count($result);exit;
        $final->label = [];
        $final->data = [];
        if(sizeof($result) > 0) {
            $temp = [];
            $arrInitial = explode('-', $result[0]->created_at);
            $yearMonthFirst = $arrInitial[1]."-".$arrInitial[0]; // only month and year 11-2020
            $temp[0] = $yearMonthFirst;
            $temp[1] = 0;
            $count = 0;
            foreach($result as  $res) {    
                $arr = explode('-', $res->created_at);
                $yearMonth = $arr[1]."-".$arr[0];               
                if ( $temp[0] == $yearMonth ) { 
                    $temp[1]++; 
                } else { 
                    //echo "\n"; print_r($temp);
                    array_push($final->label, $temp[0]);
                    array_push($final->data, $temp[1]);
                    $temp[0] =  $yearMonth; 
                    $temp[1] = 1; 
                }
                $count++;
                if($count == sizeof($result)) {
                   // echo "\n"; print_r($temp);
                    array_push($final->label, $temp[0]);
                    array_push($final->data, $temp[1]);
                }
            }       
        } 
        return $final;
    }

    protected function formattedDailyResultForLineChart($result) {
        $final = new stdClass();
        $final->label = [];
        $final->data = [];
        if(sizeof($result) > 0) {
            $temp = [];
            $temp[0] = $result[0]->created_at;
            $temp[1] = 0;
            $count = 0;
            foreach($result as  $res) {                   
                if ( $temp[0] == $res->created_at ) { 
                    $temp[1]++; 
                } else { 
                    //echo "\n"; print_r($temp);
                    array_push($final->label, $temp[0]);
                    array_push($final->data, $temp[1]);
                    $temp[0] =  $res->created_at; 
                    $temp[1] = 1; 
                }
                $count++;
                if($count == sizeof($result)) {
                   // echo "\n"; print_r($temp);
                    array_push($final->label, $temp[0]);
                    array_push($final->data, $temp[1]);
                }
            }       
        } 
        return $final;
    }

    public function getGenderByPeriod(Request $request)
    {
        if (!$request->route('start') || !$request->route('end')) return "missing start or end date";
        
        try {
            $donutChart = UserRepository::getGenderDataByDate($request->route('start'),$request->route('end')); //'2022-01-01','2022-06-01');
            $donutChartLabel = $donutChart->label;
            $donutChartData = $donutChart->data;             
           return response([ 'donutChartLabel' => $donutChartLabel, 'donutChartData' => $donutChartData ], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
