<div class="row">
    <div class="col-12 mt-4">
        <h5 class="text-dark">
            {{__('Pre-defined Questions for Evaluation')}}&nbsp;
            <span  class="tooltip-info text-info" data-toggle="tooltip"
                data-placement="top" title="{{ __('If you want to add/modify the questions, please contact the administrator') }}">
                <i class="fas fa-info-circle"></i>
            </span>&nbsp;
            <a href="{{ route('member.course.view-evaluations', [$course->id] ) }}" class="btn btn-primary btn-md">
                {{ __('View Evaluations')}}
            </a>
        </h5>
        <table class="table no-footer">
            <thead>
                <th>@lang('No.')</th>
                <th>@lang('Question')</th>
                <th>@lang('Order')</th>
                <th>@lang('Type')</th>
                <th>@lang('Created At')</th>
            </thead>
            <tbody>
                @foreach($evaluationQs as $idx => $q)       
                    <tr>
                        <td>{{$idx + 1 }}</td>
                        <td>
                            @php $isMm = App::getLocale() == 'my-MM' ? true : false; @endphp
                            @if($isMm)
                                {{ $q->question_mm ? $q->question_mm : $q->question }}
                            @else
                                {{ $q->question }}
                            @endif
                        </td>   
                        <td>{{ $q->order }}</td>   
                        <td>{{ __(\App\Models\CourseEvaluation::EVALUATION_TYPES[$q->type]) }}</td>   
                        <td>{{ date('d-m-Y', strtotime($q->created_at)) }}</td>                   
                    </tr>
                @endforeach
            </tbody>
        </table>      
    </div>
</div>
