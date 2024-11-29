<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserRegistered;
use App\Jobs\SendOTPSMS;

class VerifyOTPController extends Controller
{
    public function getOTP()
    {
        return view('auth.otp');
    }

    public function verifyOTP(Request $request)
    {
        $rules = [
            'otp_code' => 'required|string|max:255',
        ];

        $request->validate($rules);

        if (Auth::check() && (Auth::user()->verification_code === trim($request->otp_code))) {
            $user = User::find(Auth::user()->id);

            Auth::logout();
        } else {
            $user = User::where('verification_code', trim($request->otp_code))->first();
        }

        if (!isset($user->id)) {
            return redirect()->route('auth.verify.get_otp')
                ->with('error', 'Validation fails. Please try again.');
        }

        if ($user->notification_channel == 'email') { 
            $user->email_verified_at = Carbon::now();
        }
       
        // if ($user->notification_channel == 'sms') {
        //     $user->sms_verified_at = Carbon::now();
        // }
        $user->verified = 1;  //echo $user->account_type." == ".$user->type;exit;
        if(($user->account_type == 1 && $user->type == USER::TYPE_INDEPENDENT_TEACHER) ||
            ($user->account_type == 2 && $user->type == USER::TYPE_INDEPENDENT_LEARNER) ) { //auto approved upon email validation for learner role
            $user->approved = 1; 
        }
        $user->save();

        //Session::flash('status', 'Your account has been successfully verified.');

        //return Redirect::back()->with('status', 'Your account has been successfully verified.'); 'member.dashboard'
        // return Redirect::route('home')->with('status', 'Your account has been successfully verified.');
        return view('auth.opt-success')->with('status', 'Your account has been successfully verified.');
    }

    public function requestOTP()
    {
        return view('auth.request_otp');
    }

    public function resendOTP(Request $request)
    {
        $rules = [
            'credential' => 'required|string|max:255',
        ];

        $request->validate($rules);

        if (is_null($user = User::where('username', trim($request->credential))->orWhere('email', trim($request->credential))->first())) {
            return redirect()
                            ->back()
                            ->with('status', 'Your credential is wrong.');
        } else {
            if ($user->verification_code == null || $user->verification_code == '') {
                $user->verification_code = rand(100000, 1000000);//str_random(6); mt_rand(100000,1000000)
                $user->save();
            }

            // Send SMS
            // Add job to Queue to send SMS
            $job = (new SendOTPSMS($user))->delay(now()->addSeconds(1));

            //dd($job);
            dispatch($job);

            // Send Email
            $user->notify(new UserRegistered($user));

            return redirect()
                            ->route('auth.verify.get_otp')
                            ->with('status', 'Please copy and paste the verification code that we sent to your email or mobile phone in the text box below.');
        }
    }
    
    public function customLogout (Request $request) 
	{	
      
		auth()->guard('web')->logout();
		$request->session()->flush();
    	$request->session()->regenerate();	
		return redirect('/e-learning');
		
	}
}
