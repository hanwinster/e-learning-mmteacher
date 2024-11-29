{{--<embed width="711" height="800" src="{{ asset($lecturesMedias->where('model_id', $currentSection->id)->first()->getUrl()) }}">--}}

<div class="panel">
    <!-- <iframe
        src={{ str_replace(config('app.url'), config('app.url') . '/ViewerJS/#..', asset($lecturesMedias->where('model_id', $currentSection->id)->first()->getUrl())) }} -->
{{--        src="http://localhost:8000/ViewerJS/#../mpu.pdf"--}}
        <!-- width="100%"
        height="650"
        allowfullscreen
        webkitallowfullscreen
    >
    </iframe> -->
    <object data="{{ asset( $lecturesMedias->where('model_id', $currentSection->id)->first()->getUrl() )  }}" 
        type="application/pdf" width="100%" height="550">
        <p></p>
    </object>
</div> 
