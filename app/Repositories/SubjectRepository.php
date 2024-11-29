<?php

namespace App\Repositories;

use App\Models\Subject;
use App\Models\Resource;
use Illuminate\Http\Request;

class SubjectRepository
{
    protected $model;

    public function __construct(Subject $model)
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
                        // ->with(['media'])
                        ->withSearch($request->input('search'))
                        ->sortable(['updated_at' => 'desc'])
                        ->paginate($request->input('limit'));

        $posts->appends($request->all());

        return $posts;
    }

    public function apiPublishedOnly(Request $request)
    {
        return $posts = $this->model
                            //->sortable(['updated_at' => 'desc'])
                            ->isPublished()
                            ->get();
    }

    public function publishedOnly(Request $request)
    {
        return $this->model->with('resources')
                            ->isPublished()
                            ->paginate();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findBySlug($slug)
    {
        if (!$post = $this->model->where('slug', $slug)->first()) {
            abort(404, 'Resource Not Found');
        }

        return $post;
    }

    public function getKeyId()
    {
        return $this->model->id;
    }

    public function getItems()
    {
        return $this->model->where('published', true)->get()->pluck('title', 'id');
    }

    public function getApiItems()
    {
        return $this->model->select('id', 'title')->where('published', true)->get();
    }

    public static function getAllPublished()
    {
        $repository = new SubjectRepository(new Subject());

        return $repository->model->isPublished()->get();
    }

    public static function getItemList()
    {
        $repository = new self(new Subject());
        $posts = $repository->getItems();
        $posts->prepend('- Select Subject -', '');

        return $posts;
    }

    public static function getPublishedItemList()
    {
        $repository = new self(new Subject());
        $posts = $repository->getItems();

        return $posts;
    }

    public static function getPublishedApiItemList()
    {
        $repository = new self(new Subject());
        $posts = $repository->getApiItems();

        return $posts;
    }

    public function getResourceIds($id)
    {
        return $this->model->join('resource_subject', 'subjects.id', 'resource_subject.subject_id')
                            ->join('resources', 'resources.id', 'resource_subject.resource_id')
                            ->where('subjects.id', $id)
                            ->where('resources.published', true)
                            ->whereNull('resources.deleted_at')
                            ->select('resources.id')
                            ->get();
    }

    public static function getResourcesWithSubject($id)
    {
        $array = array();
        $repository = new self(new Subject());
        $posts = $repository->getResourceIds($id); //dd($posts);

        foreach ($posts as $post) {
            $array[] = $post->id;
        }

        if (count($array) == 0) {
            return Resource::where('id', 0)->privacyFilter(currentUserType())->paginate(10);
        } else {
            return Resource::withBulkResources($array)->privacyFilter(currentUserType())->orderBy('id', 'desc')->paginate(10);
        }
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

        if ($request->input('weight') == null) {
            $this->model->weight = 0;
        }

        $this->model->save();
    }
}
