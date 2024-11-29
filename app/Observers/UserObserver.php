<?php

namespace App\Observers;

use App;
use App\User;
use App\Models\Resource;
use Log;
use DB;

class UserObserver
{
    /**
    * Handle the article "deleting" event.
    *
    * @param  \App\User  $post
    * @return void
    */
    public function deleting(User $post)
    {
        // Delete related records
        DB::table('favourites')->where('user_id', $post->id)->delete();
        DB::table('link_reports')->where('user_id', $post->id)->delete();
        DB::table('feedbacks')->where('user_id', $post->id)->delete();
        DB::table('keyword_resource')->where('user_id', $post->id)->delete();

        DB::table('oauth_access_tokens')->where('user_id', $post->id)->delete();
        DB::table('model_has_roles')->where('model_type', 'App\User')
            ->where('model_id', $post->id)
            ->delete();

        DB::table('notifications')
            ->where('notifiable_type', 'App\User')
            ->where('notifiable_id', $post->id)
            ->delete();

        DB::table('requests')->where('user_id', $post->id)->delete();

        DB::table('user_subject')->where('user_id', $post->id)->delete();

        foreach (Resource::where('user_id', $post->id)->cursor() as $resource) {
            $resource->delete();
            Log::info('Deleted resource #' . $resource->id);
        }

        Log::info('Deleted related records for User #' . $post->id);
    }
}
