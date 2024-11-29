<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\User;
//use Illuminate\Support\Facades\Redirect;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// use App\Notifications\UserRegistered;
// use App\Jobs\SendOTPSMS;
use App\Http\Resources\UserResource;

class VerifyOTPController extends Controller
{	
    public function verifyOTP(Request $request)
    {   
        $validator = Validator::make($request->all(), [
			'otp_code' => 'required|string|max:255'
		]);
		if ($validator->fails()) {
			return response(['errors'=> $validator->errors()->all()], 422);
		}

        // if (Auth::check() && (Auth::user()->verification_code === trim($request->otp_code))) {
        //     $user = User::find(Auth::user()->id);
        //     Auth::logout();
        // } else {
        //     $user = User::where('verification_code', trim($request->otp_code))->first();
        // }

        // if ($user->notification_channel == 'email') {
           
        // }
        // if ($user->notification_channel == 'sms') {
        //     $user->sms_verified_at = Carbon::now();
        // }
        try {
            $user = User::where('verification_code', trim($request->otp_code))->first();
            if(!$user) {
                return response()->json(['code' => 404, 'message' => 'Provided verification_code was not found'], 404);
            }
            $user->email_verified_at = Carbon::now();
            $user->verified = 1;
            if(($user->account_type == 1 && $user->user_type == USER::TYPE_INDEPENDENT_TEACHER) ||
                ($user->account_type == 2 && $user->user_type == USER::TYPE_INDEPENDENT_LEARNER) ) { //auto approved upon email validation for learner role
                $user->approved = 1; 
            }
            $user->save();

            $response = ['message' => 'Your account has been successfully verified.', 'data' => new UserResource($user) ];

            return response()->json($response, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Provided verification_code was not found'], 404);
        }
        
    }

    public function resendOTP(Request $request)
    {
        $rules = [
            'credential' => 'required|string|max:255',
        ];

        $request->validate($rules);
		
		if (is_null($user = User::where('username', trim($request->credential))->orWhere('email', trim($request->credential))->first())) {
            
			return response(['errors' => 'Your credential is wrong.'], 422); 
			
        } else {

            if($user->verification_code == null || $user->verification_code == "") {
                $user->verification_code = rand(100000,1000000);//str_random(6); mt_rand(100000,1000000)
		        $user->save();
            }

            // Send SMS
            # Add job to Queue to send SMS
            $job = (new SendOTPSMS($user))->delay(now()->addSeconds(1));

            //dd($job);
            dispatch($job);

            // Send Email
            $user->notify(new UserRegistered($user));
			
			$response = ['message' => 'Please copy and paste the verification code that we sent to your email and mobile phone in the text box.'];

			return response($response, 200);
        }
	}

}
