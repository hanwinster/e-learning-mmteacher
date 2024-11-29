<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\Course;
use App\Repositories\CourseRepository;
use App\Repositories\CourseLearnerRepository;
use Carbon\Carbon;
use App\Notifications\CourseEnrollment;
use Notification;
use Illuminate\Http\Request as Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/e-learning';  // '/dashboard';

    /**
     * Login username to be used by the controller.
     *
     * @var string
     */
    protected $username;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CourseRepository $repository,CourseLearnerRepository $clRepository)
    {
        $this->middleware('guest')->except('logout');
        $this->repository = $repository;
        $this->clRepo = $clRepository;
        $this->username = $this->findUsername();
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function findUsername()
    {
        $login = trim(request()->input('email'));

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    /**
     * Get username property.
     *
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    /** Customize Default Login
     *   Credit : https://stackoverflow.com/questions/31015606/login-only-if-user-is-active-using-laravel/31016210#31016210
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(\Illuminate\Http\Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // This section is the only change
        if ($this->guard()->validate($this->credentials($request))) {
            $user = $this->guard()->getLastAttempted();

            if ($user->verified) {
                // Make sure the user is active
                if ($user->approved == User::APPROVAL_STATUS_APPROVED && $this->attemptLogin($request)) {
                    $user->last_login = Carbon::now();
                    $user->save();
                    return $this->sendLoginResponse($request);
                } else {
                    // Increment the failed login attempts and redirect back to the
                    // login form with an error message.
                    $this->incrementLoginAttempts($request);

                    return redirect()
                                    ->back()
                                    ->withInput($request->only($this->username(), 'remember'))
                                    ->with('approve', 'Your account must be approved by administrator to login.');
                    //->withErrors(['approve' => 'You must be approved to login.']);
                }
            } else {
                return redirect()
                                ->route('auth.verify.get_otp', ['id' => $user->id])
                                ->with('status', 'Your account must be verified. Please verify your account with the verification code that we sent to your email or mobile phone.');
            }
        }

        /*
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }
        */

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /*
	* Added on 18th June 2022 to allow login and redirect to the course page 
	* all the apis which need authentication
	*/
    public function loginViaDialog(\Illuminate\Http\Request $request) 
	{	//dd($request->all());exit;
		$this->validateLogin($request);
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // This section is the only change
        if ($this->guard()->validate($this->credentials($request))) {
            $user = $this->guard()->getLastAttempted();

            if ($user->verified) {
                // Make sure the user is active
                if ($user->approved == User::APPROVAL_STATUS_APPROVED && $this->attemptLogin($request)) {
                    $user->last_login = Carbon::now();
                    $user->save();
                    $course = Course::findOrFail($request->all()['course_id']);
                    // return $this->sendLoginResponse($request);
                    if (CourseLearnerRepository::isAlreadyTakenCourse($user, $course)) {
                        return redirect()->route('courses.show', $course)->with('message', trans('You Already Took This Course'));
                    } else {
                        return $this->performTakeCourseLogic($course, $user);
                    }                 
                } else {
                    // Increment the failed login attempts and redirect back to the
                    // login form with an error message.
                    $this->incrementLoginAttempts($request);
                    return redirect()
                                    ->back()
                                    ->withInput($request->only($this->username(), 'remember'))
                                    ->with('approve', 'Your account must be approved by administrator to login.');
                    //->withErrors(['approve' => 'You must be approved to login.']);
                }
            } else {
                return redirect()
                                ->route('auth.verify.get_otp', ['id' => $user->id])
                                ->with('status', 'Your account must be verified. Please verify your account with the verification code that we sent to your email or mobile phone.');
            }
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);

	}

    protected function performTakeCourseLogic($course, $user)
    {
        $isOwner = auth()->user()->user_id == $course->user_id ? true : false;
        $completed = $this->repository->getAllSectionsForCourse($course->id);
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
            $route = route('courses.view-course', [$course]); // redirect to the main section after taking course everytime!
            $this->clRepo->updatelastVisited($course->id, $user->id, $route);  
            return  redirect($route);
    }
}
