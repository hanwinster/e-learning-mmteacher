<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Redirect;
use App\Notifications\ResetTokenMobile;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use Hash;
use Carbon\Carbon;
use App\User;
//use App\Jobs\SendResetTokenSMS;

class SendPasswordResetTokenController extends Controller
{
	/*
	* (Email) provide email address to send the password reset link
	*/
    public function sendEmailPasswordResetToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
			'email' => 'required|string|email|max:255'
		]);
		if ($validator->fails()) {
			return response(['errors'=> $validator->errors()->all()], 422);
		}

        $user = User::where('email', $request->email)->first();
        if (!$user) {
			return response(['errors' => "We can't find a user with this email address."], 422);
        }

        //create a new token to be sent to the user.
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => rand(100000, 1000000),//str_random(60), //change 60 to any length you want
            'created_at' => Carbon::now()
        ]);

        $tokenData = DB::table('password_resets')->where('email', $request->email)->
                        where('mobile_no', $request->mobile_no)->orderBy('id', 'desc')->first();
        $token = $tokenData->token;

        //$email = $request->email; // or $email = $tokenData->email;

        /**
        * Send email to the email above with a link to your password reset
        * something like url('password-reset/' . $token)
        * Sending email varies according to your Laravel version. Very easy to implement
        */
        $user->notify(new ResetTokenMobile($token));

        $response = ['data' => "We have e-mailed Reset Token Number to reset your account's password!"];

        return response()->json($response, 200);
    }

    /*
    * 
    */
    public function sendPasswordResetToken(Request $request) //maybe implement it in future
    {
        $rules = [
            'email' => 'required|string|email|max:255',
            'mobile_no' => 'required|string|max:255',
        ];

        $request->validate($rules);

        $user = User::where('email', $request->email)->where('mobile_no', $request->mobile_no)->first();
        if (!$user) {
            return response(['errors' => "We can't find a user with these credentials."], 422);
        }

        //create a new token to be sent to the user.
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
            'token' => rand(100000, 1000000), //change 60 to any length you want
            'created_at' => Carbon::now()
        ]);

        $tokenData = DB::table('password_resets')->where('email', $request->email)->where('mobile_no', $request->mobile_no)->first();

        $token = $tokenData->token;

        $email = $request->email; // or $email = $tokenData->email;

        /**
        * Send email to the email above with a link to your password reset
        * something like url('password-reset/' . $token)
        * Sending email varies according to your Laravel version. Very easy to implement
        */
        $user->notify(new ResetTokenMobile($token));

        //SMS
        # Add job to Queue to send SMS
       //$job = (new SendResetTokenSMS($user))->delay(now()->addSeconds(1));
        //dispatch($job);

        $response = ['message' => 'Please copy and paste Reset Token Number that we sent to your email and mobile phone in the text box.'];

		return response($response, 200);
    }

    public function verifyToken(Request $request) // in use, verify the token received via email
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|max:255',
		]);
		if ($validator->fails()) {
			return response(['errors'=> $validator->errors()->all()], 422);
		}

        $tokenData = DB::table('password_resets')->where('token', $request->token)->first();

        if (!$tokenData) {
            return response(['errors' => "Invalid Token."], 422);
        }

        $response = ['token' => $request->token, 'message' => 'Valid Token. Please use it with reset password form.'];

        return response()->json([ 'data' => $response], 200);
    }

    public function resetPassword(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'token' => 'required|string|max:255'
		]);
		if ($validator->fails()) {
			return response(['errors'=> $validator->errors()->all()], 422);
		}

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response(['errors' => "We can't find a user with that e-mail address."], 404);
        } 

        $tokenData = DB::table('password_resets')->where('email', $request->email)->where('token', $request->token)->first();
        if (!$tokenData) {
            return response(['errors' => "The token you want to reset is wrong."], 422);
        }

        $user->password = Hash::make($request->password);
        $user->update(); //or $user->save();


        // If the user shouldn't reuse the token later, delete the token
        DB::table('password_resets')->where('email', $user->email)->delete();

        //do we log the user directly or let them login and try their password for the first time ? if yes
        // Auth::login($user);

        $response = ['message' => "Your account's password has been successfully reset."];

        return response($response, 200);
    }
}
