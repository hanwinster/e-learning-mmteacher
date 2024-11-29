<?php

use Illuminate\Http\Request;

#Register
Route::post('register', 'API\Auth\RegisterController@register');
#Verification
Route::post('verify/submit_otp', 'API\Auth\VerifyOTPController@verifyOTP')->name('auth.verify.submit_otp.api');
#Resend OTP (maybe in future)
//Route::post('resend/otp', 'API\Auth\VerifyOTPController@resendOTP')->name('auth.resend.otp.api');
#Reset Password 
Route::post('reset-password-mobile/email/send_reset_token', 'API\Auth\SendPasswordResetTokenController@sendEmailPasswordResetToken');//->name('auth.reset-password.email.send_reset_token.api');
//Route::post('reset-password/send_reset_token', 'API\Auth\SendPasswordResetTokenController@sendPasswordResetToken')->name('auth.reset-password.send_reset_token.api'); //maybe in future
Route::post('reset-password-mobile/verify-token', 'API\Auth\SendPasswordResetTokenController@verifyToken'); //->name('auth.reset-password.verify-token.api');
Route::post('reset-password-mobile/reset', 'API\Auth\SendPasswordResetTokenController@resetPassword'); //->name('auth.post.reset-password.api');
 
/*
 * Private API Routes
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return response()->json([ 'data' => $request->user()],200);
});

Route::middleware('auth:api')->namespace('API')->name('api.')->group(function () {
    Route::get('me', 'ProfileController@show');
    Route::get('me/cancel-account', 'ProfileController@cancel'); //Working but need to add more checks if he/she already took courses etc
    Route::post('change-password', 'ProfileController@updatePassword')->name('change-password.update');
    Route::post('update-profile', 'ProfileController@update')->name('profile.update');
});
Route::post('login', 'API\Auth\LoginController@login');
Route::get('logout', 'API\Auth\LoginController@logout');

// Route::middleware('auth:api')->get('/test', function (Request $request) {
//     return 'hello';
// });
