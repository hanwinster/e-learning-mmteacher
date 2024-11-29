<?php

use App\Models\Course;
use Spatie\MediaLibrary\Models\Media;

function get_course_cover_image(Course $course) {
    $media = $course->getMedia('course_cover_image')->first();
    return $media ? $media->getUrl() : '';
}
