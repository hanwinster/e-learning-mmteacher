<div class="panel ppt-viewer">
    <iframe src="https://docs.google.com/gview?url={{ str_replace(config('app.url'), config('app.url'), 
            asset($lecturesMedias->where('model_id', $currentSection->id)->first()->getUrl())) }}&embedded=true" 
        width="100%"
        height="540"
        allowfullscreen
        webkitallowfullscreen
        >
    </iframe>
    <!-- <object data="{{ asset( $lecturesMedias->where('model_id', $currentSection->id)->first()->getUrl() )  }}" 
        type="application/pptx" width="100%" height="550">
        <p></p>
    </object> -->
    
</div>




