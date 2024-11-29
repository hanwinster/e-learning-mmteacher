<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Models\Resource;
use DB;
use Log;

class CleanUpForUserExcept extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elibrary:clean-up-for-user-except';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all users and related records except provided User ID(s).';

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
        // $userIds = $this->argument('userId');

        // $userId = $this->ask('Enter User Id to delete related records.');
        $inputs = $this->ask('Please enter User ID you want to keep from deletion in comma separate format. eg., 1,2,3');

        // Explode roles
        $userIds = explode(',', $inputs);

        if (!count($userIds)) {
            $this->info('There are no users to delete.');
            return false;
        }

        $users = User::whereNotIn('id', $userIds)->get();

        if (!count($users)) {
            $this->info('There are no users to delete.');
            return false;
        }

        $this->info(count($users) .' user(s) found. Start deleting now...');

        $bar = $this->output->createProgressBar(count($users));
        $bar->start();

        foreach ($users as $user) {
            $userId = $user->id;
            $user->delete();

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

            Log::info('Deleted related records for User #' . $userId);
            $this->info('Deleted related records for User #' . $userId);

            $bar->advance();
        }

        $bar->finish();

        $this->info(count($users) .' user(s) were deleted');

        $this->info('Successfully cleaned up the resources.');
    }
}
