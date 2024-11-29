<?php

namespace App\Http\Controllers\API\Guest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\FaqCategoryRepository;
use App\Repositories\FaqRepository;
use App\Models\Faq;
use App\Models\FaqCategory;

use App\Http\Resources\FaqCategoryResource;
use App\Http\Resources\FaqCategoryCollection;
use App\Http\Resources\FaqResource;
use App\Http\Resources\FaqCollection;

class FaqCategoryController extends Controller
{
    protected $repository;

    public function __construct(FaqCategoryRepository $repository)
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
        $posts = $this->repository->apiPublishedOnly(request()); //$this->repository->publishedOnly(request());

        return new FaqCategoryCollection($posts);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if (!$post = $this->repository->findBySlug($slug)) {
            return response()->json(['message' => 'Resource Not Found'], 404);
        }

        return new FaqCategoryResource($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function getFaqs($slug)
    {
        $faqRepository = new FaqRepository(new Faq);

        if (!$post = $this->repository->findBySlug($slug)) {
            return response()->json(['message' => 'Resource Not Found'], 404);
        }

        $faqs = $faqRepository->findByCategoryId($post->id);

        return new FaqCollection($faqs);
    }
}
