<div class="row">
    <div class="col-12 mt-4">
        <h5 class="text-dark">
            {{ __('Order Setting of Course Components') }}
        </h5>
        <table class="table no-footer">
            <tr>
                <td>@lang('Selected Option')</td>
                <td>
                    @if ($course->order_type == 'default')
                        <span class="text-success"><i class="fas fa-check"></i>&nbsp;</span>
                        @lang('Default Order')
                </td>
                <td>
                @else
                    <span class="text-success"><i class="fas fa-check"></i>&nbsp;</span>
                    @lang('Flexible Order')
                    @endif

                </td>
            </tr>
        </table>
        <div class="px-3">
            @if (\App\Repositories\CourseRepository::shouldCrudButtonsDisabled($course))
                <span class="tooltip-info" data-toggle="tooltip" data-placement="top"
                        title="@lang('Cannot edit the order as the course already had course takers')">
                        <a class="btn pr-2 pl-2 btn-outline disabled">
                            <i class="fas fa-edit"></i> {{ __('Edit') }}
                        </a>
                </span>
            @else
                <a href="{{ route('member.course.order.edit', $course->id) }}"
                    class="btn btn-primary btn-sm">@lang('Edit')</a>
            @endif
            {{-- @endif --}}
        </div>

    </div>
</div>

<div class="row">
    <div class="col-12 mt-4">
        @if ($course->order_type == 'default')
            <h5 class="text-dark">
                {{ __('Sections According To Default Order') }}&nbsp;
            </h5>
            <table class="table table-sm">
                <tr>
                    <td>
                        <label>Order</label>
                    </td>
                    <td>
                        <label>@lang('Item/Section')</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>1.</label>
                    </td>
                    <td>
                        <p class="order-header">@lang('Introduction')</p>
                        <p class="order-content-1 ps-2 text-primary">{{ strip_tags($course->title) }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>2.</label>
                    </td>
                    <td>
                        @lang('Lectures')&nbsp;({{ count($lectures) }})
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>3.</label>
                    </td>
                    <td>
                        @lang('Learning Activities')&nbsp;({{ count($learningActivities) }})
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>4.</label>
                    </td>
                    <td>
                        @lang('Quizzes')&nbsp;({{ count($quizs_for_only_course) }})
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>5.</label>
                    </td>
                    <td>
                        @lang('Live Sessions')&nbsp;({{ count($sessions_for_only_course) }})
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>6.</label>
                    </td>
                    <td>
                        @lang('Summary')&nbsp;({{ count($summary_for_only_course) }})
                    </td>
                </tr>
            </table>
        @else
            <h5 class="text-dark">
                {{ __('Sections According To Flexible Order') }}&nbsp;
            </h5>
            <table class="table table-sm">
                <tr>
                    <td>
                        <label>Order</label>
                    </td>
                    <td>
                        <label>@lang('Item/Section')</label>
                    </td>
                </tr>
                @foreach ($course->orders as $idx => $co)
                    <tr>
                        <td>
                            <label>{{ $idx + 1 }}</label>
                        </td>
                        <td>
                            @foreach ($co as $key => $value)
                                {{ strip_tags(\App\Repositories\CourseRepository::getTitleFromValue($key, $course)) }}
                            @endforeach
                        </td>
                    </tr>
                @endforeach

            </table>
        @endif
    </div>
</div>

<div class="modal fade" id="modal-order-alert">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-danger">@lang('Order Altert!')</h4>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <h6>
                    @lang('Please make sure that all the sections of a course are added before switching to flexible option and arranging the order!')
                </h6>
                <h6 class="mt-3">
                    @lang('If a section is added after arranging the order, it is required to re-arrange the orders')
                </h6>
            </div>
            <div class="modal-footer text-centerontent">             
                <button type="button" class="btn btn-primary btn-md" data-dismiss="modal">
                    @lang('Acknowledged')
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- end of Modals -->
