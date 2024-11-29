<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Models\Resource;
use DB;
use Log;

class CleanUpForUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elibrary:clean-up-for-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete user and related records.';

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
        // Delete related records
        //$userId = $this->argument('user');
        $userId = $this->ask('Enter User Id to delete related records.');

        if ($user = User::find($userId)) {
            $user->delete();
        } else {
            $this->info('There is no user with this ID. ' . $userId);
            return false;
        }

        Log::info('Deleting related records for User #' . $userId);
        $this->info('Deleting related records for User #' . $userId);

        DB::table('favourites')->where('user_id', $userId)->delete();
        DB::table('link_reports')->where('user_id', $userId)->delete();
        DB::table('feedbacks')->where('user_id', $userId)->delete();
        DB::table('keyword_resource')->where('user_id', $userId)->delete();
        DB::table('oauth_access_tokens')->where('user_id', $userId)->delete();
        DB::table('model_has_roles')->where('model_type', 'App\User')
            ->where('model_id', $userId)
            ->delete();

        DB::table('notifications')
            ->where('notifiable_type', 'App\User')
            ->where('notifiable_id', $userId)
            ->delete();

        DB::table('requests')->where('user_id', $userId)->delete();

        DB::table('user_subject')->where('user_id', $userId)->delete();

        foreach (Resource::where('user_id', $userId)->cursor() as $resource) {
            $resource->delete();
            $this->info('Deleted resource #' . $resource->id);
        }

        $this->info('Deleted related records for User #' . $userId);
    }
}
