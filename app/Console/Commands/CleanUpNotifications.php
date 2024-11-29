<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification as Notification;

class CleanUpNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elibrary:clean-up-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up links in Notifications';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Notification::chunk(100, function ($posts) {
            foreach ($posts as $post) {
                $data = $post->data;
                if ($data['click_action_link']) {
                    $data['click_action_link'] = str_replace(env('STAGING_URL', ''), config('app.url'), $data['click_action_link']);
                }

                $post->data = $data;
                $post->save();
            }
        });

        $this->info('Successfully cleaned up the links.');
    }
}
