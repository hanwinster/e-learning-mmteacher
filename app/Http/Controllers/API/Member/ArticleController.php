<?php

namespace App\Http\Controllers\API\Member;

use App\Models\Article;
use App\User;
use App\Http\Controllers\Controller;
use App\Repositories\ArticleRepository;
use App\Http\Requests\RequestArticle as Request;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticlePartialResource;

class ArticleController extends Controller
{
    protected $resource;

    public function __construct(ArticleRepository $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_type = currentUserType();

        if ($user_type == User::TYPE_ADMIN) {
            $posts = $this->resource->index(request());
        } elseif ($user_type == User::TYPE_MANAGER) {
            $posts = $this->resource->indexForManager(request());
        }

        return ArticlePartialResource::collection($posts);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Article::where('id', $id)->first();

        if (!$post) {
            return response()->json(['message' => 'Resource Not Found'], 404);
        }

        return new ArticleResource($post);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validated();

        $this->resource->saveRecord($request);

        $id = $this->resource->getKeyId();

        // $json = ['data' => $this->resource->getModel(), 'message' => 'Resource has been successfully saved.'];

        return new ArticleResource($this->resource->getModel());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validated();

        $this->resource->saveRecord($request, $id);

        // $json = ['data' => $this->resource->getModel(), 'message' => 'Resource has been successfully updated.'];

        return new ArticleResource($this->resource->getModel());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->resource->find($id);

        $post->delete();

        $json = ['message' => 'Successfully deleted'];

        return response()->json($json);
    }
}
