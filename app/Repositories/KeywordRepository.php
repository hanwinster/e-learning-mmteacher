<?php

namespace App\Repositories;

use App\Models\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class KeywordRepository
{
    protected $model;

    public function __construct(Keyword $model)
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
        return $this->model->where('published', true)->get()->pluck('keyword', 'id');
    }

    public static function getItemList()
    {
        $repository = new self(new Keyword());
        $posts = $repository->getItems();
        $posts->prepend('- Select Keyword -', '');

        return $posts;
    }

	public static function getAllPublished()
    {
        $repository = new KeywordRepository(new Keyword());

        return $repository->model->isPublished()->get();
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
    }

    public static function getBreadcrumbsTree($page)
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
