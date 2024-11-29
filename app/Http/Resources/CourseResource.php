<?php

namespace App\Http\Resources;

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\CourseCategory;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $categories = CourseCategory::all()->pluck('name','id');
        $categoriesString ='';
        $currentLang = config('app.locale');
        foreach($this->course_categories as $key => $cat) {
            if($key !== count($this->course_categories)) {
                if($currentLang == 'en') {
                    $categoriesString .= $categories[$cat].", ";
                } else {
                    $categoriesString .= trans($categories[$cat])."၊ ";
                }
            } else {
                $categoriesString .= trans($categories[$cat]);
            }
        }
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => strip_tags($this->title),
            'description' => strip_tags($this->description),
            'cover_image' => env('APP_URL').get_course_cover_image($this->resource),
            'url_link' => $this->url_link,
            'course_category' => $categoriesString,
            'course_level' => Course::LEVELS[$this->course_level_id],
            'created_at' => Carbon::parse($this->created_at)->format('d-m-Y  h:m:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d-m-Y  h:m:s')
        ];
    }
}
