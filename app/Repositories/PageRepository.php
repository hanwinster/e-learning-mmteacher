<?php

namespace App\Repositories;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PageRepository
{
    protected $model;

    public function __construct(Page $model)
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
                        ->withSearch($request->input('search'))
                        ->sortable(['updated_at' => 'desc'])
                        ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return $posts;
    }

    public function apiPublishedOnly(Request $request)
    {
        $posts = $this->model
                        //->withSearch($request->input('search'))
                        ->sortable(['updated_at' => 'desc'])
                        ->isPublished()
                        ->paginate(10);

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

    public function getItems()
    {
        return $this->model->get()->pluck('title', 'id');
    }

    public static function getItemList()
    {
        $repository = new self(new Page());
        $posts = $repository->getItems();
        $posts->prepend('- Select Page -', '');

        return $posts;
    }

    public static function getPageBySlug($slug)
    {
        return $page = Page::where('slug', $slug)->first();
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
            $this->model->addMediaFromRequest('uploaded_file')->withCustomProperties(['file_extension' => $request->uploaded_file->extension() ])->toMediaCollection('pages');
        }
    }

    public function getBreadcrumbsTree($page)
    {
        $tree = collect([]);

        $tree->push($page);

        $paths = collect(explode('/', $page->slug));

        /*         $paths->each(function($path) use ($page, $tree) {

                    $currentPage = PageRepository::getPageBySlug($path);
                    if (isset($currentPage) && $currentPage != $page) {
                        $tree->prepend($currentPage);
                    }
                }); */

        $temp_paths = $paths->toArray();

        for ($i = 0; $i < count($paths); $i++) {
            $slug = implode('/', $temp_paths);
            array_pop($temp_paths);

            $currentPage = PageRepository::getPageBySlug($slug);

            if (isset($currentPage) && $currentPage != $page) {
                $tree->prepend($currentPage);
            }
        }

        return $tree;
    }

    public function findBySlug($slug)
    {
        try {
            $post = $this->model->where('published', true)->where('slug', $slug)->first();
            return $post;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return null;
        }

       
    }

    public static function getPageContentsBySlug($slug)
    {
        if (!$post = Page::where('published', true)->where('slug', $slug)->first()) {
            abort(404, 'Page Not Found');
        }

        return $post;
    }

    public static function getVisibleBlocks()
    {
        $all_blocks = Block::isPublished()->get();

        $blocks = $all_blocks->reject(function ($block) {
            $conditions = $block->conditions;
            $conditions = str_replace("\r", '', $conditions);
            $conditions = explode("\n", $conditions);

            if (!\Request::is(...$conditions)) {
                return $block;
            }
        });

        return $blocks;
    }

    public static function getRegions()
    {
        $regions = [
            'sidebar-left' => 'Sidebar Left',
            'sidebar-right' => 'Sidebar Right',
        ];

        // $regions = array_combine($regions, $regions);
        $regions = ['' => '-Select Region-'] + $regions;

        return $regions;
    }
}
