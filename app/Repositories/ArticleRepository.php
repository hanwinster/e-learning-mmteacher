<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleRepository
{
    protected $model;

    public function __construct(Article $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = $this->model
                        ->with(['media'])
                        ->withSearch($request->input('search'))
                        ->withCategory($request->input('category_id'))
                        ->sortable(['created_at' => 'desc'])
                        ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return $posts;
    }

    /*
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function indexForManager(Request $request)
    {
        $user = auth()->user();

        if ($college = College::find($user->ec_college)) {
            $posts = $this->model
                       ->with(['media'])
                       ->whereHas('user', function ($q) use ($user) {
                           $q->where('ec_college', '=', $user->ec_college);
                       })
                       ->withSearch($request->input('search'))
                       ->withCategory($request->input('category_id'))
                       ->sortable(['created_at' => 'desc'])
                       ->paginate($request->input('limit'));

            $posts->appends($request->all());
        }

        return $posts;
    }

    public static function getPublishedArticles()
    {
        $repository = new self(new Article());

        $posts = $repository->model
                            ->with(['category', 'media'])
                            ->isPublished()
                            ->latest()
                            ->limit(6)
                            ->get();

        return $posts;
    }

    public static function getPublishedArticlesWithCategory($category_id, $slug)
    {
        $repository = new self(new Article());

        $posts = $repository->model
                            ->withCategory($category_id)
                            ->withoutCurrentArticle($slug)
                            ->isPublished()
                            ->latest()
                            ->limit(5)
                            ->get();

        return $posts;
    }

    public function getPublishedArticlesByCategory($categoryId)
    {
        $repository = new self(new Article());

        $posts = $repository->model
                            ->withCategory($categoryId)
                            ->with(['category', 'media'])
                           // ->withoutCurrentArticle($slug)
                            ->isPublished()
                            ->latest()
                            ->limit(2)
                            ->get();

        return $posts;
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getKeyId()
    {
        return $this->model->id;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getItems()
    {
        return $this->model->get()->pluck('title', 'id');
    }

    public static function getItemList()
    {
        $repository = new self(new Article());
        $posts = $repository->getItems();
        $posts->prepend('- Select Article -', '');

        return $posts;
    }

    public function saveRecord($request, $id = null)
    { 
        if (isset($id)) {
            $this->model = $this->find($id);
        }

        $this->model->fill($request->all());

        $this->model->user_id = Auth::id();

        if ($request->input('published') !== null) {
            $this->model->published = $request->input('published', false);
        }

        $this->model->save();

        if ($request->file('uploaded_file')) {
            $this->model->addMediaFromRequest('uploaded_file')->withCustomProperties(['file_extension' => $request->uploaded_file->extension()])->toMediaCollection('articles');
        }
    }
}
