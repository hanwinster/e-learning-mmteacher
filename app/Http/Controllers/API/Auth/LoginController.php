<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Lcobucci\JWT\Parser;
use Lang;

class LoginController extends Controller
{
	/*
	* Added on 20th Jan 2022 to get token to access 
	* all the apis which need authentication
	*/
    public function login (Request $request) 
	{	
		// if(!setLanguageForSession($request->header('Content-Language'))) {
		// 	return response(['errors' => trans('Provided language is not supported')], 404);
		// }
		//getLoggedInUserLanguage();
		// if(isset($request->email)) {
		// 	$validator = Validator::make($request->all(), [
		// 		'email' => 'required|string|email|max:255',
		// 		'password' => 'required|string|min:8',
		// 	]);
		// } else 
		if(isset($request->username)) {
			$validator = Validator::make($request->all(), [
				'username' => 'required|string|max:255',
				'password' => 'required|string|min:8',
			]);
		} else {
			return response(['errors' => trans('email or username field is required')], 422);
		}
		
		if ($validator->fails()) {
			return response(['errors'=> $validator->errors()->all()], 422);
		}
		//if(isset($request->email)) {
		$user = User::where('email', $request->username)->where('approved',1)->where('verified',1)->first();
		if(!$user) {
			$user = User::where('username', $request->username)->where('approved',1)->where('verified',1)->first();
		}
		
		if ($user) {
			if (Hash::check($request->password, $user->password)) { //dd($user);exit;
				$token = $user->createToken('E-learning Password Grant Client')->accessToken;
				setLanguageForSession();
				return response()->json(['data' => ['bearer-token' => $token, 'user_id' => $user->id ] ], 200);
			} else {
				return response([ 'errors' => 'Password Mismatched' ], 422);
			}
		} else {
			return response([ 'errors' =>  'User does not exist or approved or verified' ], 422);
		}
	}
	
	public function logout (Request $request) 
	{	//dd($request->bearerToken());exit; 
		$token = auth()->user()->token();
		$token->revoke();
		auth()->guard('web')->logout();

		return response()->json([ 'data' => 'You have been succesfully logged out!'], 200);
	}
	
	/*
	Credit to : http://www.technitiate.com/logout-laravel-passport-authentication/
	public function logout(Request $request) 
	{
		$value = $request->bearerToken();
		
		if ($value) {

			$id = (new Parser())->parse($value)->getHeader('jti');
			$revoked = DB::table('oauth_access_tokens')->where('id', '=', $id)->update(['revoked' => 1]);
			//$this->guard()->logout();
		}
		
		Auth::logout();
		
		$response = 'You have been succesfully logged out!';
		
		return response($response, 200);
	}
	*/
}
