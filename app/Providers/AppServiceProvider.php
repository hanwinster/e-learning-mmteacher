<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Resource;
use App\Models\Article;
use App\Observers\ResourceObserver;
use App\Observers\ArticleObserver;
use App\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use App\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use Illuminate\Notifications\Channels\DatabaseChannel;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Resource::withoutWrapping();
        Resource::observe(ResourceObserver::class);
        Article::observe(ArticleObserver::class);
        User::observe(UserObserver::class);
        Validator::extend(
            'recaptcha',
            'App\\Helpers\\ReCaptcha@validate'
        );

        //Added by B.Singh
        Validator::extend('mp3_ogg_extension', function($attribute, $value, $parameters, $validator) {

            if(!empty($value->getClientOriginalExtension()) && ($value->getClientOriginalExtension() == 'mp3' || $value->getClientOriginalExtension() == 'ogg')){
                return true;
            }else{
                return false;
            }

        });

        //Added by B.Singh
        Validator::extend('lecture_extension', function($attribute, $value, $parameters, $validator) {

            if(!empty($value->getClientOriginalExtension()) && (($value->getClientOriginalExtension() == 'mp3' || $value->getClientOriginalExtension() == 'mp4' || $value->getClientOriginalExtension() == 'ppt' || $value->getClientOriginalExtension() == 'pptx' || $value->getClientOriginalExtension() == 'pdf'))){
                return true;
            }else{
                return false;
            }

        });

        //Added by B.Singh
        Validator::extend('assignment_extension', function($attribute, $value, $parameters, $validator) {

            if(!empty($value->getClientOriginalExtension()) && (($value->getClientOriginalExtension() == 'mp3' || $value->getClientOriginalExtension() == 'mp4' || $value->getClientOriginalExtension() == 'ppt' || $value->getClientOriginalExtension() == 'pptx' || $value->getClientOriginalExtension() == 'pdf' || $value->getClientOriginalExtension() == 'docx' || $value->getClientOriginalExtension() == 'xlsx'))){
                return true;
            }else{
                return false;
            }

        });

        // to add extra field in notifications table
        $this->app->instance(DatabaseChannel::class, new IlluminateDatabaseChannel);

        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
        

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
