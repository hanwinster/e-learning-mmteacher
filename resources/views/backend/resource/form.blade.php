@extends('backend.layouts.default')

@section('title', __('Resource'). ' ['. (isset($post->id) ? 'Edit #'.$post->id : 'New').']')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Default Elements -->
            <div class="card">
                <h4 class="card-title">
                    @if (isset($post->id)) [Edit] #<strong title="ID">{{ $post->id }}</strong> @else [New] @endif
                </h4>

                <div class="card-body" id="elibrary_root">

                    @if (isset($post))
                    {!! \Form::open(array('files' => true, 'method' => 'put', 'route' => array('admin.resource.update',
                    $post->id)
                    , 'class' => 'form-horizontal', '@submit' => 'validateBeforeSubmit')) !!}
                    @else
                    {!! \Form::open(array('files' => true, 'route' => 'admin.resource.store',
                    'class' => 'form-horizontal', '@submit' => 'validateBeforeSubmit')) !!}
                    @endif
                    {!! Form::hidden('redirect_to', url()->previous()) !!}

                    @if (isset($post->id))
                    <input type="hidden" name="id" id="id" value="{{ $post->id }}">
                    @else
                    <input type="hidden" name="id" id="id" value="">
                    @endif

                    <div class="row">
                        <div class="col-md-8">

                            <div class="form-group">
                                <label for="resource_format"
                                    class="col-xs-12 require">{{__('Resource Format') }}</label>
                                {!! Form::select('resource_format',
                                $formats, old('resource_format', isset($post->resource_format)? $post->resource_format:
                                ''), ['class' => $errors->has('resource_format')?
                                'form-control is-invalid' : 'form-control', 'v-validate' => "'required|min:1'" ]) !!}
                                {!! $errors->first('resource_format', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('resource_format')" class="invalid-feedback">@{{ errors.first('resource_format') }}</div>
                            </div>

                            <div class="form-group">
                                <label for="title" class="require">@lang('Title')</label>
                                <input v-validate="'required|max:255'" type="text" placeholder="Title.." name="title" id="title"
                                    class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                    value="{{ old('title', isset($post->title) ? $post->title: '') }}">
                                {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('title')" class="invalid-feedback">@{{ errors.first('title') }}</div>
                            </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="strand" class="require">@lang('Strand')
                                        <i class="fa fa-info-circle" data-provide="tooltip" data-toggle="tooltip"
                                            data-placement="top" data-original-title="Chapters"></i>
                                    </label>

                                    <input v-validate="'required|max:255'" type="text" placeholder="Strand.." name="strand" id="strand"
                                        class="form-control{{ $errors->has('strand') ? ' is-invalid' : '' }}"
                                        value="{{ old('strand', isset($post->strand) ? $post->strand: '') }}">
                                    {!! $errors->first('strand', '<div class="invalid-feedback">:message</div>') !!}
                                    <div v-show="errors.has('strand')" class="invalid-feedback">@{{ errors.first('strand') }}</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="sub_strand" class="require">@lang('Sub Strand')
                                        <i class="fa fa-info-circle" data-provide="tooltip" data-toggle="tooltip"
                                            data-placement="top" data-original-title="Units"></i>
                                    </label>

                                    <input v-validate="'required|max:255'" type="text" placeholder="Sub strand.." name="sub_strand" id="sub_strand"
                                        class="form-control{{ $errors->has('sub_strand') ? ' is-invalid' : '' }}"
                                        value="{{ old('sub_strand', isset($post->sub_strand) ? $post->sub_strand: '') }}">
                                    {!! $errors->first('sub_strand', '<div class="invalid-feedback">:message</div>') !!}
                                    <div v-show="errors.has('sub_strand')" class="invalid-feedback">@{{ errors.first('sub_strand') }}</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="lesson" class="require">@lang('Lesson')
                                        <i class="fa fa-info-circle" data-provide="tooltip" data-toggle="tooltip"
                                            data-placement="top" data-original-title="Lesson"></i>
                                    </label>

                                    <input v-validate="'required|max:255'" type="text" placeholder="Lesson.." name="lesson" id="lesson"
                                        class="form-control{{ $errors->has('lesson') ? ' is-invalid' : '' }}"
                                        value="{{ old('lesson', isset($post->lesson) ? $post->lesson: '') }}">
                                    {!! $errors->first('lesson', '<div class="invalid-feedback">:message</div>') !!}
                                    <div v-show="errors.has('lesson')" class="invalid-feedback">@{{ errors.first('lesson') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="author" class="col-xs-12 require">@lang('Author')</label>
                            <input v-validate="'required|max:255'" type="text" placeholder="Author.." name="author" id="author"
                                class="form-control{{ $errors->has('author') ? ' is-invalid' : '' }}"
                                value="{{ old('author', isset($post->author) ? $post->author: '') }}">
                            {!! $errors->first('author', '<div class="invalid-feedback">:message</div>') !!}
                            <div v-show="errors.has('author')" class="invalid-feedback">@{{ errors.first('author') }}</div>
                        </div>

                        <div class="form-group">
                            <label for="publisher" class="col-xs-12">@lang('Publisher')</label>
                            <input type="text" placeholder="Publisher.." name="publisher" id="publisher"
                                class="form-control{{ $errors->has('publisher') ? ' is-invalid' : '' }}"
                                value="{{ old('publisher', isset($post->publisher) ? $post->publisher: '') }}">
                            {!! $errors->first('publisher', '<div class="invalid-feedback">:message</div>') !!}
                        </div>

                        <div class="row">
                            <div class="col">
                                    <div class="form-group">
                                        <label for="publishing_year" class="require">{{ __('Publishing Year') }}</label>
                                        <input v-validate="'required|digits:4'" type="text" placeholder="Publishing year.." name="publishing_year" id="publishing_year"
                                            class="form-control{{ $errors->has('publishing_year') ? ' is-invalid' : '' }}"
                                            value="{{ old('publishing_year', isset($post->publishing_year) ? $post->publishing_year: '') }}">
                                        {!! $errors->first('publishing_year', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('publishing_year')" class="invalid-feedback">@{{ errors.first('publishing_year') }}</div>
                                        <div v-show="year_month_error" class="vee-validate-invalid-feedback">Publishing Year &amp; Month should be less than @{{ publishing_year_month }}. </div>
                                    </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="publishing_month" class="require">{{ __('Publishing Month') }}</label>
                                        {!! Form::selectMonth('publishing_month', old('publishing_month', isset($post->publishing_month)? $post->publishing_month: ''),
                                        ['placeholder' => '-Select Month-', 'id' => 'publishing_month', 'class' => $errors->has('publishing_month')? 'form-control is-invalid' : 'form-control',
                                            'v-validate' => "'required:publishing_year|min:1'", 'data-vv-as' => 'field']) !!}
                                    {!! $errors->first('publishing_month', '<div class="invalid-feedback">:message</div>') !!}
                                    <div v-show="errors.has('publishing_month')" class="invalid-feedback">@{{ errors.first('publishing_month') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="keyword-list" class="col-xs-12">{{ __('Keywords') }}</label>

                            {!! Form::select('keywords[]', $selectedKeywords, $selectedKeywords, [ 'id' =>
                            'keyword-list', 'multiple' => 'true', 'class' => 'form-control']) !!}
                            {!! $errors->first('keywords', '<div class="invalid-feedback">:message</div>') !!}
                        </div>

                        <div class="form-group">
                            <label for="url" class="col-xs-12">@lang('URL')</label>
                            <input type="text" placeholder="URL.." name="url" id="url"
                                class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}"
                                value="{{ old('url', isset($post->url) ? $post->url: '') }}">
                            {!! $errors->first('url', '<div class="invalid-feedback">:message</div>') !!}
                        </div>

                        <div class="form-group">
                            <label for="additional_information" class="">@lang('Additional Information')
                                <i class="fa fa-info-circle" data-provide="tooltip" data-toggle="tooltip"
                                    data-placement="top"
                                    data-original-title="Adding extra information of the resource"></i>
                            </label>

                            <input type="text" placeholder="Additional Information.." name="additional_information"
                                id="additional_information"
                                class="form-control{{ $errors->has('additional_information') ? ' is-invalid' : '' }}"
                                value="{{ old('additional_information', isset($post->additional_information) ? $post->additional_information: '') }}">
                            {!! $errors->first('additional_information', '<div class="invalid-feedback">:message</div>')
                            !!}
                        </div>

                        <div class="row">
                            <div class="col">
                                    <div class="form-group">
                                        <label for="license_id" class="require">{{ __('License') }}</label>
                                        {!! Form::select('license_id', $licenses, old('license_id', isset($post->license_id)
                                        ? $post->license_id: ''),
                                        ['class' => $errors->has('license_id')? 'form-control is-invalid' : 'form-control', 'v-validate' => "'required|min:1'"  ]) !!}

                                        {!! $errors->first('license_id', '<div class="invalid-feedback">:message</div>') !!}
                                        <div v-show="errors.has('license_id')" class="invalid-feedback">@{{ errors.first('license_id') }}</div>
                                    </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="require">@lang('Description')</label>
                            <textarea  v-validate="'required'" data-provide="summernote" data-height="200"
                                class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                rows="5" name="description"
                                id="description">{{ old('description', isset($post->description) ? $post->description: '') }}</textarea>
                            {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                            <div v-show="errors.has('description')" class="invalid-feedback">@{{ errors.first('description') }}</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="subject_id"
                                class="col-xs-12 require">{{ __('Subject(s)/ Learning Area(s) that you teach') }}</label>
                            <!---------->
                            @foreach ($subjects as $subject)
                            <div class="form-check">
                                @if (isset($post) && $post->subjects)

                                @php
                                $subjects = array();
                                $post_subjects = $post->subjects;

                                foreach ($post_subjects as $post_subject) {
                                //var_dump($post_subject->id);
                                $subjects[] = $post_subject->id;
                                }
                                @endphp

                                {{ Form::checkbox('subjects[]', $subject->id, in_array($subject->id, $subjects)? true : false,
                                ['id' => 'sub_' . $subject->id, 'class' => $errors->has('subjects')? 'form-check-input is-invalid' : 'form-check-input', 'v-validate' => "'required'"] ) }}

                                @else
                                {{ Form::checkbox('subjects[]', $subject->id, '', ['id' => 'sub_' . $subject->id,
                                'class' => $errors->has('subjects')? 'form-check-input is-invalid' : 'form-check-input', 'v-validate' => "'required'"]) }}
                                @endif

                                <label class="form-check-label">
                                    {{ Form::label('sub_' . $subject->id, $subject->title) }} </label>

                                @if ($loop->last)
                                {!! $errors->first('subjects', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('subjects[]')" class="invalid-feedback">Please select at least one Subject.</div>
                                @endif

                            </div>

                            @endforeach
                            <!---------------->
                        </div>

                        <div class="form-group vv">
                            <label for="suitable_for_ec_year" class="col-xs-12 require">@lang('Suitable for Education
                                College Year(s)')</label>

                            @foreach ($years as $key => $year)

                            <div class="form-check">

                                @if (isset($post))

                                @php
                                $ec_years = [];

                                if (isset($post->years))
                                {
                                $ec_years = $post->years->pluck('id')->toArray();
                                }
                                @endphp

                                {{ Form::checkbox('suitable_for_ec_year[]', $key, in_array($key, $ec_years)? true : false,
                                ['id' => 'ecy_' . $key, 'class' => $errors->has('suitable_for_ec_year')? 'form-check-input is-invalid' : 'form-check-input', 'v-validate' => "'required'"] ) }}

                                @else

                                {{ Form::checkbox('suitable_for_ec_year[]', $key, '',
                                ['id' => 'ecy_' . $key, 'class' => $errors->has('suitable_for_ec_year')? 'form-check-input is-invalid' : 'form-check-input',  'v-validate' => "'required'"]) }}

                                @endif

                                {{ Form::label('ecy_' . $key, $year, ['class' => 'form-check-label']) }}

                                @if ($loop->last)
                                {!! $errors->first('suitable_for_ec_year', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('suitable_for_ec_year[]')" class="invalid-feedback">Please select at least one Year.</div>
                                @endif
                            </div>
                            @endforeach


                        </div>

                        <div class="form-group">
                            <label for="user_type" class="require">{{ __('Accessible Right') }}</label>

                            @foreach ($userTypes as $key => $value)

                            <div class="form-check">

                                @if (isset($post) && $post->privacies)

                                @php
                                $privacies = array();
                                $post_privacies = $post->privacies;

                                foreach ($post_privacies as $post_privacy) {

                                $privacies[] = $post_privacy->user_type;
                                }
                                @endphp

                                {{ Form::checkbox('user_type[]', $key, in_array($key, $privacies)? true : false,
                                 ['id' => 'user_type_' . $key, 'class' => $errors->has('user_type')? 'form-check-input is-invalid' : 'form-check-input', 'v-validate' => "'required'"] ) }}

                                @else

                                {{ Form::checkbox('user_type[]', $key, '',
                                ['id' => 'user_type_' . $key, 'class' => $errors->has('user_type')? 'form-check-input is-invalid' : 'form-check-input', 'v-validate' => "'required'"]) }}

                                @endif

                                <label class="form-check-label">
                                    {{ Form::label('user_type_' . $key, title_case($value)) }} </label>

                                @if ($loop->last)
                                {!! $errors->first('user_type', '<div class="invalid-feedback">:message</div>') !!}
                                <div v-show="errors.has('user_type[]')" class="invalid-feedback">Please select at least one Right.</div>
                                @endif
                            </div>

                            @endforeach

                        </div>

                        <h3>{{ __('Preview & Media File') }}</h3>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <label for="cover_image" class="require">{{ __('Cover Image') }}</label>
                                @if(isset($post->id) && $post->getMedia('resource_cover_image')->count())
                                {{ Form::file('cover_image',
                                ['class' => $errors->has('cover_image') ? 'is-invalid' : '', 'v-validate' => "'image|size:500'"]) }}
                                @else
                                {{ Form::file('cover_image',
                                ['class' => $errors->has('cover_image') ? 'is-invalid' : '', 'v-validate' => "'required|image|size:500'"]) }}
                                @endif
                                <div v-show="errors.has('cover_image')" class="vee-validate-invalid-feedback">@{{ errors.first('cover_image') }}</div>

                                @if (isset($post))
                                @php
                                $images = $post->getMedia('resource_cover_image');
                                @endphp

                                <div>
                                    @foreach($images as $image)
                                    <a target="_blank" href="{{ asset($image->getUrl()) }}">
                                        <img src="{{ asset($image->getUrl('thumb')) }}">
                                    </a>

                                    <a onclick="return confirm('Are you sure you want to delete?')"
                                        href="{{ route('admin.media.destroy', $image->id) }}" class="text-danger">Remove</a>
                                    @endforeach
                                </div>
                                @endif
                                {!! $errors->first('cover_image', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-xs-12">
                                <label for="published">@lang('Preview File')</label>
                                <div class="mb-2">
                                {{ Form::file('previews', ['class' => $errors->has('previews') ? 'form-control is-invalid' : '']) }}
                                </div>

                                @if (isset($post))
                                @php
                                $images = $post->getMedia('resource_previews');
                                $vimeo_url = '';
                                $vimeo_thumbnail = '';
                                @endphp

                                <div>
                                    @foreach($images as $image)
                                    @php
                                    if( $image->getCustomProperty('uri') ) {
                                        if (isset($image->getCustomProperty('vimeo_metadata')['pictures']['sizes'][0]['link_with_play_button'])) {
                                            $vimeo_url = $image->getCustomProperty('video_url');
                                            $vimeo_thumbnail = $image->getCustomProperty('vimeo_metadata')['pictures']['sizes'][0]['link_with_play_button'];
                                        }
                                    }
                                    @endphp

                                    @if($image->getCustomProperty('uri'))
                                    <a target="_blank" href="{{ $vimeo_url ? $vimeo_url : asset($image->getUrl()) }}">
                                    <img src="{{ ($image->getCustomProperty('uri'))? $vimeo_thumbnail : asset($image->getUrl()) }}" alt="Download">
                                    </a>
                                    @else
                                    <a target="_blank" href="{{ asset($image->getUrl()) }}" class="btn">
                                        {{ __('Download') }}
                                    </a>
                                    @endif

                                    <a onclick="return confirm('Are you sure you want to delete?')"
                                        href="{{ route('admin.media.destroy', $image->id) }}" class="text-danger">Remove</a>
                                    @endforeach
                                </div>
                                @endif
                                {!! $errors->first('previews', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <label for="published">@lang('Media File')</label>
                                <div class="mb-2">
                                {{ Form::file('full_version_files', ['class' => $errors->has('full_version_files') ? 'is-invalid' : '']) }}
                                </div>

                                @if (isset($post))
                                @php
                                $images = $post->getMedia('resource_full_version_files');
                                $vimeo_url = '';
                                $vimeo_thumbnail = '';
                                @endphp
                                <div>
                                    @foreach($images as $image)
                                    @php
                                    if( $image->getCustomProperty('uri') ) {
                                        if (isset($image->getCustomProperty('vimeo_metadata')['pictures']['sizes'][0]['link_with_play_button'])) {
                                            $vimeo_url = $image->getCustomProperty('video_url');
                                            $vimeo_thumbnail = $image->getCustomProperty('vimeo_metadata')['pictures']['sizes'][0]['link_with_play_button'];
                                        }
                                    }
                                    @endphp

                                    @if($image->getCustomProperty('uri'))
                                    <a target="_blank" href="{{ $vimeo_url ? $vimeo_url : asset($image->getUrl()) }}">
                                    <img src="{{ ($image->getCustomProperty('uri'))? $vimeo_thumbnail : asset($image->getUrl()) }}" alt="Download">
                                    </a>
                                    @else
                                    <a target="_blank" href="{{ asset($image->getUrl()) }}" class="btn">
                                        {{ __('Download') }}
                                    </a>
                                    @endif
                                    <a onclick="return confirm('Are you sure you want to delete?')"
                                        href="{{ route('admin.media.destroy', $image->id) }}" class="text-danger">Remove</a>
                                    @endforeach
                                </div>
                                @endif
                                {!! $errors->first('full_version_files', '<div class="invalid-feedback">:message</div>')
                                !!}
                            </div>
                        </div>

                        <h3>{{ __('Publishing') }}</h3>

                        @can('publish_resource')
                        <div>{{ __('Published') }}</div>
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                {{ Form::radio('published', 1, (isset($post->published) && $post->published == 1 ? true : false ), ['id' => 'published_yes', 'class' => 'form-check-input']) }}
                                <label for="published_yes" class="form-check-label">Yes</label>
                            </div>

                            <div class="form-check form-check-inline">
                                {{ Form::radio('published', 0, (!isset($post->published) || $post->published == 0 ? true : false ), ['id' => 'published_no', 'class' => 'form-check-input']) }}
                                <label for="published_no" class="form-check-label">No</label>
                            </div>
                            {!! $errors->first('published', '<p class="help-block">:message</p>') !!}
                        </div>
                        @endcan

                        @can('publish_resource')
                        <div class="form-group">
                            <div>{{ __('Allow Feedback?') }}</div>

                            <div class="form-check form-check-inline">
                                {{ Form::radio('allow_feedback', 1, (isset($post->allow_feedback) && $post->allow_feedback == 1 ? true : false ), ['id' => 'allow_feedback_yes', 'class' => 'form-check-input']) }}
                                <label for="allow_feedback_yes" class="form-check-label">Yes</label>
                            </div>

                            <div class="form-check form-check-inline">
                                {{ Form::radio('allow_feedback', 0, (!isset($post->allow_feedback) || $post->allow_feedback == 0 ? true : false ), ['id' => 'allow_feedback_no', 'class' => 'form-check-input']) }}
                                <label for="allow_feedback_no" class="form-check-label">No</label>
                            </div>
                            {!! $errors->first('allow_feedback', '<p class="help-block">:message</p>') !!}
                        </div>

                        <div class="form-group">
                            <div>{{ __('Allow Download?') }}</div>

                            <div class="form-check form-check-inline">
                                {{ Form::radio('allow_download', 1, (isset($post->allow_download) && $post->allow_download == 1 ? true : false ), ['id' => 'allow_download_yes', 'class' => 'form-check-input']) }}
                                <label for="allow_download_yes" class="form-check-label">Yes</label>
                            </div>

                            <div class="form-check form-check-inline">
                                {{ Form::radio('allow_download', 0, (!isset($post->allow_download) || $post->allow_download == 0 ? true : false ), ['id' => 'allow_download_no', 'class' => 'form-check-input']) }}
                                <label for="allow_download_no" class="form-check-label">No</label>
                            </div>
                            {!! $errors->first('allow_download', '<p class="help-block">:message</p>') !!}
                        </div>

                        <div class="form-group">
                            <div>{{ __('Allow Edit?') }}</div>

                            <div class="form-check form-check-inline">
                                {{ Form::radio('allow_edit', 1, (isset($post->allow_edit) && $post->allow_edit == 1 ? true : false ), ['id' => 'allow_edit_yes', 'class' => 'form-check-input']) }}
                                <label for="allow_edit_yes" class="form-check-label">Yes</label>
                            </div>

                            <div class="form-check form-check-inline">
                                {{ Form::radio('allow_edit', 0, (!isset($post->allow_edit) || $post->allow_edit == 0 ? true : false ), ['id' => 'allow_edit_no', 'class' => 'form-check-input']) }}
                                <label for="allow_edit_no" class="form-check-label">No</label>
                            </div>
                            {!! $errors->first('allow_edit', '<p class="help-block">:message</p>') !!}
                        </div>
                        <div class="form-group">
                            <div>{{ __('Locked?') }}</div>

                            <div class="form-check form-check-inline">
                                {{ Form::radio('is_locked', 1, (isset($post->is_locked) && $post->is_locked == 1 ? true : false ), ['id' => 'is_locked_yes', 'class' => 'form-check-input']) }}
                                <label for="is_locked_yes" class="form-check-label">Yes</label>
                            </div>

                            <div class="form-check form-check-inline">
                                {{ Form::radio('is_locked', 0, (!isset($post->is_locked) || $post->is_locked == 0 ? true : false ), ['id' => 'is_locked_no', 'class' => 'form-check-input']) }}
                                <label for="is_locked_no" class="form-check-label">No</label>
                            </div>
                            {!! $errors->first('is_locked', '<p class="help-block">:message</p>') !!}
                        </div>
                        @endcan

                        @can('approve_resource')
                        <div class="form-group">
                            <div>{{ __('Is Featured?') }}</div>

                            <div class="form-check form-check-inline">
                                {{ Form::radio('is_featured', 1, (isset($post->is_featured) && $post->is_featured == 1 ? true : false ), ['id' => 'is_featured_yes', 'class' => 'form-check-input']) }}
                                <label for="is_featured_yes" class="form-check-label">Yes</label>
                            </div>

                            <div class="form-check form-check-inline">
                                {{ Form::radio('is_featured', 0, (!isset($post->is_featured) || $post->is_featured == 0 ? true : false ), ['id' => 'is_featured_no', 'class' => 'form-check-input']) }}
                                <label for="is_featured_no" class="form-check-label">No</label>
                            </div>
                            {!! $errors->first('is_featured', '<p class="help-block">:message</p>') !!}
                        </div>

                        <div class="form-group">
                            <label for="approval_status" class="col-xs-12 require">{{__('Approval Status')}}</label>
                            {!! Form::select('approval_status', $approvalStatus,
                            old('approval_status', isset($post->approval_status) ? $post->approval_status: ''),
                            ['placeholder' => '-Approval Status-', 'class' => $errors->has('approval_status')?
                            'form-control
                            is-invalid' : 'form-control' ]) !!}
                            {!! $errors->first('approval_status', '<div class="invalid-feedback">:message</div>') !!}
                        </div>
                        @endcan

                    </div>
                </div>


                <div class="form-group">
                    @if (auth()->user()->can('add_resource') || auth()->user()->can('edit_resource'))
                    <div class="btn-group">
                        <button class="btn btn-primary" type="submit" name="btnSave" value="1">
                    {{ __('Save') }}
                    </button>
                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
                        <div class="dropdown-menu">
                            <button class="dropdown-item" type="submit" name="btnSaveClose" value="1">
                        {{ __('Save & Close') }}
                        </button>
                            <button class="dropdown-item" type="submit" name="btnSaveNew" value="1">
                        {{ __('Save & New') }}
                        </button>
                            <div class="dropdown-divider"></div>
                        </div>
                    </div>
                    @endif
                    <a href="{{ route('admin.resource.index') }}" class="btn btn-flat">{{ __('Cancel') }}</a>
                </div>
                </form>
            </div>
        </div>
        <!-- END Default Elements -->
    </div>
</div>
</div>
@stop

@section('css')
@parent
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection

@section('js')
@parent
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<!-- ckeditor.js, load it only in the page you would like to use CKEditor (it's a heavy plugin to include it with the others!) -->
<script>
$(document).ready(function() {

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

    $('#other-keyword-list').select2({
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
});
</script>

<script>

        new Vue({
            el: '#elibrary_root',
            data: {
                messages: {

                },
                publishing_year_month: {{ Carbon\Carbon::now()->format('Ym') }},
                year_month_error: false
            },
            //components: [commodity_component],

            mounted() {
            },
            methods: {
                validateBeforeSubmit: function(e) {
                    this.$validator.validateAll().then((result) => {

                        var publishing_year = document.getElementById('publishing_year').value;
                        var publishing_month = document.getElementById('publishing_month').value;

                        if ( publishing_year && publishing_month) {
                            var month = publishing_month.padStart(2, '0');
                            if(parseInt(publishing_year + '' + month) > parseInt(this.publishing_year_month)) {
                                this.year_month_error = true;
                                result = false;
                            }
                        }

                        if (result) {
                            // eslint-disable-next-line
                            return true;
                        }
                        e.preventDefault();
                    });
                }
            }

        });
        </script>
    @endsection
