<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Http\Requests\RequestContactUs as Request;
use App\Models\Page;
use Blade;
use App\Repositories\PageRepository;
use App\Repositories\SlideRepository;

class PageController extends Controller
{
    protected $repository;

    public function __construct(PageRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if (!$post = $this->repository->findBySlug($slug)) {
            abort(404, 'Page Not Found');
        }
        return view('frontend.page.show', compact('post', 'slug'));
    }
}
