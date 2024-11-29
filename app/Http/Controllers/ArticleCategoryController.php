<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Repositories\ArticleRepository;
use App\Repositories\SlideRepository;

class ArticleCategoryController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if (!$category = ArticleCategory::isPublished()
                ->where('slug', $slug)
                ->first()) {
            abort(404);
        }

        $categories = ArticleCategory::isPublished()->get();
        $posts = Article::with(['media', 'category'])->isCategory($category->id)->isPublished()->latest()->paginate();
        $latest_articles = ArticleRepository::getPublishedArticles();
        return view('frontend.article.category', compact('posts', 'category', 'categories', 'latest_articles'));
    }
}
