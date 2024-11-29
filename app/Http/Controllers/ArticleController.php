<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Repositories\ArticleRepository;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Article::with(['media', 'category'])
            ->withSearch(mm_search_string(request('search')))
            ->isPublished()
            ->latest()
            ->paginate();

        $categories = ArticleCategory::isPublished()->get();

        return view('frontend.article.index', compact('posts', 'categories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if (!$post = Article::with(['media', 'category'])->isPublished()
                ->where('slug', $slug)
                ->first()) {
            abort(404);
        }

        $latest_articles = ArticleRepository::getPublishedArticlesWithCategory($post->category_id, $slug);
        $categories = ArticleCategory::isPublished()->get();

        return view('frontend.article.show', compact('post', 'categories', 'latest_articles'));
    }
}
