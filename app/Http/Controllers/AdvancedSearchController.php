<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ResourceRepository;
use App\Repositories\SubjectRepository;
use App\Models\Resource;
use App\Repositories\SearchRepository;
use App\Repositories\YearRepository;
use App\Repositories\LicenseRepository;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;

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
        $formats = Resource::RESOURCE_FORMATS;

        $subjects = SubjectRepository::getPublishedItemList();
        $licenses = LicenseRepository::getItemList();
        $userTypes = UserRepository::getTypes(true);
        // $formats = ['' => '- Select Format -'] + Resource::RESOURCE_FORMATS;
        $formats = Resource::RESOURCE_FORMATS;
        $years = YearRepository::getItemList(false);

        $roles = RoleRepository::getRoleList();

        if (count($request->all())) {
            $posts = $this->search->index($request);
        }else{
            $posts = null;
        }

        if ($keywords = $request->input('keywords')) {
            $keywords = array_combine($keywords, $keywords);
        } else {
            $keywords = [];
        }

        return view(
            'frontend.search.advanced',
            compact('posts', 'subjects', 'licenses', 'userTypes', 'years', 'roles', 'formats', 'keywords')
        );
    }
}
