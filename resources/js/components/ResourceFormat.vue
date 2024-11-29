<template>
<div class="resource-format-component">
    <div class="list-unstyled">
        <div class="p-1 hover-shadow-1" v-for="(format, index) in formats" :key="index">
        <label v-bind:for="'format_' + format.id" class="d-flex justify-content-start">
            <input class="mr-3" type="checkbox" :id="'format_' + format.id" v-model="selected" name="resource_format" :value="format" v-on:change="selectFormat">
            {{ format.title }}
            </label>
        </div>
    </div>
</div>
</template>

<script>
export default {
    data: function () {
        return {
            formats: [],
            selected: []
        }
    },

    mounted() {
        this.fetchPosts('api/resource-format');
    },

    computed: {

    },
    methods: {
        fetchPosts: function (pageUrl) {
            axios.get(pageUrl).then(response => {
                this.formats = response.data;
            }).catch(function (error) {
                if (error.response) {
                    // The request was made and the server responded with a status code
                    // that falls out of the range of 2xx
                    console.log(error.response.status);
                    console.log(error.response.headers);
                }
            });
        },

        selectFormat: function (format) {

            // parent component will catch the emitted event
            /*
            eg.
            <subject-component v-on:on-select-subject="chooseSubject">
            </subject-component>
             */
            this.$emit('on-select-format', this.selected);
        },
    }
}
</script>
