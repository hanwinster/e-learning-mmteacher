<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\ApprovalRequestRepository;
use App\Repositories\ResourcePermissionRepository;
use App\Models\Resource;

class ApprovalRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = $this->resource()->first();
        $approver = isset($this->approver->id) ? ['id' => $this->approver->id, 'name' => $this->approver->name, 'username' => $this->approver->username] : null;

        $cover_image = null;

        if (isset($resource) && $resource->getThumbnailPath()) {
            $cover_image = [
                'thumbnail' => asset($resource->getThumbnailPath()),
                'medium' => asset($resource->getMediumPath()),
                'large' => asset($resource->getMediumPath()),
            ];
        }

        // to determine a resource is editable or not by the logged in user
        $isEditable = 0;

        if (isset(auth()->user()->type)) {
            $isEditable = intval(ResourcePermissionRepository::canEdit($resource));
        }

        return [
            'id' => $this->id,
            'resource_id' => $this->resource_id,
            'resource' => $this->when(isset($resource), [
                'id' => $this->resource_id,
                'title' => $resource->title,
                'slug' => $resource->slug,
                'cover_image' => $cover_image,
                'allow_edit' => $resource->allow_edit,
                'is_locked' => $resource->is_locked,
                'is_editable' => $isEditable,
            ]),
            'user_id' => $this->user_id,
            'user' => $this->when(
                $user = $this->user,
                ['id' => $user->id, 'name' => $user->name, 'username' => $user->username]
            ),
            'description' => $this->description,
            'approval_status' => $this->approval_status,
            'approval_status_name' => Resource::APPROVAL_STATUS[$this->approval_status],
            'approved_by' => $this->when(
                isset($approver),
                $approver
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
