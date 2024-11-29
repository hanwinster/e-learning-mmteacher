<?php

namespace App\Http\Controllers\API\Guest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Http\Resources\ArticleCollection;

class ArticleCategoryController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug, Request $request)
    {
        if (!$category = ArticleCategory::isPublished()
                                        ->where('slug', $slug)
                                        ->first()) {
            return response()->json(['message' => 'Resource Not Found'], 404);
        }

        if ($request->order) {
            $posts = Article::isCategory($category->id)->isPublished()
            ->orderBy('updated_at', $request->order)
            ->paginate();
        } else {
            $posts = Article::isCategory($category->id)->isPublished()->orderBy('weight', 'desc')
            ->paginate();
        }

        return new ArticleCollection($posts); //(ArticleResource::collection( $posts ));
    }
}
