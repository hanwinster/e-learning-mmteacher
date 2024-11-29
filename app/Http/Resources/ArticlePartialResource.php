<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticlePartialResource extends JsonResource
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
            'title' => $this->title,
            'category_id' => $this->category->id,
            'category_name' => $this->category->title,
            'slug' => $this->slug,
            'body' => mb_substr(html_entity_decode(strip_tags($this->body)), 0, 50),
            'thumb_image' => ($this->getThumbnailPath()) ? asset($this->getThumbnailPath()) : '',
            'medium_image' => ($this->getMediumPath()) ? asset($this->getMediumPath()) : '',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
