<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Notifications\ResetToken;
use DB;
use Auth;
use Hash;
use Carbon\Carbon;
use App\User;
use App\Jobs\SendResetTokenSMS;

class SendPasswordResetTokenController extends Controller
{
    public function chooseOption()
    {
        return view('auth.passwords.choose_option');
    }

    public function redirectOption(Request $request)
    {
        if (in_array('sms', $request->notification_channel)) {
            return redirect::route('auth.get.request_credientials');
        } else {
            return redirect::route('password.request');
        }
    }

    public function requestCredentials()
    {
        return view('auth.passwords.request_credentials');
    }

    /*
    * post
    * Credit to : https://stackoverflow.com/questions/42229086/how-to-create-a-password-reset-method-in-laravel-when-using-the-database-user-pr
    */
    public function sendPasswordResetToken(Request $request)
    {
        $rules = [
            'email' => 'required|string|email|max:255',
            'mobile_no' => 'required|string|max:255',
        ];

        $request->validate($rules);

        $user = User::where('email', $request->email)->where('mobile_no', $request->mobile_no)->first();
        if (!$user) {
            return redirect()->back()->with('error', "We can't find a user with these credentials.");
        }
        //->withErrors(['error' => '404']);

        //create a new token to be sent to the user.
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
            'token' => rand(100000, 1000000), //change 60 to any length you want
            'created_at' => Carbon::now()
        ]);

        $tokenData = DB::table('password_resets')->where('email', $request->email)->first();

        $token = $tokenData->token;

        $email = $request->email; // or $email = $tokenData->email;

        /**
        * Send email to the email above with a link to your password reset
        * something like url('password-reset/' . $token)
        * Sending email varies according to your Laravel version. Very easy to implement
        */
        $user->notify(new ResetToken($token));

        # Add job to Queue to send SMS
        $job = (new SendResetTokenSMS($user))->delay(now()->addSeconds(1));

        //dd($job);
        dispatch($job);


        return redirect::route('auth.reset-password.get-token');
    }

    public function getToken()
    {
        return view('auth.passwords.verifyToken');
    }

    public function verifyToken(Request $request)
    {
        $rules = [
            'token' => 'required|string|max:255',
        ];

        $request->validate($rules);

        $tokenData = DB::table('password_resets')->where('token', $request->token)->first();

        if (!$tokenData) {
            return redirect()->back()->with('error', "Invalid Token.");
        }
        //redirect()->to('/'); //redirect them anywhere you want if the token does not exist

        return redirect::route('auth.get.reset-password', $request->token);
    }

    public function showPasswordResetForm($token)
    {
        return view('auth.passwords.resetToken', compact('token'));
    }

    /**
     * Assuming the URL looks like this
     * http://localhost/password-reset/random-string-here
     * You check if the user and the token exist and display a page
     */
    public function showPasswordResetForm2($token)
    {
        //$tokenData = DB::table('password_resets')->where('token', $token)->first();

        //if ( !$tokenData ) return redirect()->to('home'); //redirect them anywhere you want if the token does not exist.
        //return view('passwords.show');
    }

    public function resetPassword(Request $request, $token)
    {
        //some validation
        $rules = [
            'email' => 'required|string|email|max:255',
            //'mobile_no' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ];

        $request->validate($rules);

        $password = $request->password;
        $tokenData = DB::table('password_resets')->where('email', $request->email)->where('token', $token)->first();
        if (!$tokenData) {
            return redirect()->back()->with('error', "The e-mail address you want to reset is wrong.");
        }

        $user = User::where('email', $tokenData->email)->first();
        if (!$user) {
            return redirect()->back()->with('error', "We can't find a user with that e-mail address.");
        } //or wherever you want

        $user->password = Hash::make($password);
        $user->update(); //or $user->save();


        // If the user shouldn't reuse the token later, delete the token
        DB::table('password_resets')->where('email', $user->email)->delete();

        //do we log the user directly or let them login and try their password for the first time ? if yes
        Auth::login($user);

        return Redirect::route('home')->with('status', "Your account's password has been successfully reset.");

        //redirect where we want according to whether they are logged in or not.
        /*
        * 	Route::get('password-reset', 'SendPasswordResetTokenController@showForm');
            Route::post('password-reset', 'SendPasswordResetTokenController@sendPasswordResetToken');
            Route::get('reset-password/{token}', 'SendPasswordResetTokenController@showPasswordResetForm');
            Route::post('reset-password/{token}', 'SendPasswordResetTokenController@resetPassword');
        **/
    }
}
