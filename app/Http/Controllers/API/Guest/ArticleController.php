<?php

namespace App\Http\Controllers\API\Guest;

use App\Models\Article;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticlePartialResource;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Article::with(['category', 'media'])
            ->withCategory(request('category_id'))
            ->withSearch(mm_search_string(request('search')))
            ->isPublished()
            ->sortable(['created_at' => 'desc'])
            ->paginate();

        return ArticlePartialResource::collection($posts);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!$post = Article::with('category')->isPublished()
                                            ->where('id', $id)
                                            ->first()) {
            return response()->json(['message' => 'Resource Not Found'], 404);
        }

        return new ArticleResource($post);
    }
}
