<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Repositories\ResourceRepository;
use App\Repositories\ArticleRepository;
use App\Repositories\SlideRepository;
use App\Repositories\CourseRepository;
use App\Repositories\PageRepository;
use App\Models\Faq;
use App\Models\FaqCategory;
use Lang;

class HomeController extends Controller
{
    protected $user_type;
    // protected $resources;
    // protected $articles;
    protected $slide;
    protected $course;
    protected $page;
    protected $articles;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CourseRepository $course,PageRepository $page, SlideRepository $slide, ArticleRepository $article)
    { //ResourceRepository $resource
        // $this->resource = $resource;
        $this->articles = $article;
        $this->slide = $slide;
        $this->course = $course;
        $this->page = $page;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->user_type = currentUserType();
        //$resources = $this->resource->indexForPublicFeatured(request(), $this->user_type, 9);
        $slides = $this->slide->getPublishedSlides(6); 
        //$articles = $this->article->getPublishedArticles();
        $categories = FaqCategory::isPublished()->get(); //for faq main tabs 
        $posts = Faq::isPublished()->paginate(); //for faq contents
        $currentLang = config('app.locale');
        $courses = $this->course->getPublishedCoursesForHomeByLanguage($this->user_type, 8, $currentLang);
        $about = $this->page->findBySlug('about-us');
        $contact = $this->page->findBySlug('contact-us');
        $faq = FaqCategory::isPublished()
                                    ->where('slug', 'e-learning')->first();
        $faqs = Faq::isPublished()->where('category_id', $faq->id)->get();   
                           
        return view('frontend.home.index', compact('courses','categories','posts','about', 'contact', 'slides','faqs'));
    }

    public function showManuals($id)
    {
        $manuals = []; 
        $data1 = [
            'title' => 'User Manual For Independent Learner Role(English) - Version 1',
            'link' => 'assets/files/Independent Learner userguide_ ENG_Version 1.pdf',
            'id' => 1
        ];
        $data2 = [
            'title' => 'User Manual For Independent Learner Role(Myanmar) - Version 1',
            'link' => 'assets/files/Independent Learner userguide _ MM_Version 1.pdf',
            'id' => 2
        ];
        array_push( $manuals, $data1);
        array_push( $manuals, $data2);
        // if(auth()->user() && auth()->user()->type != 'independent_learner') {
        //     array_push( $manuals, $data2);
        //     if($id == 1) {
        //         $current = $data1;
        //     } else {
        //         $current = $data2;
        //     }
        // } else {
        //     $current = $data1;
        // }       
        if($id == 1) {
            $current = $data1;
        } else {
            $current = $data2;
        }
        return view('frontend.home.show-manuals', compact('manuals', 'current'));
    }

    public function termsAndPrivacy()
    {

        return view('frontend.home.terms');
    }

    public function showHome()
    {
        $newsAndEvents = $this->articles->getPublishedArticlesByCategory(3);
        foreach($newsAndEvents as $ne) {     
            $ne['image_url'] = $ne->getMediumPath();
            $ne['modified'] = date("d M Y", strtotime($ne['updated_at']) );
        }
        $currentLang = Lang::locale();
        $ictCourses = $this->articles->getPublishedArticlesByCategory(6);
        $ictResources = $this->articles->getPublishedArticlesByCategory(7);
        $curriculumCourses = $this->articles->getPublishedArticlesByCategory(4);
        $curriculumResources = $this->articles->getPublishedArticlesByCategory(5);
        $epsdCourses = $this->articles->getPublishedArticlesByCategory(8);
        $epsdResources = $this->articles->getPublishedArticlesByCategory(9);
        $cseCourses = $this->articles->getPublishedArticlesByCategory(10);
        $cseResources = $this->articles->getPublishedArticlesByCategory(11);
        $milCourses = $this->articles->getPublishedArticlesByCategory(12);
        $milResources = $this->articles->getPublishedArticlesByCategory(13);
       
        return view('frontend.home.landing', 
        compact('newsAndEvents', 'currentLang', 'curriculumCourses', 'curriculumResources',
                'ictCourses', 'ictResources', 'epsdCourses', 'epsdResources', 'cseCourses', 'cseResources',
                'milCourses', 'milResources') );
    }

    public function showPartners()
    {   
        return view('frontend.home.partners');
    }
}
