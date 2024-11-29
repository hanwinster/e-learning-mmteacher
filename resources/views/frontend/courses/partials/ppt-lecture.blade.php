<div class="panel ppt-viewer">
    @php
        $lecture_attached_url = '';
        if(file_exists(public_path().'/upload/'. $currentSection->course->id . '-' . $currentSection->uuid . '.pdf')) {
            $lecture_attached_url = $currentSection->course->id . '-' . $currentSection->uuid . '.pdf';
        }else{
            $lecture_attached_url = str_replace([' ', '?', '!', '~', '@', '#', '$', '%', '^', '&', '*', '(', ')', '?', '/','\'', '|', ',', '"'], '', 
                $currentSection->course->title . '-' . $currentSection->lecture_title) . '.pdf';
        }
    @endphp
    <iframe src="https://docs.google.com/gview?url={{ str_replace(config('app.url'), config('app.url'), 
            asset($lecturesMedias->where('model_id',
            $currentSection->id)->first()->getUrl())) }}&embedded=true" 
        width="100%"
        height="540"
        allowfullscreen
        webkitallowfullscreen
        ></iframe>   
    <!-- <iframe
        src={{ str_replace(config('app.url'), config('app.url') . '/', asset($lecturesMedias->where('model_id', $currentSection->id)->first()->getUrl())) }}
{{--        src="http://localhost:8000/ViewerJS/#../mpu.pdf"--}}
        width="711"
        height="700"
        allowfullscreen
        webkitallowfullscreen
    > -->
    <!-- <iframe
        src={{ asset('upload/' . $lecture_attached_url) }}
{{--        src="http://localhost:8000/ViewerJS/#../upload/DatabaseManagementCourse-AdvencedOptimizationTechniques.pdf"--}}
            width="711"
        height="500"
        allowfullscreen
        webkitallowfullscreen
    > -->
</div>




