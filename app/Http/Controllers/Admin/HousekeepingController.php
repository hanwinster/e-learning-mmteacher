<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Notifications\DatabaseNotification as Notification;
use App\Http\Controllers\Controller;
use DB;

class HousekeepingController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updateNotificationLinks()
    {
        Notification::chunk(100, function ($posts) {
            foreach ($posts as $post) {
                $data = $post->data;
                if ($data['click_action_link']) {
                    $data['click_action_link'] = str_replace('http://150.95.27.212/unesco_elibrary_v3/public', config('app.url'), $data['click_action_link']);
                }

                $post->data = $data;
                $post->save();
            }
        });

        return 'Successfully updated the links.';
    }
}
