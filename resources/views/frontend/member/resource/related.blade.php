@extends('frontend.layouts.default')
@section('title', __('Related Resources - '). $post->title)

@section('header')
<div class="section mb-0 pb-0">
</div>
@endsection

@section('content')
<main class="main-content">
    <section class="section pt-5 bg-gray overflow-hidden">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-2">
                    @include('frontend.member.partials.sidebar')
                </div>

                <div class="col-md-10 mx-auto">
                    <h1>{{ __('Related Resources - ').$post->title }}</h1>
                    <div class="card">
                        <div class="card-body">

                    <div class="row">
                        <div class="col-md-9">
                            <div class="block-forms">

                                @if (isset($post->id))
                                <form action="{{ route('member.resource.add-related',$post->id) }}" method="post">

                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="row" id="elibrary_root">
                                        <div class="col-md-12">{{ __('Please choose the resource to add to the related list.') }}</div>

                                        <div class="col-sm-10">
                                            <div class="form-group {!! $errors->first('resource_id', 'has-error') !!}">
                                                <input type="hidden" name="resource_id" v-model="selectedResource.id">
                                                <resource-modal-component v-on:on-select-resource="chooseResource" resource_id="{{  isset($post->id) ? $post->id: '' }}"></resource-modal-component>
                                                {!! $errors->first('resource_id', '
                                                <p class="help-block">:message</p>') !!}

                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <button data-toggle="modal" data-target="#modal-resource" class="btn btn-default" type="button">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group d-flex justify-content-between" >
                                                <button class="btn btn-primary" type="submit" name="btnAdd" value="1">
                                                    {{ __('Add') }}
                                                </button>
                                                @if (!$canApprove)
                                                <span>If you are ready for the submission, click <strong>Next</strong> button.</span>
                                                <a class="btn btn-primary" href="{{ route('member.resource.submit-request', $post->id) }}">
                                                    {{ __('Next') }}
                                                </a>
                                                @else
                                                <a class="btn btn-primary" href="{{ route('member.resource.index') }}">
                                                {{ __('Close') }}
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <br>

                                @endif
                                @if (isset($post->id))

                                <h4>{{ __('Existing Related Resources') }}</h4>
                                <!--
                                <form action="{{ route('member.resource.related',$post->id) }}" method="get">
                                <div class="row">
                                        <div class="col-md-2">
                                            <input type="text" placeholder="ID" name="resource_id" id="resource_id" class="form-control" value="{{ request('resource_id') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" placeholder="Title.." name="title" id="title" class="form-control" value="{{ request('title') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-default">{{ __('Search') }}</button>
                                            <a href="{{ route('member.resource.related', $post->id) }}" class="btn btn-default">{{ __('Reset') }}</a>
                                        </div>
                                </div>
                                </form>
                                -->

                                <div>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th width="50">{{ __('ID') }}</th>
                                                <th>{{ __('Title') }}</th>
                                                <th width="180">{{ __('Author') }}</th>
                                                <th width="180">{{ __('Added At') }}</th>
                                                <th width="80">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($related_posts as $resource)
                                            <tr>
                                                <td>{{ $resource->id }}</td>
                                                <td>
                                                    <a href="{{ route('resource.show', $resource->resource->slug) }}">
                                                    {{ $resource->resource->title ?? '' }}
                                                    </a>
                                                    </td>
                                                <td>{{ $resource->resource->author ?? '' }}</td>
                                                <td>{{ $resource->created_at ?? '' }}</td>
                                                <td>
                                                    <a onclick="return confirm('Are you sure you want to delete?')" href="{{ route('member.resource.remove-related', [$resource->resource_id, $resource->related_resource_id]) }}"
                                                        class="btn btn-xs btn-danger confirmation-popup">
                                                                        <i class="fa fa-times"></i>
                                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    @if ($related_posts)
                                    <div class="pagination">
                                    </div>
                                    @endif
                                </div>
                                @else
                                <p class="alert alert-info alert-block">@lang('Please save the current measure to add new legal.')</p>
                                @endif
                            </div>
                        </div>

                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('script')
@parent
<script src="{{ asset('js/resource-modal-component.js') }}"></script>
<script>
new Vue({
    el: '#elibrary_root',
    data: {
        selectedResource: ''
    },
    //components: [commodity_component],

    mounted() {
    },
    methods: {
        chooseResource: function (passedResource) {
            this.selectedResource = passedResource;
        }
    }

});
</script>
@endsection
