<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use File;
use GuzzleHttp\Client as Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Log;

class UploadVideoToVimeo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $resource;
    protected $collection_name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($resource, $collection_name)
    {
        $this->resource = $resource;
        $this->collection_name = $collection_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Upload video to Vimeo
        $media = $this->resource->getFirstMedia($this->collection_name);
        $videoUrl = $media->getFullUrl();
        Log::info('Media ID: ' . $media->id);
        Log::info($videoUrl);

        // API
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'bearer '.config('cms.vimeo_access_token')
        ];

        // https://developer.vimeo.com/api/reference/videos#edit_video
        $json = [
            'upload' => [
                'approach' => 'pull',
                // 'link' => asset('videos') . '/' . $path],
                'link' => $videoUrl,
                // 'name' => $request->file('previews')->getClientOriginalName(),
            ],
            'name' => '#' . $this->resource->id . ' - ' . $this->resource->title,
            'description' => 'eLibrary Vimeo Upload',
            'privacy' => [
                'download' => true,
                'view' => 'unlisted'
                ]
        ];

        $body = json_encode($json);

        $client = new Client();

        $guzzle_request = new GuzzleRequest('POST', 'https://api.vimeo.com/me/videos', $headers, $body);

        try {
            $response = $client->send($guzzle_request, ['timeout' => 200]);

            $statusCode = (int)$response->getStatusCode();
            Log::info('Status Code - ' . $statusCode);

            if ($statusCode == 201) {
                $result = json_decode($response->getBody()->getContents(), true);

                $uri = $result['uri'];
                $video_url = $result['link'];
                $video_thumb = $result['pictures']['sizes'][0]['link_with_play_button'];
                $iframe = $result['embed']['html'];

                // Update Vimeo URL in custom property
                $media
                    ->setCustomProperty('video_id', str_replace('/videos/', '', $result['uri']))
                    ->setCustomProperty('uri', $result['uri'])
                    ->setCustomProperty('video_url', $result['link'])
                    ->setCustomProperty('download_url', $result['download'][0]['link'])
                    ->setCustomProperty('video_thumb', $result['pictures']['sizes'][0]['link_with_play_button'])
                    ->setCustomProperty('iframe', $result['embed']['html'])
                    ->setCustomProperty('vimeo_metadata', $result)
                    ->save();

                Log::info($result);
            } elseif ($statusCode >= 400 && $statusCode <= 451) {
                throw new ClientException('4xx error occurs.');
            } elseif ($statusCode >= 500 && $statusCode <= 511) {
                throw new ServerException('5xx error occurs.');
            }
        } finally {
            Log::info('Finally');
        }
    }
}
