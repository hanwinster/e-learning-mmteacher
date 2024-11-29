<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\FaqCategory;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = FaqCategory::isPublished()->get();
        $posts = Faq::isPublished()->paginate();

        return view('frontend.faq.index', compact('posts', 'categories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if (!$current = FaqCategory::isPublished()
                                    ->where('slug', $slug)->first()) {
            abort(404, 'Resource Not Found');
        };

        $categories = FaqCategory::isPublished()->paginate();
        $posts = Faq::isPublished()->where('category_id', $current->id)->get();

        return view('frontend.faq.show', compact('current', 'posts', 'categories'));
    }
}
