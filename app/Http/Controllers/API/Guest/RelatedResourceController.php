<?php

namespace App\Http\Controllers\API\Guest;

use App\Http\Controllers\Controller;
use App\Repositories\ResourceRepository;
use App\Models\Resource;
use App\Http\Resources\ResourceResource;

class RelatedResourceController extends Controller
{
    protected $repository;

    public function __construct(ResourceRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!$post = Resource::find($id)) {
            return response()->json(['data' => null]);
        }

        $related_resources = $this->repository->relatedResources($post, currentUserType());

        /*
        if (isset($related_resources) && !$related_resources->count()) {
            $related_resources = $this->repository->relatedResourcesBySubjects($post, currentUserType());
        }
        */

        return ResourceResource::collection($related_resources);
    }
}
