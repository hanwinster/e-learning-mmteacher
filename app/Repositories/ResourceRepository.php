<?php

namespace App\Repositories;

use App\Models\Resource;
use App\Models\Privacy;
use App\Models\Keyword;
use App\Models\College;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use File;
use GuzzleHttp\Client as Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use DB;
use Carbon\Carbon;
use App\Models\ResourcePrivacy;
use App\Jobs\UploadVideoToVimeo;
use Illuminate\Support\Facades\Log;

class ResourceRepository
{
    protected $model;

    public function __construct(Resource $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $posts = $this->model
            ->with(['media', 'user', 'approver'])
            ->withSearch($request->input('search'))
            ->withApprovalStatus($request->input('approval_status'))
            // ->where('approval_status', '!=', null)
            ->where(function ($query) {
                $query->where('user_id', '=', auth()->user()->id);
                $query->orWhere(function ($query) {
                    $query->where('approval_status', '!=', null)
                        ->where('user_id', '!=', auth()->user()->id);
                });
            })
            ->withUploadedBy($request->input('uploaded_by'))
            ->withSubject($request->input('subject_id'))
            ->withResourceFormat($request->input('resource_format'))
            //->isPublished()
            ->orderBy('id', 'desc')
            // ->sortable(['updated_at' => 'desc'])
            ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return $posts;
    }

    public function searchResources(Request $request)
    {

        $posts = $this->model
            // ->with(['media'])
            ->withSearch($request->input('search'))
            //->withSubject($request->input('subject'))
            //->withResourceFormat($request->input('resource'))
            ->isPublished()
            ->sortable(['updated_at' => 'desc'])
            ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return $posts;
    }

    public static function getResourcesWithFormat($format)
    {
        return Resource::where('resource_format', $format)->privacyFilter(currentUserType())->orderBy('id', 'desc')->paginate(10);
    }

    public function increaseHitCount($resourceId)
    {
        DB::table('resources')
            ->where('id', $resourceId)
            ->increment('total_page_views');
    }

    public function decreaseHitCount($resourceId)
    {
        DB::table('resources')
            ->where('id', $resourceId)
            ->decrement('total_page_views');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForMember(Request $request)
    {
        $posts = $this->model
            // ->with(['media'])
            ->ofUser(auth()->user()->id)
            ->withSearch($request->input('search'))
            ->withSubject($request->input('subject_id'))
            ->withResourceFormat($request->input('resource_format'))
            ->sortable(['updated_at' => 'desc'])
            ->paginate($request->input('limit')); //dd(auth()->user()->id);

        $posts->appends($request->all());

        return $posts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForManager(Request $request)
    {
        $user = auth()->user();

        if ($college = College::find($user->ec_college)) {
            $posts = $this->model
                // ->with(['media'])
                ->whereHas('user', function ($q) use ($user) {
                    $q->where('ec_college', '=', $user->ec_college);
                })
                ->withSearch($request->input('search'))
                ->withSubject($request->input('subject_id'))
                ->withApprovalStatus($request->input('approval_status'))
                // ->where('approval_status', '!=', null)
                ->where(function ($query) {
                    $query->where('user_id', '=', auth()->user()->id);
                    $query->orWhere(function ($query) {
                        $query->where('approval_status', '!=', null)
                            ->where('user_id', '!=', auth()->user()->id);
                    });
                })
                ->withResourceFormat($request->input('resource_format'))
                ->sortable(['updated_at' => 'desc'])
                ->paginate($request->input('limit'));

            $posts->appends($request->all());
        }

        return $posts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForPublic(Request $request, $user_type = User::TYPE_GUEST, $limit = null)
    {

        $posts = $this->model
            ->with(['media'])
            ->withSearch($request->input('search'))
            ->isPublished()
            ->isApproved()
            ->sortable(['updated_at' => 'desc'])
            ->privacyFilter($user_type)

            ->paginate($request->input('limit') ?? $limit);

        $posts->appends($request->all());

        return $posts;
    }

    public function indexForPublicFeatured(Request $request, $user_type = User::TYPE_GUEST, $limit = null)
    {
        $posts = $this->model
            ->with(['media', 'privacies'])
            ->withSearch($request->input('search'))
            ->isPublished()
            ->isApproved()
            ->isFeatured()
            ->sortable(['created_at' => 'desc'])
            ->privacyFilter($user_type)

            ->paginate($request->input('limit') ?? $limit);

        $posts->appends($request->all());

        return $posts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchForPublic(Request $request, $user_type = User::TYPE_GUEST)
    {

        $posts = $this->model
            ->withSearch($request->input('q'))
            ->isPublished()
            ->isApproved()
            ->sortable(['updated_at' => 'desc'])
            ->privacyFilter($user_type)

            ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return $posts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function relatedResourcesBySubjects($resource, $user_type = User::TYPE_GUEST)
    {
        $subjects = $resource->subjects->pluck('id')->toArray();

        $posts = $this->model
            ->withSubjectList($subjects)
            ->isPublished()
            ->isApproved()
            ->orderBy('updated_at', 'desc')
            ->privacyFilter($user_type)
            ->limit(10)
            ->get();

        return $posts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function relatedResources($resource, $user_type = User::TYPE_GUEST)
    {
        $list = null;

        if (isset($resource->related)) {
            $posts = $resource->related;
            $list = collect([]);

            $posts->each(function ($post) use (&$list) {
                if (isset($post->resource) && $post->resource->published == 1) {
                    $list[] = $post->resource;
                }
            });
        }

        return $list;
    }

    public static function getLatestPublishedResources($user_type = User::TYPE_GUEST, $slug = null)
    {
        $repository = new self(new Resource());

        $posts = $repository->model
            ->privacyFilter($user_type)
            ->withoutCurrentResource($slug)
            ->isPublished()
            ->latest()
            ->isApproved()
            ->limit(6)
            ->get();

        return $posts;
    }

    public function publishedOnly(Request $request)
    {
        return $this->model
            ->isPublished()
            ->isApproved()
            ->paginate();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findBySlug($slug, $user_type = User::TYPE_GUEST)
    {
        if (!$post = $this->model->where('published', 1)
            ->where('slug', $slug)
            ->isApproved()
            ->privacyFilter($user_type)
            ->first()) {
            abort(404);
        }

        return $post;
    }

    public function findById($id, $user_type = User::TYPE_GUEST)
    {
        if (!$post = $this->model->where('published', 1)
            ->where('id', $id)
            ->isApproved()
            ->privacyFilter($user_type)
            ->first()) {
            return null;
        }

        return $post;
    }

    public function getKeyId()
    {
        return $this->model->id;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getItems()
    {
        return $this->model->get()->pluck('title', 'id');
    }

    public static function getItemList()
    {
        $repository = new self(new Resource());
        $posts = $repository->getItems();
        $posts->prepend('-Select Resource-', '');

        return $posts;
    }

    public function getAdvancedSearchKeys()
    {
        return $this->model->advanced_search_keys();
    }

    public static function getAdvancedSearchKeysList()
    {
        $repository = new self(new Resource());

        $posts = $repository->getAdvancedSearchKeys();
        $posts = ['- Select Advanced Search -'] + $posts;
        return $posts;
    }

    public static function getAdvancedSearchList()
    {
        $repository = new self(new Resource());

        return $repository->getAdvancedSearchKeys();
    }

    public function getCollegeYears()
    {
        return $this->model->collegeYears();
    }

    public static function getEducationCollegeYears()
    {
        $repository = new ResourceRepository(new Resource());
        return $repository->getCollegeYears();
    }

    public function cloneResource($id)
    {
        $post = $this->find($id);
        $clone = $post->replicate();
        $clone->slug = '';
        $clone->title = 'New version of ' . $clone->title;
        $clone->original_resource_id = $post->id;
        $clone->user_id = auth()->user()->id;
        $clone->approval_status = null;
        $clone->published = 0;
        $clone->allow_edit = 1;
        $clone->is_locked = 0;
        $clone->approved_at = null;
        $clone->approved_by = null;
        $clone->total_page_views = 0;
        $clone->total_downloads = 0;
        $clone->save();

        $clone->subjects()->attach($post->subjects);
        $clone->years()->attach($post->years);
        $clone->privacies()->saveMany($post->privacies);

        return $clone;
    }

    public function saveRecord($request, $id = null)
    {
        if (isset($id)) {
            $this->model = $this->find($id);
        } else {
            $this->model->user_id = auth()->user()->id;
        }

        $this->model->fill($request->all());

        if ($request->input('published') !== null) {
            $this->model->published = $request->input('published', false);
        }

        // disable publishing of new resource for Teacher Educator Users
        if (currentUserType() == User::TYPE_TEACHER_EDUCATOR && !isset($id)) {
            $this->model->published = 0;
        }

        //dd($this->model);

        if ($id == null) {
            $this->model->allow_edit = 1;
        }

        if ($request->input('is_featured')) {
            $this->model->is_featured = $request->input('is_featured', 0);
        }

        if ($request->input('approval_status') !== null) {
            $this->model->approval_status = $request->input('approval_status', null);

            if ($this->model->approval_status != null && $this->model->approval_status != 0) {
                $this->model->approved_at = Carbon::now();
            }
        }

        /*
        ** No longer use "apporved". Use "approval_status" instead.
        if ($request->input('approved') !== null) {
            $this->model->approved = $request->input('approved', false);

            if ($request->input('approved') == '1' && $this->model->approved_at == null) {
                //if ( ($this->model) && $this->model->approved_at == null)
                //{
                $this->model->approved_at = Carbon::now();
                //}
            }
        }
        */
        $this->model->save();

        // Privacy
        //$privacy->resource()->associate($this->model);
        //$this->model->privacies()->sync($request->input('user_type'));

        $old_privacies = ResourcePrivacy::where('resource_id', $id)->get();

        foreach ($old_privacies as $old_privacy) {
            $old_privacy->delete();
        }

        if ($request->input('user_type')) {
            foreach ($request->input('user_type') as $user_type) {
                $privacy = new ResourcePrivacy();
                $privacy->resource_id = $this->model->id;
                //$privacy->role_id = auth()->user()->id;
                $privacy->user_type = $user_type;
                $privacy->save();
            }
        }

        // Subjects
        $this->model->subjects()->sync($request->input('subjects'));

        if ($request->input('keywords')) {
            Keyword::tagResource($this->model, $request->input('keywords'), 'creator');
        }

        if ($request->input('keywords_other')) {
            Keyword::tagResource($this->model, $request->input('keywords_other'), 'other');
        }

        if ($request->input('suitable_for_ec_year') !== null) {
            $this->model->suitable_for_ec_year = implode(',', $request->input('suitable_for_ec_year'));
            $this->model->years()->sync($request->input('suitable_for_ec_year'));
        }

        if ($request->file('cover_image')) {
            $this->model->addMediaFromRequest('cover_image')->toMediaCollection('resource_cover_image');
        }

        // Upload to Vimeo (if the file is video)
        $allowExtensions = ['mp4', 'mpg', 'mpeg', 'wmv', 'avi', 'mov'];

        if ($request->file('previews')) {
            $extension = $request->file('previews')->getClientOriginalExtension(); //->getMimeType();

            // Store media file first
            $this->model->addMediaFromRequest('previews')->toMediaCollection('resource_previews');

            if (in_array($extension, $allowExtensions)) {
                dispatch(new UploadVideoToVimeo($this->model, 'resource_previews'));
            }
        }

        if ($request->file('full_version_files')) {
            $extension = $request->file('full_version_files')->getClientOriginalExtension();

            // Store media file first
            $med = $this->model->addMediaFromRequest('full_version_files')->toMediaCollection('resource_full_version_files');


            if (in_array($extension, $allowExtensions)) {
                dispatch(new UploadVideoToVimeo($this->model, 'resource_full_version_files'));
            } else {

                $media = $this->model->getMedia('resource_full_version_files');

                // dd($result);
                foreach ($media->toArray() as $val) {
                    $client = new \Google_Client();
                    $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
                    $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
                    $client->refreshToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));
                    $service = new \Google_Service_Drive($client);

                    // $fileMetadata = new \Google_Service_Drive_DriveFile(array(
                    //     'name' => 'ExpertPHP',
                    //     'mimeType' => 'application/vnd.google-apps.folder'));
                    // $folder = $service->files->create($fileMetadata, array(
                    //     'fields' => 'id'));
                    // printf("Folder ID: %s\n", $folder->id);

                    // $fileName = $request->file('resource_file')->getClientOriginalName();
                    $file = new \Google_Service_Drive_DriveFile(array(
                        'name' => $val['file_name'],
                        'parents' => array(env('GOOGLE_DRIVE_FOLDER_ID'))
                    ));
                    $result = $service->files->create($file, array(
                        'data' => file_get_contents(public_path("storage/" . $val['id'] . "/" . $val['file_name'])),
                        'mimeType' => 'application/octet-stream',
                        'uploadType' => 'media'
                    ));

                    // $this->model->res_link = 'https://drive.google.com/open?id=' . $result->id;
                    $med->setCustomProperty('gdrive_link', 'https://drive.google.com/open?id=' . $result->id);
                    // get url of uploaded file
                    // $url = 'https://drive.google.com/open?id=' . $result->id;
                    // echo $url;
                }
                $med->save();
            }
        }
    }

    public static function deleteVimeoVideo($video_id)
    {
        // API
        $headers = ['Authorization' => 'bearer ' . config('cms.vimeo_access_token')];

        $json = [];

        $body = json_encode($json);

        $client = new Client();

        $guzzle_request = new GuzzleRequest('DELETE', 'https://api.vimeo.com/videos/' . $video_id, $headers, $body);

        try {
            $client->send($guzzle_request, ['timeout' => 200]);
        } catch (RequestException $e) {
            echo $e->getMessage() . "\n";
            echo $e->getRequest()->getMethod();
        }
    }

    public static function getVimeoDownloadUrl($mediaItem)
    {
        $videoId = $mediaItem->getCustomProperty('video_id');

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'bearer ' . config('cms.vimeo_access_token')
        ];

        $client = new Client();

        $guzzle_request = new GuzzleRequest('GET', 'https://api.vimeo.com/me/videos/' . $videoId, $headers);

        try {
            $response = $client->send($guzzle_request, ['timeout' => 200]);

            $statusCode = (int)$response->getStatusCode();

            if ($statusCode == 200) {
                $result = json_decode($response->getBody()->getContents(), true);

                return $result['download'][0]['link'];
            } elseif ($statusCode >= 400 && $statusCode <= 451) {
                throw new ClientException('4xx error occurs.');
            } elseif ($statusCode >= 500 && $statusCode <= 511) {
                throw new ServerException('5xx error occurs.');
            }
        } finally {
            Log::info('Finally');
        }

        return null;
    }

    public function getDefaultRightsForResourceForm($user_type)
    {
        $rights = [];

        switch ($user_type) {
            case User::TYPE_ADMIN:
                $rights = [
                    User::TYPE_ADMIN
                ];
                break;

            case User::TYPE_MANAGER:
                $rights = [
                    User::TYPE_ADMIN, User::TYPE_MANAGER
                ];
                break;
            case User::TYPE_TEACHER_EDUCATOR:
                $rights = [
                    User::TYPE_ADMIN, User::TYPE_MANAGER, User::TYPE_TEACHER_EDUCATOR
                ];
                break;
        }

        return $rights;
    }
}
