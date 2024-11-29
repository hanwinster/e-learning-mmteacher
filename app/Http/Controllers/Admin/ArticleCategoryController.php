<?php

namespace App\Http\Controllers\Admin;

use App\Models\ArticleCategory;
use App\Http\Requests\RequestArticleCategory as Request;
use App\Http\Controllers\Controller;
use App\Repositories\ArticleCategoryRepository;
use Spatie\MediaLibrary\Models\Media;

class ArticleCategoryController extends Controller
{
    protected $repository;

    public function __construct(ArticleCategoryRepository $repository)
    {
        $this->repository = $repository;
		/* KHK Start */
		$this->middleware('permission:view_article_category',['only' => 'view']);
        $this->middleware('permission:add_article_category', ['only' => ['create','store']]);
        $this->middleware('permission:edit_article_category', ['only' => ['edit','update']]);
        $this->middleware('permission:delete_article_category', ['only' => ['destroy']]);
		/* KHK End */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->repository->index(request());

        return view('backend.article-category.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.article-category.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\RequestArticleCategory $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validated();

        $this->repository->saveRecord($request);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSave')) {
            return redirect()->route('admin.article-category.index')
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        } elseif ($request->input('btnApply')) {
            return redirect()->route('admin.article-category.edit', $id)
              ->with(
                  'success',
                ' #' . $id . ' has been successfully saved.'
              );
        } else {
            return redirect()->route('admin.article-category.index')
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
    public function edit($id)
    {
        $post = $this->repository->find($id);

        return view('backend.article-category.form', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\RequestArticleCategory $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validated();

        $this->repository->saveRecord($request, $id);

        if ($request->input('btnSave')) {
            return redirect()->route('admin.article-category.index')
              ->with(
                  'success',
                ' #' . $id . ' has been successfully updated.'
              );
        } elseif ($request->input('btnApply')) {
            return redirect()->route('admin.article-category.edit', $id)
              ->with(
                  'success',
                ' #' . $id . ' has been successfully updated.'
              );
        } else {
            return redirect()->route('admin.article-category.index')
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
		
		/* KHK Start */
		$medias = Media::where('model_type', 'App\Models\ArticleCategory')->where('model_id', $id)->get();
		
		if (count($medias) > 0)
		{
			foreach($medias as $media) {
				$media->delete();
			}
		}
		/* KHK End */

        $post->delete();

        return redirect()->route('admin.article-category.index')
          ->with('success', 'Successfully deleted');
    }
}
