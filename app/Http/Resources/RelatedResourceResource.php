<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\LinkReportRepository;
use App\Http\Resources\ResourceResource;
use App\Models\Resource;

class RelatedResourceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = null;
        $resource = null;

        if ($this->resource_id) {
            $resource = [
                'id' => $this->resource->id,
                'title' => $this->resource->title
            ];
        }

        if ($this->user_id) {
            $user = [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'name' => $this->user->name
            ];
        }

        $relatedResource = Resource::find($this->related_resource_id);

        return [
            'id' => $this->id,
            'resource_id' => $this->resource_id,
            'resource' => $this->when($this->resource_id, $resource),
            'related_resource_id' => $this->resource_id,
            'related_resource' => $this->when($this->related_resource_id, new ResourceResource($relatedResource)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
