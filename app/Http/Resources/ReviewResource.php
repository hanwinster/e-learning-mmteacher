<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\SubjectRepository;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'resource_id' => $this->resource_id,
            'user_id' => $this->user_id,
            'user' => $this->when(
                $user = $this->user,
                [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'thumb_image' => ($user->getThumbnailPath()) ? asset($user->getThumbnailPath()) : null,
                    ]
            ),
            'rating' => (int) $this->rating,
            'comment' => $this->comment,
            'published' => $this->published,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
