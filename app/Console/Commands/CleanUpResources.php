<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Resource;
use Illuminate\Notifications\DatabaseNotification as Notification;

class CleanUpResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elibrary:clean-up-resources';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up resources and related approval requests, privacies, related resources, likes, reviews and notifications.';

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
        Resource::whereNotNull('deleted_at')
            ->withCount(['favourites', 'reviews', 'approvalRequests', 'related', 'privacies'])
            ->chunk(100, function ($posts) {
                $this->info($posts->count(). " resources were deleted.");

                foreach ($posts as $post) {
                    $this->info($post->id. " - ".$post->title);
                    $this->info('- '. $post->favourites_count. " Favourites");
                    $this->info('- '. $post->reviews_count. " Reviews");
                    $this->info('- '. $post->related_count. " Related");
                    $this->info('- '. $post->privacies_count. " Privacies");
                    $this->info('- '. $post->approvalRequests_count. " Approval Requests");

                    //$post->clearMediaCollection();
                    $this->info($post->deleted_at);
                    $post->delete();
                }
                $this->info($posts->count(). " resources were deleted.");
            });

        $this->info('Successfully cleaned up the resources.');
    }
}
