<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Http\Requests\RequestArticle as Request;
use App\Http\Controllers\Controller;
use App\Repositories\ArticleRepository;
use App\Repositories\ArticleCategoryRepository;

class ArticleController extends Controller
{
    protected $repository;

    public function __construct(ArticleRepository $repository)
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
        $posts = $this->repository->index(request());

        $categories = ArticleCategoryRepository::getItemList();

        return view('backend.article.index', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ArticleCategoryRepository::getItemList();

        return view('backend.article.form', compact('categories'));
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

        $this->repository->saveRecord($request);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSave')) {
            return redirect()->route('admin.article.index')
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        } elseif ($request->input('btnApply')) {
            return redirect()->route('admin.article.edit', $id)
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        } else {
            return redirect()->route('admin.article.index')
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $lang = 'en')
    {
        $post = $this->repository->find($id);

        $categories = ArticleCategoryRepository::getItemList();

        return view('backend.article.form', compact('post', 'categories'));
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
        $this->repository->saveRecord($request, $id);

        if ($request->input('btnSave')) {
            return redirect()->route('admin.article.index')
              ->with(
                  'success',
                ' #' . $id . ' has been successfully updated.'
              );
        } elseif ($request->input('btnApply')) {
            return redirect()->route('admin.article.edit', $id)
              ->with(
                  'success',
                ' #' . $id . ' has been successfully updated.'
              );
        } else {
            return redirect()->route('admin.article.index')
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->repository->find($id);

        $post->delete();

        return redirect()->route('admin.article.index')
          ->with('success', 'Successfully deleted');
    }
}
