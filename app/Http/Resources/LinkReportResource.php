<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\LinkReportRepository;

class LinkReportResource extends JsonResource
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

        return [
            'id' => $this->id,
            'resource_id' => $this->resource_id,
            'resource' => $this->when($this->resource_id, $resource),
            'user_id' => $this->user_id,
            'user' => $this->when(
                $user = $this->user,
                ['id' => $user->id, 'name' => $user->name, 'username' => $user->username]
                ),
            'comment' => $this->comment,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
