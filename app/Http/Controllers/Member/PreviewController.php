<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ResourceRepository;
use App\Repositories\SubjectRepository;
use App\Models\Resource;
use App\User;
use Spatie\MediaLibrary\Models\Media;
use App\Repositories\SlideRepository;
use App\Models\License;

class PreviewController extends Controller
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $isPreview = true;
        $post = Resource::findOrFail($id);

        $format_list = "";  //FormatRepository::getAllPublished();
        $subject_list = $this->subject->getAllPublished();
        $advanced_list = ""; //ResourceRepository::getAdvancedSearchList();

        $latest_resources = $this->repository->getLatestPublishedResources($this->user_type, $id);

        $resource_formats = Resource::RESOURCE_FORMATS;

        $license = License::findOrFail($post->license_id);

        $years = $this->repository->getEducationCollegeYears();
        $keywords = $post->keywordList();
        $otherKeywords = $post->keywordList('other');

        return view(
            'frontend.resource.show',
            compact('post', 'isPreview', 'format_list', 'advanced_list', 'subject_list', 'latest_resources', 'resource_formats', 'license', 'years', 'keywords', 'otherKeywords')
        );
    }
}
