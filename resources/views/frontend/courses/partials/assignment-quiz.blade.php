<div class ="row">
    <div class="col-12">
        <iframe src="https://docs.google.com/viewer?url={{ str_replace(config('app.url'), config('app.url'), 
                                    asset($assignmentMedia->getUrl()) ) }}&embedded=true" 
                                    width="100%"
                                    height="540"
                                    allowfullscreen
                                    webkitallowfullscreen
        ></iframe>
    </div>   
</div> 