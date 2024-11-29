<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#course-info" role="tab" aria-controls="course-info" aria-selected="false">Course Information</a>
    </li>
</ul>
<div class="tab-content mt-5 container-fluid ml-0" id="myTabContent">
{{--    <div class="tab-pane fade show active" id="assignment" role="tabpanel" aria-labelledby="assignment-tab">--}}
{{--    </div>--}}
    <div class="pane fade show active" id="course-info" role="tabpanel" aria-labelledby="course-info-tab">
        {!! $course->description !!}
    </div>



</div>
