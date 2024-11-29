<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SlideResource extends JsonResource
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
			'description' => $this->description,
			'published' => $this->published,
			'weight' => $this->weight,
			'thumb_image' => ($this->getThumbnailPath())? asset($this->getThumbnailPath()) : '',
			'medium_image' => ($this->getMediumPath())? asset($this->getMediumPath()) : '',
			'large_image' => ($this->getImagePath())? asset($this->getImagePath()) : '',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
