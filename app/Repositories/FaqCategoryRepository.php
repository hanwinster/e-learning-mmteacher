<?php

namespace App\Repositories;

use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqCategoryRepository
{
    protected $model;

    public function __construct(FaqCategory $model)
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
        return $this->model->get()->pluck('title', 'id');
    }

    public static function getItemList()
    {
        $repository = new self(new FaqCategory());
        $posts = $repository->getItems();
        $posts->prepend('- Select Category -', '');

        return $posts;
    }

    public function findBySlug($slug)
    {
        if (!$post = $this->model->where('published', true)->where('slug', $slug)->first()) {
            abort(404, 'Resource Not Found');
        }

        return $post;
    }
	
	/* KHK Start */
	public function publishedOnly(Request $request)
    {
        return $this->model->with('faqs')
							->isPublished()
							->paginate();
    }	
	
	public function apiPublishedOnly(Request $request)
    {
        return $this->model//->with('faqs')
							//->withSearch($request->input('search'))
							->sortable(['updated_at' => 'desc'])
							->isPublished()
							->paginate(10);
    }
	
	/* KHK End */

    public function saveRecord($request, $id = null)
    {
        if (isset($id)) {
            $this->model = $this->find($id);
        }

        $this->model->fill($request->all());

        if ($request->input('published') !== null) {
            $this->model->published = $request->input('published', false);
        }

        $this->model->save();
    }
}
