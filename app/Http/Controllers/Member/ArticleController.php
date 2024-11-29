<?php

namespace App\Http\Controllers\Member;

use App\User;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Http\Requests\RequestArticle as Request;
use App\Http\Controllers\Controller;
use App\Repositories\ArticleRepository;
use App\Repositories\ArticleCategoryRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ArticlePosted;

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
        $user_type = currentUserType();

        if ($user_type == User::TYPE_ADMIN) {
            $posts = $this->repository->index(request());
        } elseif ($user_type == User::TYPE_MANAGER) {
            $posts = $this->repository->indexForManager(request());
        }

        $categories = ArticleCategoryRepository::getItemList();

        return view('frontend.member.article.index', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ArticleCategoryRepository::getItemList();

        return view('frontend.member.article.form', compact('categories'));
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
            return redirect()->route('member.article.index')
              ->with(
                  'success',
                  ' #' . $id . ' has been successfully saved.'
              );
        } elseif ($request->input('btnSaveNotify')) {
            if (config('cms.enable_article_notification')) {
                // Notify to all active users
                $users = UserRepository::getActiveUsers();

                Notification::send($users, new ArticlePosted($this->repository->getModel()));
            }

            return redirect()->route('member.article.index')
              ->with(
                  'success',
                  ' #' . $id . ' has been successfully saved and notified to all active users.'
              );
        } else {
            return redirect()->route('member.article.index')
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

        $categories = ArticleCategoryRepository::getItemList();

        return view('frontend.member.article.form', compact('post', 'categories'));
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
            return redirect()->route('member.article.index')
              ->with(
                  'success',
                  ' #' . $id . ' has been successfully updated.'
              );
        } elseif ($request->input('btnSaveNotify')) {
            if (config('cms.enable_article_notification')) {
                // Notify to all active users
                $users = UserRepository::getActiveUsers();

                Notification::send($users, new ArticlePosted($this->repository->getModel()));
            }

            return redirect()->route('member.article.index')
              ->with(
                  'success',
                  ' #' . $id . ' has been successfully updated and notified to all active users.'
              );
        } else {
            return redirect()->route('member.article.index')
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

        return redirect()->route('member.article.index')
          ->with('success', 'Successfully deleted');
    }
}
