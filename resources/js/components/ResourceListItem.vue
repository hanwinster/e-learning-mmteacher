<template>
<div class="resource-list-item">
<div class="card hover-shadow-7 my-3 p-3">
    <div class="row">
        <div class="col-md-4">
            <a v-if="post.cov1er_image" :href="post.permalink">
                <img class="fit-cover h-150 w-250" :src="post.cover_image.medium" :alt="post.title">
            </a>
        </div>

        <div class="col-md-6">
            <div class="p-0">
                <h5 class="m-0 overflow-hidden"><a :href="post.permalink">{{ post.title }}</a></h5>
                <small class="m-0 text-black-50">
                    <span class="text-info">{{ post.resource_format }}</span> - <i>by</i> {{ post.author }}
                </small>
                <p>
                    {{ post.summary }}
                </p>
            </div>
        </div>
        <div class="col-md-2">
            <div class="mb-1">

            </div>

            <div class="text-center">
                <small>
                <i>Last Updated</i>
                
                </small>
            </div>
            <div class="mt-1">
<!--                 <a class="btn btn-primary btn-sm" :href="baseUrl + '/resource/preview/' + post.id" data-featherlight="iframe" data-featherlight-iframe-allowfullscreen="true" data-featherlight-iframe-width="600" data-featherlight-iframe-height="381">Preview</a> -->
                <a class="btn btn-flat btn-sm" :href="baseUrl + '/resource/preview/' + post.id" data-provide="lightbox">Preview</a>
            </div>
        </div>
    </div>
</div>

</div>
</template>

<script>
export default {
    props: ['post'],

    data: function () {
        return {
            baseUrl: ''
        }
    },

    mounted() {
        this.baseUrl = window.axios.defaults.baseURL
    },

    computed: {

    },
    methods: {

    }
}
</script>
