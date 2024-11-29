<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Resource;
use App\Repositories\ResourcePermissionRepository;

class ResourceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $includeRelation = true;

        $preview_url = null;
        $download_url = null;
        $preview_file = null;
        $download_file = null;
        $cover_image = null;
        $favourited = false;
        $resource_format = null;
        $license = null;

        if (isset(auth()->user()->id)) {
            $favourited = $this->favourited();
        }

        if ($previewMedia = $this->getFirstMedia('resource_previews')) {
            $preview_url = asset($previewMedia->getUrl());
            $extension = null;

            if (isset($previewMedia) && $previewMedia->getPath()) {
                $extension = strtolower(pathinfo($previewMedia->getPath(), PATHINFO_EXTENSION));
            }

            $preview_file = [
                'url' => $preview_url,
                'file_name' => $previewMedia->file_name,
                'mime_type' => $previewMedia->mime_type,
                'size' => $previewMedia->size,
                'human_readable_size' => $previewMedia->human_readable_size,
                'extension' => $extension
            ];
        }

        if ($this->allow_download) {
            if ($media = $this->getFirstMedia('resource_full_version_files')) {
                $download_url = route('resource.download', $media);
                $extension = null;

                if (isset($media) && $media->getPath()) {
                    $extension = strtolower(pathinfo($media->getPath(), PATHINFO_EXTENSION));
                }

                $download_file = [
                    'url' => $download_url,
                    'file_name' => $media->file_name,
                    'mime_type' => $media->mime_type,
                    'size' => $media->size,
                    'human_readable_size' => $media->human_readable_size,
                    'extension' => $extension
                ];
            }
        }

        $url = route('resource.show', $this->slug);

        if ($this->getThumbnailPath()) {
            
            $res = $this->getMedia("resource_cover_image")->toArray()[0];
            
            $cover_image = [
                'thumbnail' => asset($this->getThumbnailPath()),
                'medium' => asset("storage/".$res['id']."/".$res['file_name']),
                'large' => asset($this->getMediumPath()),
                // 'direct' => asset($this->getMediumPath()),
            ];
        }

        if (isset(Resource::RESOURCE_FORMATS[strtolower($this->resource_format)])) {
            $resource_format = Resource::RESOURCE_FORMATS[strtolower($this->resource_format)];
        }

        if ($license = $this->license) {
            $license = ['id' => $license->id, 'title' => $license->title];
        }

        $user = $this->user;

        $keywords = $this->keywordList();
        $otherKeywords = $this->keywordList('other');

        $selectedKeywords = null;

        foreach ($keywords as $key => $value) {
            $selectedKeywords[] = ['id' => $key, 'value' => $value];
        }

        $selectedOtherKeywords = null;

        foreach ($otherKeywords as $key => $value) {
            $selectedOtherKeywords[] = ['id' => $key, 'value' => $value];
        }

        $privacies = $this->privacies->pluck('user_type');

        $accessibleRights = null;

        foreach ($privacies as $key => $value) {
            $accessibleRights[] = ['id' => $value, 'value' => $value];
        }

        /*
        $allow_edit = 0;
        $userType = 'guest';

        if (isset(auth()->user()->type)) {
            $userType = auth()->user()->type;
        }

        if ($this->allow_edit == 1
            || $userType == 'admin'
            || $userType == 'manager'
        ) {
            $allow_edit = 1;
        }
        */

        // to determine a resource is editable or not by the logged in user
        $isEditable = 0;

        if (isset(auth()->user()->type)) {
            $isEditable = intval(ResourcePermissionRepository::canEdit($this));
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'permalink' => $url,
            'summary' => str_limit(strip_tags($this->description), 100, '...'),
            'description' => $this->description,
            //'resource_format' => $this->resource_format,
            'resource_format' => $resource_format,
            'strand' => $this->strand,
            'sub_strand' => $this->sub_strand,
            'lesson' => $this->lesson,
            'author' => $this->author,
            'rating' => $this->rating,
            'publisher' => $this->publisher,
            'publishing_year' => $this->publishing_year,
            'publishing_month' => $this->publishing_month,
            'published' => $this->published,
            'approved' => $this->approved,
            'approval_status' => $this->approval_status,
            'user_id' => $this->user_id,
            'user' => isset($user->id) ? ['id' => $user->id, 'name' => $user->name, 'username' => $user->username] : null,
            // 'subjects' => $this->when($includeRelation, $this->subjects->select('title', 'id')),
            'subjects' => $this->when(
                $includeRelation && isset($this->subjects),
                PartialSubject::collection($this->subjects)
            ),
            'license_id' => $this->license_id,
            'license' => $license,
            // 'years' => $this->when($includeRelation, $this->years->pluck('title', 'id')),
            'years' => $this->when(
                $includeRelation && isset($this->years),
                PartialYear::collection($this->years)
            ),
            'is_featured' => $this->is_featured,
            // 'suitable_for_ec_year' => $this->suitable_for_ec_year,
            'url' => $this->url,
            'additional_information' => $this->additional_information,
            'total_page_views' => (int)$this->total_page_views,
            'total_downloads' => (int)$this->total_downloads,
            'allow_feedback' => $this->allow_feedback,
            'allow_download' => $this->allow_download,
            'allow_edit' => $this->allow_edit,
            'is_locked' => $this->is_locked,
            'is_editable' => $isEditable,
            'cover_image' => $cover_image,
            // 'preview_url' => $preview_url,
            // 'download_url' => $download_url,
            'preview_file' => $preview_file,
            'download_file' => $download_file,
            // 'deleted_at' => $this->deleted_at,
            'approved_at' => $this->approved_at,
            'favourited' => $favourited,
            'keywords' => $selectedKeywords,
            'keywords_other' => $selectedOtherKeywords,
            'user_type' => $accessibleRights,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'last_updated' => $this->updated_at->format('Y-m-d'),
        ];
    }
}
