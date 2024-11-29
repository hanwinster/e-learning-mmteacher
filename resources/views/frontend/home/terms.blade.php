@extends('frontend.layouts.default')

@section('title', __('Terms And Conditions'))

@section('header')
     
@endsection

@section('content')
@php
    $isEng = App::getLocale() == 'en' ? true : false;
@endphp
<section class="page-section learning-area" >
    <div class="container">
        <div class="row">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Terms And Conditions') }}</li>
                </ol>
            </nav>
            <div class="col-12"> 
                <h3 class="text-center text-primary mb-3">@lang('Terms And Conditions of Myanmar Teacher Platform')</h3>
                @if($isEng)
                    <p style="text-align:justify">
                    The terms and conditions listed below apply to all registered users in Myanmar Teacher Platform which includes E-learning and E-library systems. 
                    It is strongly recommended that you have read and understood the terms and conditions in order to use the platform properly. 
                    If you have questions or concerns with the terms, please reach out to us via
                        <a href="mailto:stemmyanmar@unesco.org">stemmyanmar@unesco.org</a>. The term “Course” or "Online Course" includes all the courses and lectures, web pages and other related materials. 
                        And the term "User" refers to all of the registered users with different roles in the platform.
                    </p>
                    <div class="row">
                        <div class="col-6">
                            <h4 class="text-center text-primary">@lang('Use of Materials from Myanmar Teacher Platform')</h4>
                            <p style="text-align:justify">
                            All online courses and materials, together with references used in the courses, are the property of the Myanmar Teacher Platform or the property of third parties and used with permission granted to UNESCO Myanmar. 
                            The materials are for the personal use of individuals registered in Myanmar Teacher Platform only and not allowed for commercial use.
                            </p>
                        </div>
                        <div class="col-6">
                            <h4 class="text-center text-primary">@lang('General Disclaimer')</h4>
                            <p style="text-align:justify">
                            Reference materials and links attached in the courses are provided by the course owners authorized with UNESCO Myanmar. 
                            Myanmar Teacher Platform does not assume responsibility or liability for the accuracy or completeness of content contained in reference materials or links. 
                            Also, it does not endorse any product, service or organization referenced.
                            </p>
                        </div>
                    </div>
                @else 
                    <p style="text-align:justify">
                    အောက်တွင်ဖော်ပြထားသော စည်းမျဉ်းသတ်မှတ်ချက်များအားလုံးသည် E-learning နှင့် E-library စနစ်များပါဝင်သော Myanmar Teacher Platformတွင် စာရင်းသွင်းအသုံးပြုသူ/ မန်ဘာအကောင့်ရှိသူအားလုံးနှင့် သက်ဆိုင်ပါသည်။ 
                    ဤပလက်ဖောင်းကို ကောင်းစွာ အသုံးပြုရန်အတွက် စည်းကမ်းသတ်မှတ်ချက်များကို သေချာစွာဖတ်ရှုနားလည်ထားရန် အလေးအနက် အကြံပြုအပ်ပါသည်။ 
                    သင့်တွင် စည်းကမ်းချက်များနှင့်ပတ်သက်၍ အချို့သောမေးခွန်းများ သို့မဟုတ် ထောက်ပြစရာအချက်များရှိပါက <a href="mailto:stemmyanmar@unesco.org">stemmyanmar@unesco.org</a> မှတဆင့် ကျွန်ုပ်တို့ထံ ဆက်သွယ်ပါ။ 
                    "သင်တန်း" သို့မဟုတ် "အွန်လိုင်းသင်တန်း" ဟူသော အသုံးအနှုန်းတွင် ဤပလက်ဖောင်းရှိ သင်တန်းများနှင့် ဟောပြောပွဲများ၊ ဝဘ်ဆိုဒ်စာမျက်နှာများနှင့် အခြားဆက်စပ် (သင်ထောက်ကူ)ပစ္စည်းများ ပါဝင်သည်။ 
                    "အသုံးပြုသူ" ဟုဆိုရာတွင် ဤပလက်ဖောင်းရှိ မတူညီသော အကောင့်/သုံးစွဲသူအမျိုးအစားများဖြင့် စာရင်းသွင်းထားသော သုံးစွဲသူအားလုံးကို ရည်ညွှန်းပါသည်။
                    </p>
                    <div class="row">
                        <div class="col-6">
                            <h4 class="text-center text-primary">@lang('Use of Materials from Myanmar Teacher Platform')</h4>
                            <p style="text-align:justify">
                            သင်တန်းတွင် ပူးတွဲပါရှိသည့် ကိုးကားပစ္စည်းများနှင့် လင့်ခ်များကို UNESCO Myanmar မှ တရားဝင်ခွင့်ပြုထားသော သင်တန်းဖန်တီးသူများမှ ပံ့ပိုးပေးပါသည်။ 
                            ရည်ညွှန်းပစ္စည်းများ သို့မဟုတ် လင့်ခ်များပါရှိသော အကြောင်းအရာများ၏ တိကျမှု သို့မဟုတ် ပြည့်စုံမှုအတွက် ဤပလပ်ဖောင်းတွင် တာဝန်ရှိခြင်း(သို့မဟုတ်) တာ၀န်ယူခြင်း မရှိပါ။ 
                            ထို့အပြင် ရည်ညွှန်းကိုးကားထားသော မည်သည့်ထုတ်ကုန်၊ ဝန်ဆောင်မှု (သို့မဟုတ်) အဖွဲ့အစည်းကိုမျှ ထောက်ခံခြင်းမရှိပါ။
                            </p>
                        </div>
                        <div class="col-6">
                            <h4 class="text-center text-primary">@lang('General Disclaimer')</h4>
                            <p style="text-align:justify">
                            သင်တန်းတွင် ပူးတွဲပါရှိသည့် ကိုးကားပစ္စည်းများနှင့် လင့်ခ်များကို UNESCO Myanmar မှ တရားဝင်ခွင့်ပြုထားသော သင်တန်းဖန်တီးသူများမှ ပံ့ပိုးပေးပါသည်။ 
                            ရည်ညွှန်းပစ္စည်းများ သို့မဟုတ် လင့်ခ်များပါရှိသော အကြောင်းအရာများ၏ တိကျမှု သို့မဟုတ် ပြည့်စုံမှုအတွက် E-learning ပလပ်ဖောင်းတွင် တာဝန်ရှိခြင်း(သို့မဟုတ်) တာ၀န်ယူခြင်း မရှိပါ။ 
                            ထို့အပြင် ရည်ညွှန်းကိုးကားထားသော မည်သည့်ထုတ်ကုန်၊ ဝန်ဆောင်မှု (သို့မဟုတ်) အဖွဲ့အစည်းကိုမျှ ထောက်ခံခြင်းမရှိပါ။
                            </p>
                        </div>
                    </div>
                @endif
            </div>   
        </div>
        
        <div class="row">
            <div class="col-12"> 
                <a id="privacy"><h3 class="text-center text-primary mb-3 mt-3">@lang('Privacy Policy')</h3></a>
                @if($isEng)
                    <p style="text-align:justify">
                    Your privacy is of utmost priority and any user related data and information are treated as strictly confidential. 
                    This Privacy Policy explains how Myanmar Teacher Platform collects, protects, uses, and shares information. 
                    The  section on Privacy Policy may be updated if new privacy policies are included.
                    </p>
                    <p style="text-align:justify">
                    By accessing the platform, personal information, such as name, email, mobile number, etc. will be collected and stored in the database on the Myanmar Teacher Platform production server. 
                    This information will not specifically identify the "User" and will be used for internal analysis of the performance of the platform only and will not be shared
                     with any external parties or organizations. For inquiries regarding the Privacy Policy, or to report a privacy related problem, please contact
                        <a href="mailto:stemmyanmar@unesco.org">stemmyanmar@unesco.org</a>
                    </p>
                    <div class="row">
                        <div class="col-6">
                            <h4 class="text-center text-primary">@lang('Data Privacy')</h4>
                            <p style="text-align:justify">
                            "Data privacy" is the protection of personal data from people or parties who should not have access to it. Data privacy is applied through a data privacy policy on the Myanmar Teacher Platform. 
                            The data policies and procedures in place are intended to make sure that your personal information is handled safely and responsibly. 
                            The responsibility to protect the privacy of data shared through the Platform by users is taken very seriously with commitment to provide the highest level of protection. 
                            Only the required information is collected to provide the best service for the users (e.g., "Name" of a user is collected to print out the name of the user in certificates after completing a course and also “Email” is collected to send one-time password upon registration and/or other triggered notifications).
                            New technologies to best protect user privacy are continuously explored and assessed to be applied in the existing and potential applications.
                            </p>
                        </div>
                        <div class="col-6">
                            <h4 class="text-center text-primary">@lang('Internet Privacy')</h4>
                            <p style="text-align:justify">
                            When visiting Myanmar Teacher Platform, users should feel safe and secure. 
                            The website is protected by SSL encryption and supports the connection via https only to promote confidentiality, authenticity and integrity. 
                            Account information is protected by making sure that it will be handled by authorized administrators from UNESCO Myanmar STEM project team. 
                            User passwords (in plain text) are encrypted upon registration and stored in encrypted format only. 
                            The access to user account information, learning materials and resources are restricted to the registered users only with login required by entering a unique username or email and password. 
                            However, users are strongly recommended not to share their personal password with others.
                            </p>
                        </div>
                    </div>  
                @else 
                <p style="text-align:justify">
                သင်၏ ကိုယ်ရေးအချက်အလက်လုံခြုံမှုသည် ကျွန်ုပ်တို့၏ပထမဦးစားပေးဖြစ်ပြီး သုံးစွဲသူ/ အကောင့်ဖွင့်ထားသူနှင့် သက်ဆိုင်သည့် ဒေတာနှင့် အချက်အလက်များကို တင်းကြပ်စွာ လျှို့ဝှက်သိမ်းဆည်းထားပါသည်။ 
                ဤမူဝါဒ သတ်မှတ်ချက်များတွင် Myanmar Teacher Platform မှ အသုံးပြုသူများ၏ ကိုယ်ရေးအချက်အလက်များကို စုဆောင်းခြင်း၊ ကာကွယ်ခြင်း၊ အသုံးပြုခြင်းနှင့် မျှဝေခြင်းတို့ကို ရှင်းပြထားသည်။ 
                ကျွန်ုပ်တို့၏ ကိုယ်ရေးအချက်အလက် လုံခြုံမှုဆိုင်ရာ မူဝါဒအသစ်များကို ထည့်သွင်းသည့်အခါတိုင်း ဤနေရာတွင် အခါအားလျော်စွာ ပြင်ဆင်ဖြည့်စွက်မှုများ ပြုလုပ်သွားမည်ဖြစ်ပါသည်။
                    </p>
                    <p style="text-align:justify">
                    ဤပလက်ဖောင်းကို အသုံးပြုရာတွင် အမည်၊ အီးမေးလ်၊ မိုဘိုင်းနံပါတ် အစရှိသည့် ကိုယ်ရေးအချက်အလက်များကို (မှတ်ပုံတင်ရာတွင်) ပေးရန်လိုအပ်ပြီး ၎င်းတို့ကို ဆာဗာရှိဒေတာဘေ့စ်တွင် သိမ်းဆည်းထားမည်ဖြစ်သည်။ 
                    ဤအချက်အလက်များကို အသုံးပြု၍ "အသုံးပြုသူ" ကို အတိအကျ ခွဲခြားသတ်မှတ်မည် မဟုတ်ပါ။ ပလက်ဖောင်း၏ စွမ်းဆောင်ရည်ကို အကဲဖြတ်သုံးသပ်ရန်အတွက်သာ အသုံးပြုမည်ဖြစ်ပြီး အခြားအစုအဖွဲ့များ သို့မဟုတ် အဖွဲ့အစည်းများနှင့်လည်း မျှဝေမည်မဟုတ်ပါ။ 
                    ကျွန်ုပ်တို့၏ ကိုယ်ရေးအချက်အလက် လုံခြုံမှုဆိုင်ရာမူဝါဒအကြောင်း စုံစမ်းမေးမြန်းရန် သို့မဟုတ် ကိုယ်ရေးအချက်အလက် လုံခြုံမှုဆိုင်ရာ ပြဿနာတစ်ခုခုရှိခဲ့ပါက အသိပေးတင်ပြရန် <a href="mailto:stemmyanmar@unesco.org">stemmyanmar@unesco.org</a> သို့  ကျေးဇူးပြု၍ ဆက်သွယ်ပေးပါ။
                    </p>
                    <div class="row">
                        <div class="col-6">
                            <h4 class="text-center text-primary">@lang('Data Privacy')</h4>
                            <p style="text-align:justify">
                            "ကိုယ်ရေးအချက်အလက်/ဒေတာ လုံခြုံမှု" ဆိုသည်မှာ ခွင့်မပြုသင့်သော လူများ သို့မဟုတ် အစုအဖွဲ့များထံမှ ကိုယ်ရေးအချက်အလက်များကို အကာအကွယ်ပေးခြင်းဖြစ်ပါသည်။ ထိုအချက်အား ကျွန်ုပ်တို့ ကောင်းစွာနားလည်သဘောပေါက်ပြီး 
                            ဤပလက်ဖောင်းကို ဖန်တီးရာတွင် ကျင့်သုံးထားပါသည်။ 
                            သင့်ကိုယ်ရေးအချက်အလက်များကို ဘေးကင်းလုံခြုံပြီး တာဝန်သိစွာ ကိုင်တွယ်အသုံးပြုကြောင်း သေချာစေရန် ကိုယ်ရေးအချက်အလက်/ဒေတာ မူဝါဒများနှင့် လုပ်ထုံးလုပ်နည်းများကို သတ်မှတ်ထားပါသည်။ 
                            ကျွန်ုပ်တို့၏ အသုံးပြုသူများမျှဝေထားသော ကိုယ်ရေးအချက်အလက်/ဒေတာအား ကာကွယ်ရန်ကျွန်ုပ်တို့တာဝန်ယူပါသည်။ ကျွန်ုပ်တို့သည် အဆင့်အမြင့်ဆုံး ကာကွယ်မှုပေးဆောင်ရန် လေးနက်စွာရည်ရွယ်ထားပြီး သုံးစွဲသူများအတွက် 
                            အကောင်းဆုံးဝန်ဆောင်မှုပေးရန်အတွက် လိုအပ်သော အချက်အလက်များကိုသာ စုဆောင်းပါသည် (ဥပမာ၊ သင်တန်းတစ်ခုပြီးသည်နှင့် သင်တန်းပြီးဆုံးကြောင်းလက်မှတ်တွင် အမည်ကို ရိုက်နှိပ်ရန်အတွက် သုံးစွဲသူများ၏ "အမည်" ကို တောင်းခံပါသည်။ 
                            ထိုနည်းတူစွာ “အီးမေးလ်”အား မှတ်ပုံတင်ပြီးနောက် OTP ပေးပို့ရန်နှင့် အကြောင်းကြား/သတိပေးချက်များ ပေးပို့ရန်အတွက် တောင်းခံပါသည်။) 
                            ထို့အပြင် ကျွန်ုပ်တို့သည် နောက်ဆုံးပေါ်နည်းပညာများကို အသုံးပြုခြင်းဖြင့် သင်၏ ကိုယ်ရေးအချက်အလက် လုံခြုံမှုကို အကောင်းဆုံး ကာကွယ်နိုင်ရန်အတွက် နည်းပညာအသစ် များကိုလည်း  အဆက်မပြတ်ရှာဖွေပြီး အကဲဖြတ်နေပါသည်။
                            </p>
                        </div>
                        <div class="col-6">
                            <h4 class="text-center text-primary">@lang('Internet Privacy')</h4>
                            <p style="text-align:justify">
                            ကျွန်ုပ်တို့သည် သင့်အား Myanmar Teacher Platformကို အသုံးပြုသောအခါတွင် ဘေးကင်းလုံခြုံသည်ဟု ခံစားစေလိုပါသည်။  
                            ကျွန်ုပ်တို့၏ဝဘ်ဆိုဒ်ကို SSL ကုဒ်ဝှက်ခြင်းဖြင့် ကာကွယ်ထားပြီး ချိတ်ဆက်ရာတွင်လည်း https မှတစ်ဆင့်သာ  ချိတ်ဆက်နိုင်ပါသည်။ 
                            Https သည် httpထက် အဆများစွာ ပို၍လုံခြုံပြီး အသုံးပြုသူအတွက် ဝက်ဆိုဒ်၏ အစစ်အမှန်ဖြစ်မှုနှင့် လျို့ဝှက်စွာ ချိတ်ဆက်နိုင်မှု တို့အား သေချာစေပါသည်။ 
                            သင့်အကောင့်အချက်အလက်များအား UNESCO Myanmar STEM ပရောဂျက်အဖွဲ့မှ အသိအမှတ်ပြုထားသော စီမံခန့်ခွဲသူများမှသာ ကိုင်တွယ်မည်ဖြစ်ပါသည်။ 
                            သင်၏စကားဝှက် (ရိုးရိုးစာသားဖြင့်ပေးထားသော) သည် မှတ်ပုံတင်သည့်အခါတွင် ကုဒ်ဝှက်(encrypted)ထားပြီး ကျွန်ုပ်တို့သည် ကုဒ်ဝှက်ထားသော ပုံစံကိုသာ စနစ်တွင် သိမ်းဆည်းထားသည်။ 
                            သင့်အကောင့်အချက်အလက်နှင့် သင်ကြားရေးပစ္စည်းအရင်းအမြစ်များအား ဝင်ရောက်ကြည့်ရှုခွင့်ကို စာရင်းသွင်း/အကောင့်ဖွင့် အသုံးပြုသူများကသာ ပြုလုပ်နိုင်မည် ဖြစ်ပြီး (မတူညီသော/စနစ်ရှိအခြားအသုံးပြုသူတစ်ဉီး၏ usernameနှင့် မတူညီသော) 
                            အသုံးပြုသူအမည်(username) သို့မဟုတ် အီးမေးလ် နှင့် စကားဝှက်ကို ထည့်သွင်းခြင်းဖြင့် စနစ်သို့ ဝင်ရောက်ရန် လိုအပ်ပါသည်။ 
                            ဤကဲ့သို့ စနစ်အား လုံခြုံအောင် ကာကွယ်ထားသည့်တိုင်အောင် သင့်စကားဝှက်ကို မည်သူတစ်ဦးတစ်ယောက်နှင့်မျှ မျှဝေခြင်းမပြုရန် အလေးအနက်အကြံပြုအပ်ပါသည်။
                            </p>
                        </div>
                    </div>  
                @endif
            </div>   
        </div>  
    </div>
</div>
@endsection