<?php

namespace App\Http\Controllers\API\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SearchRepository;
use App\Http\Resources\ResourceResource;

class AdvancedSearchController extends Controller
{
    protected $search;

    public function __construct(SearchRepository $search)
    {
        $this->search = $search;
    }

    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $posts = null;

        if (count($request->all())) {
            $posts = $this->search->index($request);

            return ResourceResource::collection($posts);
        }

        return response()->json(['message' => 'There are no resources.']);
    }
}
