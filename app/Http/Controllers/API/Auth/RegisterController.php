<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Notifications\UserRegistered;
//use App\Jobs\SendOTPSMS;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client; 


class RegisterController extends Controller
{
    protected function makeValidation($data)
    {
        if($data['account_type'] == 1 ) {// for independent_teacher only
            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                    'username' => 'required|max:255|unique:users',
                    'email' => 'required|string|email|max:255|unique:users',
                    'mobile_no' => in_array('sms', $data['notification_channel']) ? 'required|string|max:255' : 'nullable|string|max:255',
                    'account_type' => 'required|string',
                    'user_type_all' => 'required|string',
                    'password' => 'required|string|min:8|confirmed',
                    'profile_image' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg'
                  //  'gender' => 'required|string'
            ]);

        } else {
            if($data['user_type_all'] == 'journalist' ) {
                $validator = Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'username' => 'required|max:255|unique:users',
                    'email' => 'required|string|email|max:255|unique:users',
                    'mobile_no' => in_array('sms', $data['notification_channel']) ? 'required|string|max:255' : 'nullable|string|max:255',
                    'account_type' => 'required|string',
                    'user_type_all' => 'required|string',
                    'password' => 'required|string|min:8|confirmed',
                    'profile_image' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg',
                    'affiliation' => 'required|string|max:128',
                    'position' => 'required|string|max:255'
                    //'gender' => 'required|string'
                ]);
            } elseif( $data['user_type_all'] == 'independent_learner' ) {
                $validator = Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'username' => 'required|max:255|unique:users',
                    'email' => 'required|string|email|max:255|unique:users',
                    'mobile_no' => in_array('sms', $data['notification_channel']) ? 'required|string|max:255' : 'nullable|string|max:255',
                    'account_type' => 'required|string',
                    'user_type_all' => 'required|string',
                    'password' => 'required|string|min:8|confirmed',
                    'profile_image' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg'
                   // 'gender' => 'required|string'
                ]);
            } else {
                $validator = Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'username' => 'required|max:255|unique:users',
                    'email' => 'required|string|email|max:255|unique:users',
                    'mobile_no' => in_array('sms', $data['notification_channel']) ? 'required|string|max:255' : 'nullable|string|max:255',
                    'account_type' => 'required|string',
                    'ec_college' => 'required',
                    'user_type_all' => 'required|string',
                    'password' => 'required|string|min:8|confirmed',
                    'profile_image' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg'
                  //  'gender' => 'required|string'
                ]);
            }
        }
        $validator->sometimes('country_code', 'required|string', function ($data) {
            return in_array('sms', $data['notification_channel']);
        });
        return $validator;
    }

    public function register(Request $request)
    {  
        $data = $request->all();
        $validator = null; 
        if(isset($data['account_type']) )  {
            $validator = $this->makeValidation($data);
        } else {
            return response()->json(['code' => 400, 'message' => 'Account type is missing'], 400);
        }

        if ($validator->fails()) {
           
            $failedRules = $validator->failed();
            return response()->json(['code' => 400, 'message' =>  $validator->errors()->first()], 400);
        }

        $gender = isset($data['gender']) ? $data['gender'] : 'others';
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'gender' =>  $gender,
            'account_type' => $data['account_type'],
        ]);

        $shouldSendSMS = false;
        if (in_array('sms', $data['notification_channel'])) {
            $shouldSendSMS =true;        
        } 
        $user->notification_channel = 'email'; // SMS will be used only for registration and later will use only email
        $user->verification_code = rand(100000, 1000000);//str_random(6); mt_rand(100000,1000000)
        $user->user_type = $data['account_type'] == 1 ? $data['user_type_teacher'] : $data['user_type_all'];
        $user->ec_college = $data['account_type'] == 1 && ($data['user_type_teacher'] !== 'independent_teacher') ? $data['ec_college'] : null;
        $user->organization = isset($data['organization']) ? $data['organization'] : null;
        $user->mobile_no = isset($data['mobile_no']) ? $data['mobile_no'] : null;
        if (isset($data['suitable_for_ec_year']) && $data['suitable_for_ec_year'] !== null) {
            $user->suitable_for_ec_year = implode(',', $data['suitable_for_ec_year']);
        }
        //$user->suitable_for_ec_year = $data['year_id'];

        // #README - default user type for all public registration
        switch($user->user_type) {
            case 'journalist': $user->type = 'journalist';break;
            case 'education_college_teaching_staff': $user->type = 'teacher_educator';break;
            case 'education_college_non_teaching_staff': $user->type = 'student_teacher';break;
            case 'education_college_student_teacher': $user->type = 'student_teacher';break;
            case 'ministry_of_education_staff': $user->type = 'student_teacher';break;
            case 'independent_teacher': $user->type = 'independent_teacher';break;
            default: $user->type = 'independent_learner';break;
        }
        //dd($user);exit;
        $user->affiliation = $user->user_type == 'journalist' ? $data['affiliation'] : null;
        $user->position = $user->user_type == 'journalist' ? $data['position'] : null;
        if(isset($data['country_code'])) {
            $code =  $data['country_code'] == 'th' ? 66 : 95;
        } else {
            $code = 95;
        }
        $user->country_code = $code;
        $user->save();

        // if ($request->profile_image) {
        //     //$user->addMediaFromBase64($request->profile_image_in_baseb4)->usingFileName(str_random(12))->toMediaCollection('profile');
        //     $image = 'data:image/png;base64,' . $request->profile_image;
        //     $user->addMediaFromBase64($image)->usingFileName(str_random(12) . '.png')
        //         ->withCustomProperties(['file_extension' => 'png'])
        //         ->toMediaCollection('profile');
        // }
       
        if ($request->profile_image) { //echo $request->profile_image;exit;
            $user->addMediaFromRequest('profile_image')->withCustomProperties(['file_extension' => 
                $request->profile_image->extension()])->toMediaCollection('profile');
        }
        if (!$user) {
            return response(['errors' => "We can't create a new user account."], 400);
        }
        // Send SMS or email
        //$user->notify(new UserRegisteredMobile($user));

        //if (in_array('sms', $request->notification_channel)) {
        // Add job to Queue to send SMS
        //$job = (new SendOTPSMS($user))->delay(now()->addSeconds(1));

        // dispatch($job);
        //}
        try {
            $user->notify(new UserRegistered($user));
            if($shouldSendSMS && $data['mobile_no']) { 
                $smsMessage = "Your OTP code is ".$user->verification_code;
                $mobileNoWithCode = $code.$data['mobile_no']; //dd($mobileNoWithCode);exit;
                $basic  = new \Vonage\Client\Credentials\Basic("cccd3ddc", "ZGhofgXMzjjQm6zZ");
                $client = new \Vonage\Client($basic); // mpt ma rmd - 9251232660, telenor ma rmd = 9768094539, ma rmd oreedo = 9963160440
                $response = $client->sms()->send(  
                    new \Vonage\SMS\Message\SMS( $mobileNoWithCode, "E-learning", $smsMessage)
                );
                $message = $response->current();
                if ($message->getStatus() == 0) {
                    return response()->json(['data' => $user]);
                } else {                
                    return response(['errors' => trans("User account was created and OTP was sent to the email address but sending OTP via SMS failed with status code: ")
                        . $message->getStatus()  ], 500);
                }
            } else {
                return response()->json(['data' => $user]);
            }
            
        } catch (Exception $e) {
            return response(['errors' => "Errors occured while sending notifications"], 500);
        }   
       

        //$token = $user->createToken('Laravel Password Grant Client')->accessToken;
        //$response = ['token' => $token, 'message' => 'Your account has been created, but it must go through an approval process.'];
       // $response = ['message' => 'Your account has been created. Please verify it with the verification code that we sent to your email and mobile phone.'];

        
    }
}
