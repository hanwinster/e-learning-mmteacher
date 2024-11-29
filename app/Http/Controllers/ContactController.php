<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestContactUs as Request;
use App\Models\Page;
use Blade;
use App\Repositories\ContactRepository;
use App\Repositories\PageRepository;

class ContactController extends Controller
{
    protected $repository;
    protected $pageRepository;

    public function __construct(ContactRepository $repository, PageRepository $pageRepository)
    {
        $this->repository = $repository;
        $this->pageRepository = $pageRepository;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug = 'contact-us')
    {
        if (!$post = $this->pageRepository->findBySlug($slug)) {
            abort(404, 'Page Not Found');
        }

        //$captcha_key = \Config::get('cms.recaptcha_key');dd($captcha_key);

        return view('frontend.page.contact-us', compact('post', 'slug'));
    }

    public function sendContact(Request $request)
    {   // dd($request->all());exit; 
        $validated = $request->validated();

        $isSaved = $this->repository->saveRecord($request); 
        if($isSaved) {
            return response(['data' =>  trans('Your Message has been successfully sent to admin.')], 200);
        } else {
            return response(['data' =>  trans('Errors occured while saving data. Please try again!')], 500);
        }
        // return redirect()->back()
        //       ->with(
        //           'success',
        //           'Your Message has been successfully sent to admin.'
        //       );
    }
}
