<?php

namespace App\Http\Controllers\API\Guest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ResourceRepository;
use App\Models\Resource;
use App\Http\Resources\ResourceResource;

class ResourceController extends Controller
{
    protected $repository;
    protected $user_type;

    public function __construct(ResourceRepository $repository)
    {
        $this->repository = $repository;
        $this->user_type = currentUserType();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $posts = $this->repository->indexForPublic(request(), currentUserType());

        // dd($posts);
        return ResourceResource::collection($posts);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!$post = $this->repository->findById($id, $this->user_type)) {
            return response()->json(['message' => 'Resource Not Found'], 404);
        }

        $this->repository->increaseHitCount($post->id);
        $post->total_page_views = $post->total_page_views + 1;

        return new ResourceResource($post);
    }
}
