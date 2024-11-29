<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ResourceRepository;
use App\Repositories\SubjectRepository;
use App\Models\Resource;
use App\Repositories\SlideRepository;

class SubjectController extends Controller
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
        $posts = $this->repository->publishedOnly(request());
		$slides = SlideRepository::getPublishedSlides(5);
        $subjects = $this->repository->getAllPublished();

        return view('frontend.resource.index', compact('posts', 'subjects','slides'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subject = $this->subject->findBySlug($id); //dd($subject->resources());

        //$posts = $subject->resources()->privacyFilter(currentUserType())->paginate();
		//$posts = $posts->privacyFilter(currentUserType())->paginate();
		
		$posts = $this->subject->getResourcesWithSubject($subject->id);
		$slides = $this->slide->getPublishedSlides(5);
        $subjects = $this->subject->getAllPublished(); 
		$formats = Resource::RESOURCE_FORMATS;

        return view('frontend.subject.show', compact('subject', 'subjects', 'posts', 'slides', 'formats'));
    }
}
