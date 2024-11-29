<?php

namespace App\Repositories;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqRepository
{
    protected $model;

    public function __construct(Faq $model)
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
						->withCategory($request->input('category_id'))
						->sortable(['updated_at' => 'desc'])
						->paginate($request->input('limit'));

        $posts->appends($request->all());

        return $posts;
    }

    public static function getPublishedFaqs()
    {
        $repository = new self(new Faq());

        $posts = $repository->model
							->isPublished()
							->latest()
							->limit(5)
							->get();

        return $posts;
    }
	
	/* KHK Start */
	public function apiPublishedOnly(Request $request)
    {
        return $this->model
						//->withSearch($request->input('search'))
						->sortable(['updated_at' => 'desc'])
						->isPublished()
						->paginate(10);
    }
	/* KHK End */

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
        return $this->model->get()->pluck('name', 'id');
    }

    public static function getItemList()
    {
        $repository = new self(new Faq());
        $posts = $repository->getItems();
        $posts->prepend('- Select Faq -', '');

        return $posts;
    }

    public function findBySlug($slug)
    {
        if (!$post = $this->model->where('published', true)->where('slug', $slug)->first()) {
            abort(404, 'Resource Not Found');
        }

        return $post;
    }
	
	public function findById($id)
    {
        if (!$post = $this->model->where('published', true)->where('id', $id)->first()) {
            abort(404, 'Resource Not Found');
        }

        return $post;
    }

    public function findByCategoryId($category_id)
    {
        $posts = $this->model->where('published', true)->where('category_id', $category_id)->paginate(10);

        return $posts;
    }

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

		/*
        if ($request->file('uploaded_file')) {
            $this->model->addMediaFromRequest('uploaded_file')->toMediaCollection('Faqs');
        }
		*/
    }
}
