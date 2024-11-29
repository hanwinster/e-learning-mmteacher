<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ResourceRepository;
use App\Repositories\SubjectRepository;
use App\Models\Resource;
use App\Repositories\SlideRepository;

class SearchController extends Controller
{
	protected $resource;
	protected $subject;
	protected $slide;
	protected $user_type;	

    public function __construct(ResourceRepository $resource, SubjectRepository $subject, SlideRepository $slide)
    {
        $this->resource = $resource;
		$this->subject = $subject;
		$this->slide = $slide;
		
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
        $posts = $this->resource->indexForPublic(request(), $this->user_type); //->searchResources(request()); //->publishedOnly(request());
        $subjects = $this->subject->getAllPublished();
		$slides = $this->slide->getPublishedSlides(5);
		$formats = Resource::RESOURCE_FORMATS;

        return view('frontend.search.index', compact('posts', 'subjects', 'slides', 'formats'));
    }
}
