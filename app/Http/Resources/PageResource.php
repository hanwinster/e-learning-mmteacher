<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
		
		return [
            'id' => $this->id,
            'title' => $this->title,
            'title_mm' => $this->title_mm,
            'body' => $this->body,
            'body_mm' => $this->body_mm,
			'slug' => $this->slug,			
			'published' => $this->published,
			'user_id' => $this->user_id,
			'thumb_image' => ($this->getThumbnailPath())? asset($this->getThumbnailPath()) : '',
			'medium_image' => ($this->getMediumPath())? asset($this->getMediumPath()) : '',
			'large_image' => ($this->getImagePath())? asset($this->getImagePath()) : '',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
