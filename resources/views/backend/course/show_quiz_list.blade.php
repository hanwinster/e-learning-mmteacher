<div class="card">
    <header class="card-header">
        <h4 class="card-title">
            @can('add_quiz')
            <a href="{{route('admin.quiz.create', $course->id)}}" class="btn btn-primary text-white pull-right">{{__('New')}}</a>
            @endcan
        </h4>
    </header>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-vcenter dataTable no-footer"style="width: 100%;">
            <tbody>
                <tr>
                    <td style="padding: 0;">
                        <table class="table table-bordered table-vcenter dataTable no-footer quiz_table" style="width: 100%;">
                            <thead>
                                @include('backend.course.quiz_header', ['heading' => $course->title])
                            </thead>
                            <tbody>
                                @include('backend.course.quiz_row', ['quizzes' => $quizs_for_only_course])
                            </tbody>
                        </table>
                    </td>
                </tr>
                @php
                    $lectures = \App\Models\Lecture::where('course_id', $course->id)->get();
                @endphp

                @foreach($lectures as $lecture)
                    <tr>
                        <td style="padding: 0;">
                            <table class="table table-bordered table-vcenter dataTable no-footer quiz_table" style="width: 100%;">
                                <thead>
                                    @include('backend.course.quiz_header', ['heading' => $lecture->lecture_title])
                                </thead>
                                <tbody>
                                    @include('backend.course.quiz_row', ['quizzes' => $lecture->quizzes])
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>