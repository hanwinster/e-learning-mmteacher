<template>
<div class="resource-component">
<!-- Fade In Modal -->
<div class="modal fade" id="modal-resource" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 800px">
        <div class="modal-content">
        <div class="component-resource">
        <div class="block block-themed block-transparent remove-margin-b">
            <div class="block-header bg-primary-dark">
                <ul class="block-options">
                    <li>
                        <button data-dismiss="modal" type="button"><i class="si si-close"></i>
                        </button>
                    </li>
                </ul>
                <h3 class="block-title">Select Resources</h3>
            </div>
            <div class="block-content">
                <div class="row">
                <div class="col-md-6">
                     <input type="text" name="q" value="" placeholder="Enter search term" class="form-control" v-model="search">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-default" type="button" @click="searchResources">Search</button>
                    <button class="btn btn-default" type="button" @click="fetchResources">Reset</button>
                </div>

                </div>
                <div class="block__contacts">
                    <table class="table table-borderd table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Publisher</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="resource in resources" :key="resource.id">
                            <td>{{ resource.id }}</td>
                            <td>{{ resource.title }}</td>
                            <td>{{ commodity.author }}</td>
                            <td>{{ commodity.publisher }}</td>
                            <td>{{ commodity.created_at }}</td>
                            <td>
                                <button class="btn btn-default"
                                        @click="selectResource(resource)" type="button">Select
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="pagination">
                        <button type="button" class="btn btn-default"
                                @click="fetchResources(pagination.prev_page_url)"
                                :disabled="!pagination.prev_page_url">
                            Previous
                        </button>
                        <span>Page {{pagination.current_page}}
                                    of {{pagination.last_page}}</span>
                        <button type="button" class="btn btn-default"
                                @click="fetchResources(pagination.next_page_url)"
                                :disabled="!pagination.next_page_url">Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close
            </button>
        </div>
    </div>
     </div>
    </div>
</div>
<!-- END Fade In Modal -->
</div>
</template>

<script>
export default {
    props: ['resource_id'],

    data: function () {
        return {
            search: '',
            resources: [],
            pagination: {},
            selectedResource: ''
        }
    },

    mounted() {
        this.fetchResource('/api/resource/' + this.resource_id);
        this.fetchResources('/api/user/resource');
    },

    methods: {
        fetchResources: function (pageUrl) {
            //pageUrl = 'api/resources';

            axios.get(pageUrl).then(response => {
                this.resources = response.data.data;
                this.makePagination(response.data);
            });
        },

        searchResources: function () {
            pageUrl = '/api/user/search?q=' + this.search;
            axios.get(pageUrl).then(response => {
                this.resources = response.data.data;
                this.makePagination(response.data);
            });
        },

        fetchResource: function (pageUrl) {
            if (this.resource_id != '') {
                axios.get(pageUrl).then(response => {
                    this.selectedResource = response.data;

                    this.$emit('on-select-resource', this.selectedResource);
                }).catch(function (error) {
                    if (error.response) {
                        // The request was made and the server responded with a status code
                        // that falls out of the range of 2xx
                        console.log(error.response.data);
                        console.log(error.response.status);
                        console.log(error.response.headers);
                    }
                });
            }
        },

        selectResource: function (resource) {
            this.selectedResource = resource;
            $('#modal-resource').modal('hide');

            // parent component will catch the emitted event
            /*
            eg.
            <commodity-component v-on:on-select-commodity="chooseResource" resource_id="">
            </commodity-component>
             */
            this.$emit('on-select-resource', this.selectedResource);
        },

        makePagination: function (data) {
            var pagination = {
                current_page: data.current_page,
                last_page: data.last_page,
                next_page_url: data.next_page_url,
                prev_page_url: data.prev_page_url
            };

            this.pagination = pagination;
        }
    }
}
