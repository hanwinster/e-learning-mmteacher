<?php

namespace App\Http\Controllers\Member;

use App\Models\Review;
use App\Models\Resource;
use App\Http\Controllers\Controller;
use App\Repositories\ReviewRepository;
use App\Http\Requests\RequestReview as Request;

class ReviewController extends Controller
{
    protected $repository;

    public function __construct(ReviewRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\RequestReview $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $resourceId)
    {
        $validated = $request->validated();

        $this->repository->saveRecord($request);

        $resource = Resource::find($resourceId);

        return redirect()->route('resource.show', $resource->slug)
          ->with('success', 'Successfully saved');
    }
}
