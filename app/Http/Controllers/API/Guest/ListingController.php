<?php

namespace App\Http\Controllers\API\Guest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Models\Contact;
use App\Models\ArticleCategory;
use App\Models\CourseCategory;
use App\User;
use App\Repositories\CollegeRepository;
use App\Repositories\YearRepository;
use Lang;

class ListingController extends Controller
{
    public function changeLanguage(Request $request)
    {   // \Lang::getLocale();exit;
        $validator = Validator::make($request->all(), [
            'lang' => 'required|string'
		]);
		if ($validator->fails()) {
			return response(['errors'=> $validator->errors()->all()], 422);
		}
        \Lang::setLocale($request->lang);
        return response()->json(['data' => 'Language set to '.\Lang::getLocale().' successfullly'], 200);
    }

    public function getCourseCategories()
    {
        $posts = CourseCategory::get()->pluck('name', 'id');


        $list = [];

        foreach ($posts as $key => $value) {
            $list[] = ['id' => $key, 'name' => $value];
        }

        return response()->json(['data' => $list]);
    }

    public function getCourseCategoryById($id)
    {
        try {
            $post = CourseCategory::where('id', '=', $id)->first();
            $list = [
                'id' => $post->id,
                'name' => $post->name,
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at
            ];
            return response()->json(['data' => $list ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Resource Not Found'], 404);
        }
    }

    public function getArticleCategories()
    {
        $posts = ArticleCategory::where('published', '=', 1)
            ->get()->pluck('title', 'id');


        $list = [];

        foreach ($posts as $key => $value) {
            $list[] = ['id' => $key, 'title' => $value];
        }

        return response()->json($list);
    }

    public function getAccessibleRights()
    {
        $types = User::TYPES;

        $list = [];

        foreach ($types as $key => $value) {
            $list[] = ['id' => $key, 'title' => $value];
        }

        return response()->json(['data' => $list], 200);
    }


    public function getRegionsAndStates()
    {
        $types = Contact::REGIONS_STATES;

        $list = [];

        foreach ($types as $key => $value) {
            $list[] = ['id' => $key, 'title' => $value];
        }

        return response()->json(['data' => $list]);
    }

    public function getUserTypes()
    {
        $types = UserRepository::getUserTypes(false);

        $list = [];

        foreach ($types as $key => $value) {
            $list[] = ['id' => $key, 'title' => $value];
        }

        return response()->json(['data' => $list]);
    }

    public function getNotificationChannel()
    {
        return response()->json([ 'data' =>
                [   ['id' => 'sms', 'title' => 'SMS'],
                    ['id' => 'email', 'title' => 'Email (Default)']
                ]
        ]);
    }

    public function getGender(Request $request)
    {
        if( $request->header('Content-Language') ) { 
            if(!setLanguageForSession($request->header('Content-Language'))) { // if language is set and it's not supported
                return response(['errors' => 'Provided language is not supported'], 404);
            }
            $lang = $request->header('Content-Language');
            if($lang=='my-MM') {
                return response()->json([ 'data' =>
                        [   ['code' => 'male', 'title' => trans('Male') ],
                            ['code' => 'female', 'title' => trans('Female') ],
                            ['code' => 'others', 'title' => trans('Others') ]
                        ]
                ]);
            } else {
                return response()->json([ 'data' =>
                        [   ['code' => 'male', 'title' =>'Male' ],
                            ['code' => 'female', 'title' => 'Female' ],
                            ['code' => 'others', 'title' => 'Others' ]
                        ]
                ]);
            }
        } else { 
            return response(['errors' => 'Content Language is missing in the header'], 400);
        }
    }

    public function getCollege(Request $request)
    {
        if( $request->header('Content-Language') ) {
            if(!setLanguageForSession($request->header('Content-Language'))) { // if language is set and it's not supported
                return response(['errors' => 'Provided language is not supported'], 404);
            }
            $lang = $request->header('Content-Language');
            $colleges = CollegeRepository::getItemListAPI($lang);
        } else { 
            return response(['errors' => 'Content Language is missing in the header'], 400);
        }
        

        $list = [];

        foreach ($colleges as $key => $value) {
            $list[] = ['id' => $key, 'title' => $value];
        }

        return response()->json(['data' => $list], 200);
    }

    public function getYearofTeaching(Request $request)
    {
        if( $request->header('Content-Language') ) {
            if(!setLanguageForSession($request->header('Content-Language'))) { // if language is set and it's not supported
                return response(['errors' => 'Provided language is not supported'], 404);
            }
            $years = YearRepository::getItemList(false);

            $list = [];
            $lang = $request->header('Content-Language');
            foreach ($years as $key => $value) { 
                if($lang=='my-MM') {
                    $list[] = ['id' => $key, 'title' => trans($value)];
                } else {
                    $list[] = ['id' => $key, 'title' => $value];
                }
            }

            return response()->json(['data' =>$list], 200);
        } else { 
            return response(['errors' => 'Content Language is missing in the header'], 400);
        }
    }

    public function getHomeVideoLink()
    {
        return response()->json(['data' => ['link' => env('APP_URL')."/assets/videos/mpt-mobile-latest.mp4"]]);
    }

    public function getUserManualLinks()
    {
        $manuals = []; 
        $data1 = [
            'title' => 'User Manual For Independent Learner Role(English) - Version 1',
            'link' => env('APP_URL').'/assets/files/MTP_USER MANUAL_final.pdf',
            'id' => 1
        ];
        $data2 = [
            'title' => 'User Manual For Independent Learner Role(Myanmar) - Version 1',
            'link' => env('APP_URL').'/assets/files/MTP_USER MANUAL_MM_clean.pdf',
            'id' => 2
        ];
        array_push( $manuals, $data1);
        array_push( $manuals, $data2);
        return response()->json(['data' => $manuals]);
    }

    public function getTermsAndConditions()
    {
        $data = [
            "en" => [
                "intro" => 'The terms and conditions listed below apply to all registered users in Myanmar Teacher Platform which includes E-learning and E-library systems. It is strongly recommended that you have read and understood the terms and conditions in order to use the platform properly. If you have questions or concerns with the terms, please reach out to us via <a href="mailto:stemmyanmar@unesco.org">stemmyanmar@unesco.org</a>. The term “Course” or "Online Course" includes all the courses and lectures, web pages and other related materials. And the term "User" refers to all of the registered users with different roles in the platform.',
                "use_of_materials" => "All online courses and materials, together with references used in the courses, are the property of the Myanmar Teacher Platform or the property of third parties and used with permission granted to UNESCO Myanmar. The materials are for the personal use of individuals registered in Myanmar Teacher Platform only and not allowed for commercial use.",
                "general_disclaimer" => "Reference materials and links attached in the courses are provided by the course owners authorized with UNESCO Myanmar. Myanmar Teacher Platform does not assume responsibility or liability for the accuracy or completeness of content contained in reference materials or links. Also, it does not endorse any product, service or organization referenced."
            ],
            "my-MM" => [
                "intro" => 'အောက်တွင်ဖော်ပြထားသော စည်းမျဉ်းသတ်မှတ်ချက်များအားလုံးသည် E-learning နှင့် E-library စနစ်များပါဝင်သော Myanmar Teacher Platformတွင် စာရင်းသွင်းအသုံးပြုသူ/ မန်ဘာအကောင့်ရှိသူအားလုံးနှင့် သက်ဆိုင်ပါသည်။ ဤပလက်ဖောင်းကို ကောင်းစွာ အသုံးပြုရန်အတွက် စည်းကမ်းသတ်မှတ်ချက်များကို သေချာစွာဖတ်ရှုနားလည်ထားရန် အလေးအနက် အကြံပြုအပ်ပါသည်။ သင့်တွင် စည်းကမ်းချက်များနှင့်ပတ်သက်၍ အချို့သောမေးခွန်းများ သို့မဟုတ် ထောက်ပြစရာအချက်များရှိပါက <a href="mailto:stemmyanmar@unesco.org">stemmyanmar@unesco.org</a> မှတဆင့် ကျွန်ုပ်တို့ထံ ဆက်သွယ်ပါ။ "သင်တန်း" သို့မဟုတ် "အွန်လိုင်းသင်တန်း" ဟူသော အသုံးအနှုန်းတွင် ဤပလက်ဖောင်းရှိ သင်တန်းများနှင့် ဟောပြောပွဲများ၊ ဝဘ်ဆိုဒ်စာမျက်နှာများနှင့် အခြားဆက်စပ် (သင်ထောက်ကူ)ပစ္စည်းများ ပါဝင်သည်။ "အသုံးပြုသူ" ဟုဆိုရာတွင် ဤပလက်ဖောင်းရှိ မတူညီသော အကောင့်/သုံးစွဲသူအမျိုးအစားများဖြင့် စာရင်းသွင်းထားသော သုံးစွဲသူအားလုံးကို ရည်ညွှန်းပါသည်။',
                "use_of_materials" => "သင်တန်းတွင် ပူးတွဲပါရှိသည့် ကိုးကားပစ္စည်းများနှင့် လင့်ခ်များကို UNESCO Myanmar မှ တရားဝင်ခွင့်ပြုထားသော သင်တန်းဖန်တီးသူများမှ ပံ့ပိုးပေးပါသည်။ ရည်ညွှန်းပစ္စည်းများ သို့မဟုတ် လင့်ခ်များပါရှိသော အကြောင်းအရာများ၏ တိကျမှု သို့မဟုတ် ပြည့်စုံမှုအတွက် ဤပလပ်ဖောင်းတွင် တာဝန်ရှိခြင်း(သို့မဟုတ်) တာ၀န်ယူခြင်း မရှိပါ။ ထို့အပြင် ရည်ညွှန်းကိုးကားထားသော မည်သည့်ထုတ်ကုန်၊ ဝန်ဆောင်မှု (သို့မဟုတ်) အဖွဲ့အစည်းကိုမျှ ထောက်ခံခြင်းမရှိပါ။",
                "general_disclaimer" => "သင်တန်းတွင် ပူးတွဲပါရှိသည့် ကိုးကားပစ္စည်းများနှင့် လင့်ခ်များကို UNESCO Myanmar မှ တရားဝင်ခွင့်ပြုထားသော သင်တန်းဖန်တီးသူများမှ ပံ့ပိုးပေးပါသည်။ ရည်ညွှန်းပစ္စည်းများ သို့မဟုတ် လင့်ခ်များပါရှိသော အကြောင်းအရာများ၏ တိကျမှု သို့မဟုတ် ပြည့်စုံမှုအတွက် E-learning ပလပ်ဖောင်းတွင် တာဝန်ရှိခြင်း(သို့မဟုတ်) တာ၀န်ယူခြင်း မရှိပါ။ ထို့အပြင် ရည်ညွှန်းကိုးကားထားသော မည်သည့်ထုတ်ကုန်၊ ဝန်ဆောင်မှု (သို့မဟုတ်) အဖွဲ့အစည်းကိုမျှ ထောက်ခံခြင်းမရှိပါ။"
            ]
        ];
        return response()->json(['data' => $data]);
    }

    public function getPrivacyPolicy()
    {
        $data = [
            "en" => [
                "intro" => 'Your privacy is of utmost priority and any user related data and information are treated as strictly confidential. This Privacy Policy explains how Myanmar Teacher Platform collects, protects, uses, and shares information. The  section on Privacy Policy may be updated if new privacy policies are included. By accessing the platform, personal information, such as name, email, mobile number, etc. will be collected and stored in the database on the Myanmar Teacher Platform production server. This information will not specifically identify the "User" and will be used for internal analysis of the performance of the platform only and will not be shared with any external parties or organizations. For inquiries regarding the Privacy Policy, or to report a privacy related problem, please contact<a href="mailto:stemmyanmar@unesco.org">stemmyanmar@unesco.org</a>',
                "data_privacy" => '"Data privacy" is the protection of personal data from people or parties who should not have access to it. Data privacy is applied through a data privacy policy on the Myanmar Teacher Platform. The data policies and procedures in place are intended to make sure that your personal information is handled safely and responsibly. The responsibility to protect the privacy of data shared through the Platform by users is taken very seriously with commitment to provide the highest level of protection. Only the required information is collected to provide the best service for the users (e.g., "Name" of a user is collected to print out the name of the user in certificates after completing a course and also “Email” is collected to send one-time password upon registration and/or other triggered notifications). New technologies to best protect user privacy are continuously explored and assessed to be applied in the existing and potential applications.',
                "internet_privacy" => 'When visiting Myanmar Teacher Platform, users should feel safe and secure. The website is protected by SSL encryption and supports the connection via https only to promote confidentiality, authenticity and integrity. Account information is protected by making sure that it will be handled by authorized administrators from UNESCO Myanmar STEM project team. User passwords (in plain text) are encrypted upon registration and stored in encrypted format only. The access to user account information, learning materials and resources are restricted to the registered users only with login required by entering a unique username or email and password. However, users are strongly recommended not to share their personal password with others.'
            ],
            "my-MM" => [
                "intro" => 'သင်၏ ကိုယ်ရေးအချက်အလက်လုံခြုံမှုသည် ကျွန်ုပ်တို့၏ပထမဦးစားပေးဖြစ်ပြီး သုံးစွဲသူ/ အကောင့်ဖွင့်ထားသူနှင့် သက်ဆိုင်သည့် ဒေတာနှင့် အချက်အလက်များကို တင်းကြပ်စွာ လျှို့ဝှက်သိမ်းဆည်းထားပါသည်။ ဤမူဝါဒ သတ်မှတ်ချက်များတွင် Myanmar Teacher Platform မှ အသုံးပြုသူများ၏ ကိုယ်ရေးအချက်အလက်များကို စုဆောင်းခြင်း၊ ကာကွယ်ခြင်း၊ အသုံးပြုခြင်းနှင့် မျှဝေခြင်းတို့ကို ရှင်းပြထားသည်။ ကျွန်ုပ်တို့၏ ကိုယ်ရေးအချက်အလက် လုံခြုံမှုဆိုင်ရာ မူဝါဒအသစ်များကို ထည့်သွင်းသည့်အခါတိုင်း ဤနေရာတွင် အခါအားလျော်စွာ ပြင်ဆင်ဖြည့်စွက်မှုများ ပြုလုပ်သွားမည်ဖြစ်ပါသည်။ ဤပလက်ဖောင်းကို အသုံးပြုရာတွင် အမည်၊ အီးမေးလ်၊ မိုဘိုင်းနံပါတ် အစရှိသည့် ကိုယ်ရေးအချက်အလက်များကို (မှတ်ပုံတင်ရာတွင်) ပေးရန်လိုအပ်ပြီး ၎င်းတို့ကို ဆာဗာရှိဒေတာဘေ့စ်တွင် သိမ်းဆည်းထားမည်ဖြစ်သည်။ ဤအချက်အလက်များကို အသုံးပြု၍ "အသုံးပြုသူ" ကို အတိအကျ ခွဲခြားသတ်မှတ်မည် မဟုတ်ပါ။ ပလက်ဖောင်း၏ စွမ်းဆောင်ရည်ကို အကဲဖြတ်သုံးသပ်ရန်အတွက်သာ အသုံးပြုမည်ဖြစ်ပြီး အခြားအစုအဖွဲ့များ သို့မဟုတ် အဖွဲ့အစည်းများနှင့်လည်း မျှဝေမည်မဟုတ်ပါ။ ကျွန်ုပ်တို့၏ ကိုယ်ရေးအချက်အလက် လုံခြုံမှုဆိုင်ရာမူဝါဒအကြောင်း စုံစမ်းမေးမြန်းရန် သို့မဟုတ် ကိုယ်ရေးအချက်အလက် လုံခြုံမှုဆိုင်ရာ ပြဿနာတစ်ခုခုရှိခဲ့ပါက အသိပေးတင်ပြရန် <a href="mailto:stemmyanmar@unesco.org">stemmyanmar@unesco.org</a> သို့  ကျေးဇူးပြု၍ ဆက်သွယ်ပေးပါ။',
                "data_privacy" => '"ကိုယ်ရေးအချက်အလက်/ဒေတာ လုံခြုံမှု" ဆိုသည်မှာ ခွင့်မပြုသင့်သော လူများ သို့မဟုတ် အစုအဖွဲ့များထံမှ ကိုယ်ရေးအချက်အလက်များကို အကာအကွယ်ပေးခြင်းဖြစ်ပါသည်။ ထိုအချက်အား ကျွန်ုပ်တို့ ကောင်းစွာနားလည်သဘောပေါက်ပြီး ဤပလက်ဖောင်းကို ဖန်တီးရာတွင် ကျင့်သုံးထားပါသည်။ သင့်ကိုယ်ရေးအချက်အလက်များကို ဘေးကင်းလုံခြုံပြီး တာဝန်သိစွာ ကိုင်တွယ်အသုံးပြုကြောင်း သေချာစေရန် ကိုယ်ရေးအချက်အလက်/ဒေတာ မူဝါဒများနှင့် လုပ်ထုံးလုပ်နည်းများကို သတ်မှတ်ထားပါသည်။ ကျွန်ုပ်တို့၏ အသုံးပြုသူများမျှဝေထားသော ကိုယ်ရေးအချက်အလက်/ဒေတာအား ကာကွယ်ရန်ကျွန်ုပ်တို့တာဝန်ယူပါသည်။ ကျွန်ုပ်တို့သည် အဆင့်အမြင့်ဆုံး ကာကွယ်မှုပေးဆောင်ရန် လေးနက်စွာရည်ရွယ်ထားပြီး သုံးစွဲသူများအတွက် အကောင်းဆုံးဝန်ဆောင်မှုပေးရန်အတွက် လိုအပ်သော အချက်အလက်များကိုသာ စုဆောင်းပါသည် (ဥပမာ၊ သင်တန်းတစ်ခုပြီးသည်နှင့် သင်တန်းပြီးဆုံးကြောင်းလက်မှတ်တွင် အမည်ကို ရိုက်နှိပ်ရန်အတွက် သုံးစွဲသူများ၏ "အမည်" ကို တောင်းခံပါသည်။ ထိုနည်းတူစွာ “အီးမေးလ်”အား မှတ်ပုံတင်ပြီးနောက် OTP ပေးပို့ရန်နှင့် အကြောင်းကြား/သတိပေးချက်များ ပေးပို့ရန်အတွက် တောင်းခံပါသည်။) ထို့အပြင် ကျွန်ုပ်တို့သည် နောက်ဆုံးပေါ်နည်းပညာများကို အသုံးပြုခြင်းဖြင့် သင်၏ ကိုယ်ရေးအချက်အလက် လုံခြုံမှုကို အကောင်းဆုံး ကာကွယ်နိုင်ရန်အတွက် နည်းပညာအသစ် များကိုလည်း  အဆက်မပြတ်ရှာဖွေပြီး အကဲဖြတ်နေပါသည်။',
                "internet_privacy" => 'ကျွန်ုပ်တို့သည် သင့်အား Myanmar Teacher Platformကို အသုံးပြုသောအခါတွင် ဘေးကင်းလုံခြုံသည်ဟု ခံစားစေလိုပါသည်။ ကျွန်ုပ်တို့၏ဝဘ်ဆိုဒ်ကို SSL ကုဒ်ဝှက်ခြင်းဖြင့် ကာကွယ်ထားပြီး ချိတ်ဆက်ရာတွင်လည်း https မှတစ်ဆင့်သာ  ချိတ်ဆက်နိုင်ပါသည်။ Https သည် httpထက် အဆများစွာ ပို၍လုံခြုံပြီး အသုံးပြုသူအတွက် ဝက်ဆိုဒ်၏ အစစ်အမှန်ဖြစ်မှုနှင့် လျို့ဝှက်စွာ ချိတ်ဆက်နိုင်မှု တို့အား သေချာစေပါသည်။ သင့်အကောင့်အချက်အလက်များအား UNESCO Myanmar STEM ပရောဂျက်အဖွဲ့မှ အသိအမှတ်ပြုထားသော စီမံခန့်ခွဲသူများမှသာ ကိုင်တွယ်မည်ဖြစ်ပါသည်။ သင်၏စကားဝှက် (ရိုးရိုးစာသားဖြင့်ပေးထားသော) သည် မှတ်ပုံတင်သည့်အခါတွင် ကုဒ်ဝှက်(encrypted)ထားပြီး ကျွန်ုပ်တို့သည် ကုဒ်ဝှက်ထားသော ပုံစံကိုသာ စနစ်တွင် သိမ်းဆည်းထားသည်။ သင့်အကောင့်အချက်အလက်နှင့် သင်ကြားရေးပစ္စည်းအရင်းအမြစ်များအား ဝင်ရောက်ကြည့်ရှုခွင့်ကို စာရင်းသွင်း/အကောင့်ဖွင့် အသုံးပြုသူများကသာ ပြုလုပ်နိုင်မည် ဖြစ်ပြီး (မတူညီသော/စနစ်ရှိအခြားအသုံးပြုသူတစ်ဉီး၏ usernameနှင့် မတူညီသော) အသုံးပြုသူအမည်(username) သို့မဟုတ် အီးမေးလ် နှင့် စကားဝှက်ကို ထည့်သွင်းခြင်းဖြင့် စနစ်သို့ ဝင်ရောက်ရန် လိုအပ်ပါသည်။ ဤကဲ့သို့ စနစ်အား လုံခြုံအောင် ကာကွယ်ထားသည့်တိုင်အောင် သင့်စကားဝှက်ကို မည်သူတစ်ဦးတစ်ယောက်နှင့်မျှ မျှဝေခြင်းမပြုရန် အလေးအနက်အကြံပြုအပ်ပါသည်။'
            ]
        ];
        return response()->json(['data' => $data]);
    }
}
