<?php

namespace App\Http\Controllers\Member;

use App\Models\Resource;
use App\User;
use App\Http\Controllers\Controller;
use App\Repositories\ResourceRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\LicenseRepository;
use App\Repositories\RoleRepository;
use App\Repositories\YearRepository;
use App\Repositories\UserRepository;
use App\Repositories\ResourcePermissionRepository;
use App\Http\Requests\RequestResource as Request;

class ResourceController extends Controller
{
    protected $resource;
    protected $subject;

    public function __construct(ResourceRepository $resource, SubjectRepository $subject)
    {
        $this->resource = $resource;
        $this->subject = $subject;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_type = currentUserType();
        // echo User::TYPE_ADMIN;
        // exit();
        if ($user_type == User::TYPE_ADMIN) {
            $posts = $this->resource->index(request());
        } elseif ($user_type == User::TYPE_MANAGER) {
            $posts = $this->resource->indexForManager(request());
        } else {
            $posts = $this->resource->indexForMember(request());
        }

        $subjects = $this->subject->getItemList();

        $formats = Resource::RESOURCE_FORMATS;
        $approvalStatus = Resource::APPROVAL_STATUS;

        $uploaded_by = null;

        if (auth()->user()->isAdmin()) {
            $uploaded_by = UserRepository::getAllUploaders();
        } elseif (auth()->user()->isManager()) {
            $uploaded_by = UserRepository::getAllUploadersFromSameCollege();
        }
        // $canEdit = ResourcePermissionRepository::canEdit();

        return view('frontend.member.resource.index', compact('posts', 'subjects', 'formats', 'approvalStatus', 'uploaded_by'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($format = Resource::IMAGE)
    {
        $subjects = $this->subject->getAllPublished();

        $licenses = LicenseRepository::getItemList();

        $userTypes = User::TYPES;

        $formats = ['' => '- Select Format -'] + Resource::RESOURCE_FORMATS;

        //$years = $this->resource->getEducationCollegeYears();
        $years = YearRepository::getItemList(false);

        $keywords = [];
        $selectedKeywords = [];
        $otherKeywords = [];
        $selectedOtherKeywords = [];

        $approvalStatus = Resource::APPROVAL_STATUS;

        // Get default selected rights
        $default_rights = $this->resource->getDefaultRightsForResourceForm(currentUserType());

        $canPublish = ResourcePermissionRepository::canPublish();
        $canApprove = ResourcePermissionRepository::canApprove();
        $canLock = ResourcePermissionRepository::canLock();

        return view(
            'frontend.member.resource.form',
            compact(
                'subjects',
                'licenses',
                'format',
                'userTypes',
                'formats',
                'years',
                'keywords',
                'selectedKeywords',
                'otherKeywords',
                'selectedOtherKeywords',
                'approvalStatus',
                'default_rights',
                'canPublish',
                'canApprove',
                'canLock'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validated();

        $this->resource->saveRecord($request);

        $id = $this->resource->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('member.resource.create')
              ->with(
                  'success',
                  __('Resource has been successfully updated. And you are ready to create a new resource.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('member.resource.edit', $id)
              ->with(
                  'success',
                  __('Resource has been successfully updated.')
              );
        } elseif ($request->input('btnSaveClose')) {
            return redirect($request->input('redirect_to'))
              ->with(
                  'success',
                  __('Resource has been successfully updated.')
              );
        } elseif ($request->input('btnNext')) {
            return redirect()->route('member.resource.related', $id)
              ->with(
                  'success',
                  __('Resource has been successfully updated.')
              );
        } else {
            return redirect()->route('member.resource.index')
              ->with(
                  'success',
                  __('Resource has been successfully saved.')
              );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = $this->resource->find($id);
        $subjects = $this->subject->getAllPublished();
        $licenses = LicenseRepository::getItemList();
        $userTypes = User::TYPES;
        $formats = ['' => '- Select Format -'] + Resource::RESOURCE_FORMATS;
        // $years = $this->resource->getEducationCollegeYears();
        $years = YearRepository::getItemList(false);

        $roles = RoleRepository::getRoleList();
        $format = $post->resource_format;

        $keywords = $post->keywordList();
        $otherKeywords = $post->keywordList('other');

        $selectedKeywords = $keywords->map(function ($key, $value) {
            return $value;
        });

        $selectedKeywords = $selectedKeywords->toArray();

        $selectedOtherKeywords = $otherKeywords->map(function ($key, $value) {
            return $value;
        });
        // Get default selected rights
        $default_rights = $this->resource->getDefaultRightsForResourceForm(currentUserType());
        
        $selectedOtherKeywords = $selectedOtherKeywords->toArray();
        $approvalStatus = Resource::APPROVAL_STATUS;
        $canPublish = ResourcePermissionRepository::canPublish();
        $canApprove = ResourcePermissionRepository::canApprove();
        $canLock = ResourcePermissionRepository::canLock();

        return view(
            'frontend.member.resource.form',
            compact(
                'subjects',
                'format',
                'post',
                'licenses',
                'formats',
                'roles',
                'years',
                'userTypes',
                'keywords',
                'selectedKeywords',
                'otherKeywords',
                'selectedOtherKeywords',
                'default_rights',
                'approvalStatus',
                'canPublish',
                'canApprove',
                'canLock'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validated();

        $this->resource->saveRecord($request, $id);

        if ($request->input('btnSaveNew')) {
            return redirect()->route('member.resource.create')
              ->with(
                  'success',
                  __('Resource has been successfully updated. And you are ready to create a new resource.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('member.resource.edit', $id)
              ->with(
                  'success',
                  __('Resource has been successfully updated.')
              );
        } elseif ($request->input('btnSaveClose')) {
            return redirect($request->input('redirect_to'))
              ->with(
                  'success',
                  __('Resource has been successfully updated.')
              );
        } elseif ($request->input('btnNext')) {
            return redirect()->route('member.resource.related', $id)
              ->with(
                  'success',
                  __('Resource has been successfully updated.')
              );
        } else {
            return redirect()->route('member.resource.index')
              ->with(
                  'success',
                  __('Resource has been successfully saved.')
              );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->resource->find($id);

        $post->delete();

        return redirect()->back()
          ->with('success', 'Successfully deleted');
    }

    public function clone($id)
    {
        $post = Resource::findOrFail($id);

        $clone = $this->resource->cloneResource($id);

        return redirect()->route('member.resource.edit', $clone->id)
        ->with('success', 'A new version of ' . $post->title . ' is successfully created. Please start uploading cover, preview and media file to finish.');
    }
}
