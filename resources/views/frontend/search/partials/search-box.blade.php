<div class="section mb-0 pb-0">
    <header class="header text-white" style="background-color: #0069b4">
        <div class="container h-50">
            <div class="row">
                <div class="col-md-8 mx-auto text-center">
                    <h1 class="pt-5">{{ __('Advanced Search') }}</h1>
                </div>
            </div>
            <form action="{{ url('search/advanced') }}" method="get">

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>{{ __('Title') }}</label>
                            <input class="form-control form-control-sm" type="text" name="title" id="title" placeholder="Enter the title of resource" value="{{ request('title', '') }}">
                        </div>

                        <div>
                            <h6>{{ __('Education College Year') }}</h6>
                            @foreach ($years as $key => $value)
                            <div class="form-check form-check-inline">
                                {{ Form::checkbox('year[]', $key, is_array(request('year')) ? in_array($key, request('year')) : null, ['id' => 'year_' . $key, 'class' => $errors->has('year')? 'form-check-input
                                is-invalid' : 'form-check-input']) }}
                                <label class="form-check-label" for="year_{{ $key }}">{{ $value }}</label>
                            </div>
                            @endforeach
                        </div>

                        <div class="form-group">
                            <label>{{ __('Keywords') }}</label>
                            {!! Form::select('keywords[]', $keywords, $keywords, [ 'id' => 'keyword-list', 'multiple' => 'true', 'class' => 'form-control']) !!}
                        </div>

                        <div>
                            <h6>{{ __('Subjects') }}</h6>
                            @foreach ($subjects as $key => $value)
                            <div class="form-check form-check-inline">
                                {{ Form::checkbox('subject[]', $key, is_array(request('subject')) ? in_array($key, request('subject')) : null, ['id' => 'subject_' . $key, 'class' => $errors->has('subject')? 'form-check-input is-invalid'
                                : 'form-check-input']) }}
                                <label class="form-check-label" for="subject_{{ $key }}">{{ $value }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="strand">{{ __('Strand') }}</label>
                            <input class="form-control form-control-sm" type="text" name="strand" id="strand" placeholder="Strand" value="{{ request('strand') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sub_strand">{{ __('Sub Strand') }}</label>
                            <input class="form-control form-control-sm" type="text" name="sub_strand" id="sub_strand" placeholder="Sub Strand" value="{{ request('sub_strand') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="lesson">{{ __('Lesson') }}</label>
                            <input class="form-control form-control-sm" type="text" name="lesson" id="lesson" placeholder="Lesson" value="{{ request('lesson') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="license">{{ __('License') }}</label>
                            {!! Form::select('license', $licenses, old('license', ''), ['id' => 'license', 'class' => $errors->has('license')? 'form-control is-invalid' : 'form-control'
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
{{--                     <div class="col-md-4">
                        <div class="form-group">
                            <label for="resource_format">{{ __('Resource Format') }}</label>
                            {!! Form::select('resource_format', $formats, request('resource_format', ''), ['id' => 'resource_format', 'class' => $errors->has('resource_format')? 'form-control is-invalid' : 'form-control'
                            ]) !!}
                        </div>
                    </div> --}}
                        <div class="col-md-12">
                            <h6>{{ __('Resource Formats') }}</h6>
                            @foreach ($formats as $key => $value)
                            <div class="form-check form-check-inline">
                                {{ Form::checkbox('resource_format[]', $key, is_array(request('resource_format')) ? in_array($key, request('resource_format')) : null, ['id' => 'resource_format_' . $key, 'class' => $errors->has('resource_format')? 'form-check-input is-invalid'
                                : 'form-check-input']) }}
                                <label class="form-check-label" for="resource_format_{{ $key }}">{{ $value }}</label>
                            </div>
                            @endforeach
                        </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                            <a href="{{ route('search.advanced') }}" class="btn btn-flat text-white">{{ __('Reset') }}</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </header>
</div>

@section('css')
@parent
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection

@section('script')
@parent
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>
    $('#keyword-list').select2({
        placeholder: "Type Keywords...",
        minimumInputLength: 2,
        tags: true,
        tokenSeparators: [',', ' '],
        ajax: {
            url: '/api/keywords',
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term)
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
</script>
@endsection
