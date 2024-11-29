<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ResourceRepository;
use App\Repositories\SubjectRepository;
use App\Models\Resource;
use App\User;
use Spatie\MediaLibrary\Models\Media;
//use App\Repositories\FormatRepository;
use App\Repositories\SlideRepository;


use GuzzleHttp\Client as Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Log;

class ResourceController extends Controller
{
    protected $repository;
    protected $subject;
    protected $slide;
    protected $user_type;

    public function __construct(ResourceRepository $repository, SubjectRepository $subject, SlideRepository $slide)
    {
        $this->repository = $repository;
        $this->subject = $subject;
        $this->slide = $slide;
        //$this->user_type = currentUserType();

        $this->middleware(function ($request, $next) {
            $this->user_type = currentUserType();//Auth::user()->projects;

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->repository->indexForPublic(request(), $this->user_type);
        $slides = $this->slide->getPublishedSlides(5);
        $subjects = $this->subject->getAllPublished();
        $formats = Resource::RESOURCE_FORMATS;

        return view('frontend.resource.index', compact('posts', 'subjects', 'slides', 'formats'));
    }

    public function indexByFormat($format)
    {
        $posts = $this->repository->getResourcesWithFormat($format);
        $slides = $this->slide->getPublishedSlides(5);
        $subjects = $this->subject->getAllPublished();
        $formats = Resource::RESOURCE_FORMATS;

        return view('frontend.resource.index', compact('posts', 'subjects', 'slides', 'formats'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = $this->repository->findBySlug($id, $this->user_type);

        $this->repository->increaseHitCount($post->id);
        $post->total_page_views = $post->total_page_views + 1;

        $format_list = '';  //FormatRepository::getAllPublished();
        $subject_list = $this->subject->getAllPublished();
        $advanced_list = ''; //ResourceRepository::getAdvancedSearchList();

        $related_resources = $this->repository->relatedResources($post, $this->user_type);

        /*
        if (!$related_resources->count()) {
            $related_resources = $this->repository->relatedResourcesBySubjects($post, $this->user_type);
        }
        */

        $resource_formats = Resource::RESOURCE_FORMATS;

        $years = $this->repository->getEducationCollegeYears();
        $keywords = $post->keywordList();
        $otherKeywords = $post->keywordList('other');
     
        return view(
            'frontend.resource.show',
            compact('post', 'format_list', 'advanced_list', 'subject_list', 'related_resources', 'resource_formats', 'years', 'keywords', 'otherKeywords')
        );
    }

    public function download($mediaId)
    {
        $mediaItem = Media::findOrFail($mediaId);

        $resourceId = $mediaItem->model_id;

        if ($post = Resource::find($resourceId)) {
            $post->total_downloads = $post->total_downloads + 1;
            $post->save();
        }

        // if ($mediaItem->getCustomProperty('download_url')) {
        if ($mediaItem->getCustomProperty('video_id')
            && $downloadUrl = ResourceRepository::getVimeoDownloadUrl($mediaItem)) {
            // return redirect()->away($mediaItem->getCustomProperty('download_url'));
            return redirect()->away($downloadUrl);
        } else {
            return response()->download($mediaItem->getPath(), $mediaItem->file_name);
        }
    }
}
