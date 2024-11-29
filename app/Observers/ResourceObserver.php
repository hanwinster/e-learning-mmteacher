<?php

namespace App\Observers;

use App\Models\Resource;
use App\Repositories\ResourceRepository;
use Log;
use Spatie\MediaLibrary\Models\Media;
use DB;

class ResourceObserver
{
    /**
     * Handle the resource "deleting" event.
     *
     * @param  \App\Models\Resource  $post
     * @return void
     */
    public function deleting(Resource $post)
    {
        // Delete related records
        DB::table('favourites')->where('resource_id', $post->id)->delete();
        DB::table('featured_resource')->where('resource_id', $post->id)->delete();
        DB::table('keyword_resource')->where('resource_id', $post->id)->delete();
        DB::table('link_reports')->where('resource_id', $post->id)->delete();
        DB::table('related_resources')
            ->where('resource_id', $post->id)
            ->orWhere('related_resource_id', $post->id)
            ->delete();

        // Delete Approval Request notifications
        if ($requests = DB::table('requests')->where('resource_id', $post->id)->get()) {
            foreach ($requests as $request) {
                DB::table('notifications')->where('model', 'ApprovalRequest')
                    ->where('model_id', $request->id)
                    ->delete();
            }
        }

        DB::table('requests')->where('resource_id', $post->id)->delete();

        DB::table('resource_privacy')->where('resource_id', $post->id)->delete();
        DB::table('resource_subject')->where('resource_id', $post->id)->delete();
        DB::table('resource_year')->where('resource_id', $post->id)->delete();
        DB::table('feedbacks')->where('resource_id', $post->id)->delete();

        // Delete notifications
        DB::table('notifications')->where('model', 'Resource')->where('model_id', $post->id)->delete();

        // Delete videos uploaded in Vimeo
        $medias = Media::where('model_type', 'App\Models\Resource')->where('model_id', $post->id)->get();

        if (count($medias) > 0) {
            foreach ($medias as $media) {
                if ($media->getCustomProperty('uri')) {
                    $video_id = str_replace('/videos/', '', $media->getCustomProperty('uri'));
                    ResourceRepository::deleteVimeoVideo($video_id);
                }

                $media->delete();
            }
        }

        Log::info('Deleted related records for Resource #' . $post->id);
    }
}
