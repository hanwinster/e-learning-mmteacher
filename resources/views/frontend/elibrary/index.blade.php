@extends('frontend.layouts.default')

@section('title', __('eLibrary'))

@section('header')
<div class="section mb-0 pb-0">
    <header class="header text-white" style="background-color: #4CAF50; padding-top:50px; padding-bottom:50px;">
    @include('frontend.resource.partials.search')
    </header>
</div>
@endsection

@section('content')
<main class="main-content">
    <section class="section bg-gray">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                        <h3>{{ __('Subjects') }}</h3>
                        <div class="list-group">
                        @foreach ($subjects as $subject)
                        <a href="{{ $subject->path() }}" class="list-group-item list-group-item-action">{{ $subject->title }}</a>
                        @endforeach
                        </div>

                        <h3 class="mt-5">{{ __('Resource Formats') }}</h3>
                        <div class="list-group">
                        @foreach ($subjects as $subject)
                        <a href="{{ $subject->path() }}" class="list-group-item list-group-item-action">{{ $subject->title }}</a>
                        @endforeach
                        </div>
                </div>
                <div class="col-md-9">
                    <div>
                        <div>
                            @include('frontend.elibrary.partials.how-to-use')
                        </div>
                        <div class="row gap-y gap-2">
                            @foreach ($posts as $post)
                           <div class="col-md-4">
                            @include('frontend.resource.partials.card', $post)
                           </div>
                    {{--
                           <div class="col-md-4" data-shuffle="item" data-groups="g_{{ $post->id }}">
                        <a class="hover-move-up" href="{{ $post->path() }}">

                        <img src="{{ asset('assets/img/vector/3.png') }}" alt="{{ $post->title }}">
                          <div class="text-center pt-5">
                            <h5 class="fw-5001 mb-0">{{ $post->title }}</h5>
                            <small class="small-5 text-lightest text-uppercase ls-2">{{ $post->publisher ?? '' }}</small>
                          </div>
                        </a>
                      </div>
                      --}}
                            @endforeach

                            <div>
                                {{ $posts->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>
</main>
<!-- /.main-content -->
@endsection

@section('script') @parent
<script src="{{ asset('js/subject-component.js') }}"></script>
<script>
    new Vue({
        el: '#app-root', // '#elibrary_app',
        data: {
            posts: [],
            pagination: {},
            search: '',
            subjects: [],
            resource_formats: []
        },
        mounted: function() {
            this.fetchPosts('api/resource');
        },
        methods: {
            getSubjects: function (subjects) {
                this.subject = []
                this.subjects = subjects
                this.getPosts()
            },

            getFormats: function (formats) {
                 this.resource_formats = []
                 this.resource_formats = formats
                 this.getPosts()
            },

/*             fetchPosts: function()
            {

                axios.get('/api/resource')
                .then(function (response) {
                    this.posts = response.data.data
                })
                .catch(function (error) {
                    console.log(error);
                })
                .then(function () {
                    // always executed
                });
            }, */
            fetchPosts: function (pageUrl) {
                axios.get(pageUrl)
                .then(response => {
                    this.posts = response.data.data;
                    this.makePagination(response.data);
                })
                .catch(function (error) {
                    if (error.response) {
                        // The request was made and the server responded with a status code
                        // that falls out of the range of 2xx
                        console.log(error.response.status);
                        console.log(error.response.headers);
                    }
                });
            },

            getPosts: function()
            {
                var subjects = this.subjects.map(function(s) { return s.id})
                var formats = this.resource_formats.map(function(f) { return f.id })

                axios.get('/api/advanced-search', {
                    params: {
                    search: this.search,
                    subject: subjects,
                    resource_format: formats,
                    }
                })
                .then(response =>
                {
                    this.posts = response.data.data
                    this.makePagination(response.data);
                })
                .catch(function (error) {
                    console.log(error);
                })
                .then(function () {
                    // always executed
                });
            },
            makePagination: function (data) {
                var pagination = {
                    meta: data.meta,
                    current_page: data.current_page,
                    last_page: data.last_page,
                    next_page_url: data.next_page_url,
                    prev_page_url: data.prev_page_url,
                    prev: data.links.prev,
                    next: data.links.next,
                };

                this.pagination = pagination;
            }
        }
    })

</script>
@endsection
