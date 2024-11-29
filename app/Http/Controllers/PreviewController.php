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
use App\Models\License;

class PreviewController extends Controller
{
    protected $repository;
    protected $subject;

    public function __construct(ResourceRepository $repository, SubjectRepository $subject)
    {
        $this->repository = $repository;
        $this->subject = $subject;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = $this->repository->find($id);

        $format_list = "";  //FormatRepository::getAllPublished();
        $subject_list = $this->subject->getAllPublished();

        $resource_formats = Resource::RESOURCE_FORMATS;

        $years = $this->repository->getEducationCollegeYears();
        $keywords = $post->keywordList();
        $otherKeywords = $post->keywordList('other');

        return view(
            'frontend.resource.preview',
            compact('post', 'format_list', 'subject_list', 'resource_formats', 'years', 'keywords', 'otherKeywords')
        );
    }
}
