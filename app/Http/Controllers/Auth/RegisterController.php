<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Notifications\UserRegistered;
use App\Jobs\SendOTPSMS;
use Illuminate\Auth\Events\Registered;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client; 
    //use ClickSend;
    //use ClickSend\Configuration;
    //use ClickSend\Api\SMSApi;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'verify/get_otp'; //'/dashboard';

    protected $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        //dd($this->request);exit;
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    { 
        if($data['account_type'] == 1 ) {
            if($data['user_type_teacher'] == 'independent_teacher' ) {
                return Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'username' => 'required|max:255|unique:users',
                    'email' => 'required|string|email|max:255|unique:users',
                    'mobile_no' => in_array('sms', $data['notification_channel']) ? 'required|string|max:255' : 'nullable|string|max:255',
                    'account_type' => 'required|string',
                    'user_type_all' => 'required|string',
                    'password' => 'required|string|min:8|confirmed',
                    'profile_image' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg',
                ]);
            } else {
                return Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'username' => 'required|max:255|unique:users',
                    'email' => 'required|string|email|max:255|unique:users',
                    'mobile_no' => in_array('sms', $data['notification_channel']) ? 'required|string|max:255' : 'nullable|string|max:255',
                    'ec_college' => 'required',
                    'account_type' => 'required|string',
                    'user_type_teacher' => 'required|string',
                    'password' => 'required|string|min:8|confirmed',
                    'profile_image' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg',
                ]);
            }
            //dd($validator);exit;
        } else { //2
            if($data['user_type_all'] == 'journalist' ) {
                return Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'username' => 'required|max:255|unique:users',
                    'email' => 'required|string|email|max:255|unique:users',
                    'mobile_no' => in_array('sms', $data['notification_channel']) ? 'required|string|max:255' : 'nullable|string|max:255',
                    'password' => 'required|string|min:8|confirmed',
                    'profile_image' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg',
                    'account_type' => 'required|string',
                    'user_type_all' => 'required|string',
                    'affiliation' => 'required|string|max:128',
                    'position' => 'required|string|max:255',
                ]);
            } elseif( $data['user_type_all'] == 'independent_learner') {
                return Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'username' => 'required|max:255|unique:users',
                    'email' => 'required|string|email|max:255|unique:users',
                    'mobile_no' => in_array('sms', $data['notification_channel']) ? 'required|string|max:255' : 'nullable|string|max:255',
                    'account_type' => 'required|string',
                    'user_type_all' => 'required|string',
                    'password' => 'required|string|min:8|confirmed',
                    'profile_image' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg',
                ]);
            } else { //teacher again
               
                return Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'username' => 'required|max:255|unique:users',
                    'email' => 'required|string|email|max:255|unique:users',
                    'mobile_no' => in_array('sms', $data['notification_channel']) ? 'required|string|max:255' : 'nullable|string|max:255',
                    'ec_college' => 'required',
                    'account_type' => 'required|string',
                    'user_type_teacher' => 'required|string',
                    'password' => 'required|string|min:8|confirmed',
                    'profile_image' => 'image|mimes:jpeg,jpg,png,bmp,gif,svg',
                ]);
            }
        }
       
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {   //dd($data['notification_channel']);exit;
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            //'mobile_no' => $data['mobile_no'],
            'password' => Hash::make($data['password']),
            'gender' => $data['gender'],
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
        $user->mobile_no = $data['mobile_no'];
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
        $user->affiliation = $user->user_type == 'journalist' ? $data['affiliation'] : null;
        $user->position = $user->user_type == 'journalist' ? $data['position'] : null;
        $code = $data['country_code'] == 'th' ? 66 : 95;
        $user->country_code = $code;
        $user->save();

        if (isset($data['subjects'])) {
            $user->subjects()->detach();
            $user->subjects()->attach($data['subjects']);
        }

        if ($this->request->profile_image) {
            $user->addMediaFromRequest('profile_image')->withCustomProperties(['file_extension' => 
                $this->request->profile_image->extension()])->toMediaCollection('profile');
        }

        // Send SMS or email

        $data['email'] = $user->email;
        $data['name'] = $user->name;
        $data['mobile_no'] = $user->mobile_no;
        $data['otp'] = $user->verification_code;

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
                } else {                
                    return redirect()->back()->with('error', trans("User account was created and OTP was sent to the email address but sending OTP via SMS failed with status code: "). $message->getStatus() );
                }
            }
            
        } catch (Exception $e) {
            return abort(500);
        }
        
        return $user;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    { 
        $this->validator($request->all())->validate();
        //dd($request->all());exit;
        event(new Registered($user = $this->create($request->all())));

        // $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    public function testEmail()
    { 
        $user = User::findOrFail(15004);
        $user->notify(new UserRegistered($user));
    }

    public function test()
    {   
        // Configure HTTP basic authorization: BasicAuth
        $config = ClickSend\Configuration::getDefaultConfiguration()
        ->setUsername('stemmyanmar@unesco.org')
        ->setPassword('7494C75E-FE0A-B02C-40B1-FA4090325E01');

        // $apiInstance = new ClickSend\Api\AccountApi(
        //     // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
        //     // This is optional, `GuzzleHttp\Client` will be used as default.
        //     new \GuzzleHttp\Client(), // without \ in front of Guzzle, it will give error
        //     $config
        // );

        // try {
        //     $result = $apiInstance->accountGet();
        //     dd($result);exit;
        // } catch (Exception $e) {
        //     echo 'Exception when calling AccountApi->accountGet: ', $e->getMessage(), PHP_EOL;
        // }
        $apiInstance = new ClickSend\Api\SMSApi(
            // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
            // This is optional, `GuzzleHttp\Client` will be used as default.
            new \GuzzleHttp\Client(), // without \ in front of Guzzle, it will give error
            $config
        );
        $msg = new \ClickSend\Model\SmsMessage();
        $msg->setBody("TESTING FROM staging & OTP is 123456"); 
        $msg->setTo("+959791680837"); //9963160440 //9676091357 ma hnin su wai
        $msg->setSource("E-learning");
        
        // \ClickSend\Model\SmsMessageCollection | SmsMessageCollection model
        $sms_messages = new \ClickSend\Model\SmsMessageCollection(); 
        $sms_messages->setMessages([$msg]);
        try {
            $result = $apiInstance->smsSendPost($sms_messages);
            dd($result);exit; // sent to mpt, telenor, ooredoo (ma rmd), my tel (ma hsw)
        } catch (Exception $e) {
            echo 'Exception when calling SMSApi->smsSendPost: ', $e->getMessage(), PHP_EOL;
        }

        // //dd(env('MAIL_FROM_ADDRESS'));exit;  
        // $basic  = new \Vonage\Client\Credentials\Basic("cccd3ddc", "ZGhofgXMzjjQm6zZ");
        // $client = new \Vonage\Client($basic);
        // //dd($client);exit; // mpt ma rmd - 9251232660, telenor ma rmd = 9768094539, ma rmd oreedo = 9963160440, ma RMD aunty oreedo
        // $response = $client->sms()->send(  
        //     new \Vonage\SMS\Message\SMS("959897929244", 'E-Learning', 'A text message sent using the Nexmo SMS API ')
        // );
        
        // $message = $response->current();
        // //dd($message);exit;
        // if ($message->getStatus() == 0) {
        //     echo "The message was sent successfully to 959897929244";
        // } else {
        //     echo "The message failed with status: " . $message->getStatus() . "\n";
        // } //6f1471bd-1bc4-47af-ae39-8195adccb9b2
        //sample response format
        //Vonage\SMS\SentSMS {#2058 ▼
            #accountRef: null
            #clientRef: null
            #messageId: "56174581-3ebc-4444-a7bb-d96c8ebfa73d"
            #messagePrice: "0.11390000"
            #network: "41405"
            #remainingBalance: "88.46910000"
            #status: 0
            #to: "959963160440"
        //  }

        //"{"http_code":200,"response_code":"SUCCESS","response_msg":"Messages queued for delivery.","data":{"total_price":0.0832,"total_count":1,"queued_count":1,
        //"messages":[{"direction":"out","date":1655355861,"to":"+959897929244","body":"TESTING FROM staging & OTP is 123456","from":"PINSMS",
        //"schedule":0,"message_id":"6B69A75D-2EB2-4529-94FA-AEE104B22123","message_parts":1,"message_price":"0.0832","from_email":null,"list_id":null,"custom_string":"","contact_id":null,"user_id":337105,"subaccount_id":382656,"country":"MM","carrier":"MPT","status":"SUCCESS"}],"_currency":{"currency_name_short":"USD","currency_prefix_d":"$","currency_prefix_c":"\u00a2","currency_name_long":"US Dollars"}}} ◀"
    }
}
