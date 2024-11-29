<?php

namespace App\Http\Controllers\API\Guest;

use App\Models\Page;
use App\Http\Requests\RequestSlide as Request;
use App\Http\Controllers\Controller;
use App\Repositories\SlideRepository;
use App\Http\Resources\SlideResource;
use App\Http\Resources\SlideCollection;

class SlideController extends Controller
{
    protected $repository;

    public function __construct(SlideRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->repository->apiPublishedOnly(request());

        return new SlideCollection($posts);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        try {
            $post = $this->repository->find($id);
            return new SlideResource($post);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['code' => 404, 'message' => 'Resource Not Found'], 404);
        }
        
    }
}
