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
    <main class="main-content" id="elibrary_app">
        <section class="section bg-gray pt-7">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        {{--
                        <div class="form-group input-group">
                            <input type="text" class="form-control" placeholder="Search for..." name="search" id="search" v-model="search" value="" v-on:keyup.enter="getPosts">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" v-on:click="getPosts">{{ __('Go!') }}</button>
                            </div>
                        </div>
                        <div class="text-right mb-3">
                            <small>
                            <a href="{{ route('search.advanced') }}">{{ __('Advanced Search') }}</a>
                            </small>
                        </div>
                        --}}

                        <h3>{{ __('Subjects') }}</h3>
                        <subject-component v-on:on-select-subject="getSubjects"></subject-component>

                        <h3 class="mt-5">{{ __('Resource Formats') }}</h3>
                        <resource-format v-on:on-select-format="getFormats"></resource-format>

                    </div>
                    <div class="col-md-9">
                        <div v-if="!searching">
                            @include('frontend.elibrary.partials.how-to-use', ['how_to_slug' => $how_to_slug])
                        </div>
                        <div class="mt-3 mb-3">
                            Filter:
                            @verbatim
                                <span v-for="(subject, index) in subjects" :key="index" class="badge badge-primary mr-1">{{ subject.title }}</span>
                            @endverbatim
                            @verbatim
                                <span v-for="(format, index) in resource_formats" :key="index" class="badge badge-secondary mr-1">{{ format.title }}</span>
                            @endverbatim
                        </div>
                        <div>
                            <div class="row gap-y gap-2">
                                <div class="col-md-12">
                                    <resource-list-item v-for="(post, index) in posts" :post="post" :key="index"></resource-list-item>

                                    <div class="pagination" v-if="pagination.next || pagination.prev">
                                        @verbatim
                                            <button type="button" class="btn btn-sm" @click="fetchPosts(pagination.prev)" :disabled="!pagination.prev">
                                                Previous
                                            </button>
                                            <span class="m-3"> Page {{pagination.meta.current_page}}
                                    of {{ pagination.meta.last_page }} </span>
                                            <button type="button" class="btn btn-default" @click="fetchPosts(pagination.next)" :disabled="!pagination.next">
                                                Next
                                            </button>

                                            <span class="m-3">Total: {{pagination.meta.total}}</span>
                                        @endverbatim
                                    </div>
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

@section('script')
    @parent
    {{-- <script src="{{ asset('js/subject-component.js') }}"></script> --}}
    <script>
        new Vue({
            el: '#app-root', // '#elibrary_app',
            data: {
                searching: false,
                posts: [],
                pagination: {},
                search: "{{ request('search') ?? '' }}",
                subjects: [],
                resource_formats: [],
                auth: {{ Auth::check() ? 'true' : 'false' }} ,
                links: {
                    resource: 'api/resource',
                    search: 'api/advanced-search'
                },
                private_links:
                    {
                        resource: 'api/user/resource',
                        search: 'api/user/advanced-search'
                    }
            },
            mounted: function() {
                if (this.search){
                    this.getPosts()
                } else {
                    this.fetchPosts(this.getResourceLink())
                }
            },
            methods: {
                getResourceLink: function()
                {
                    return this.auth == true ? this.private_links.resource : this.links.resource
                },

                getSearchLink: function()
                {
                    return this.auth == true ? this.private_links.search : this.links.search
                },

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

                /*
                * Get latest resources
                */
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
                    this.searching = true
                    var subjects = this.subjects.map(function(s) { return s.id})
                    var formats = this.resource_formats.map(function(f) { return f.id })

                    axios.get(this.getSearchLink(), {
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
