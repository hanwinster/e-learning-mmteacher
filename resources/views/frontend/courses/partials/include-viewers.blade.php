
@if($type == 'lecture')
    @if($currentSection->getMedia('lecture_attached_file')->first()->mime_type == 'application/pdf' ||
        $currentSection->getMedia('lecture_attached_file')->first()->mime_type == 'application/vnd.oasis.opendocument.presentation' )
        @include('frontend.courses.partials.pdf-lecture')
    @elseif($currentSection->getMedia('lecture_attached_file')->first()->mime_type == 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        || $currentSection->getMedia('lecture_attached_file')->first()->mime_type == "application/vnd.ms-powerpoint")
        @include('frontend.courses.partials.ppt-lecture')
    @elseif($currentSection->getMedia('lecture_attached_file')->first()->mime_type == 'video/mp4')
        @include('frontend.courses.partials.video-lecture')
    @elseif($currentSection->getMedia('lecture_attached_file')->first()->mime_type == 'audio/mpeg')
        @include('frontend.courses.partials.audio-lecture')
    @else
        @include('frontend.courses.partials.ppt-lecture')
    @endif
@elseif($type == 'quiz')
    @if($currentQuiz->type == 'true_false')
        @include('frontend.courses.partials.truefalse-question')
    @elseif($currentQuiz->type == 'multiple_choice')
        @include('frontend.courses.partials.multiple-choice-question')
    @elseif($currentQuiz->type == 'matching')
        @include('frontend.courses.partials.matching-question')
    @elseif($currentQuiz->type == 'blank')
        @include('frontend.courses.partials.blank-question')
    @elseif($currentQuiz->type == 'short_question')
        @include('frontend.courses.partials.short-question')
    @elseif($currentQuiz->type == 'long_question')
        @include('frontend.courses.partials.long-question')
    @elseif($currentQuiz->type == 'rearrange')
        @include('frontend.courses.partials.rearrange-question')
    @else
        @include('frontend.courses.partials.assignment-quiz')
    @endif
@elseif($type == 'summary')
    @if($currentSection->getMedia('summary_attached_file')->first()->mime_type == 'application/pdf' ||
        $currentSection->getMedia('summary_attached_file')->first()->mime_type == 'application/vnd.oasis.opendocument.presentation' )
        @include('frontend.courses.partials.pdf-lecture')
    @elseif($currentSection->getMedia('summary_attached_file')->first()->mime_type == 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        || $currentSection->getMedia('summary_attached_file')->first()->mime_type == "application/vnd.ms-powerpoint")
        @include('frontend.courses.partials.ppt-lecture-summary')
    @elseif($currentSection->getMedia('summary_attached_file')->first()->mime_type == 'video/mp4')
        @include('frontend.courses.partials.video-lecture')
    @elseif($currentSection->getMedia('summary_attached_file')->first()->mime_type == 'audio/mpeg')
        @include('frontend.courses.partials.audio-lecture')
    @elseif($currentSection->getMedia('summary_attached_file')->first()->mime_type == 'application/msword')
    <iframe src="https://docs.google.com/viewer?url={{ str_replace(config('app.url'), config('app.url'), 
                                    asset($summaryMedias->getUrl()) ) }}&embedded=true"  
                                    width="100%"
                                    height="540"
                                    allowfullscreen
                                    webkitallowfullscreen 
                                ></iframe>
    <!-- <iframe src="https://docs.google.com/viewer?url={{ str_replace(config('app.url'), config('app.url'), 
                                    asset( $summaryMedias->custom_properties['gdrive_link'] ) ) }}&embedded=true" 
                                    width="100%"
                                    height="540"
                                    allowfullscreen
                                    webkitallowfullscreen
                                ></iframe> -->
    @endif
@elseif($type == 'learning_activity')
    @if($currentSection->getMedia('learning_activity_attached_file')->first()->mime_type == 'application/pdf' ||
        $currentSection->getMedia('learning_activity_attached_file')->first()->mime_type == 'application/vnd.oasis.opendocument.presentation' )
        @include('frontend.courses.partials.pdf-lecture')
    @elseif($currentSection->getMedia('learning_activity_attached_file')->first()->mime_type == 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        || $currentSection->getMedia('learning_activity_attached_file')->first()->mime_type == "application/vnd.ms-powerpoint")
        @include('frontend.courses.partials.ppt-lecture-summary')
    @elseif($currentSection->getMedia('learning_activity_attached_file')->first()->mime_type == 'video/mp4')
        @include('frontend.courses.partials.video-lecture')
    @elseif($currentSection->getMedia('learning_activity_attached_file')->first()->mime_type == 'audio/mpeg')
        @include('frontend.courses.partials.audio-lecture')
    @elseif($currentSection->getMedia('learning_activity_attached_file')->first()->mime_type == 'application/msword')
    <iframe src="https://docs.google.com/viewer?url={{ str_replace(config('app.url'), config('app.url'), 
                                    asset($learningActivityMedias->getUrl()) ) }}&embedded=true"  
                                    width="100%"
                                    height="540"
                                    allowfullscreen
                                    webkitallowfullscreen 
                                ></iframe>
    @endif
@else

@endif