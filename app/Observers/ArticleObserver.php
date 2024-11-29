<?php

namespace App\Observers;

use App;
use App\Models\Article;
use DB;
use Spatie\MediaLibrary\Models\Media;

class ArticleObserver
{
    /**
     * Handle the article "deleting" event.
     *
     * @param  \App\Models\Article  $article
     * @return void
     */
    public function deleting(Article $article)
    {
        DB::table('notifications')->where('model', 'Article')->where('model_id', $article->id)->delete();

        // Delete videos uploaded in Vimeo
        $medias = Media::where('model_type', 'App\Models\Resource')->where('model_id', $article->id)->get();

        if (count($medias) > 0) {
            foreach ($medias as $media) {
                $media->delete();
            }
        }
    }
}
