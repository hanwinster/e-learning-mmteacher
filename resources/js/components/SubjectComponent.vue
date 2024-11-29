<template>
<div id="subject-component">
    <div class="list-unstyled">
        <div class="p-1 hover-shadow-1" v-for="(subject, index) in subjects" :key="index">
        <label v-bind:for="'subject_' + subject.id" class="d-flex justify-content-start">

        <input class="mr-3" type="checkbox" :id="'subject_' + subject.id" v-model="selected" name="subject" :value="subject" v-on:change="selectSubject">
            {{ subject.title }}
            </label>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: ['subject_id'],

    data: function () {
        return {
            subjects: [],
            selected: []
        }
    },

    mounted() {
        this.fetchPosts('api/subject');
    },

    computed: {
        selected_subjects: function()
        {
            console.log(this.selected)
        }
    },
    methods: {
        fetchPosts: function (pageUrl) {
            axios.get(pageUrl).then(response => {
                this.subjects = response.data.data;
                // console.log(this.subjects)
                // console.log(response.data.data)
            }).catch(function (error) {
                if (error.response) {
                    // The request was made and the server responded with a status code
                    // that falls out of the range of 2xx
                    console.log(error.response.data);
                    console.log(error.response.status);
                    console.log(error.response.headers);
                }
            });
        },

        selectSubject: function (subject) {

            // parent component will catch the emitted event
            /*
            eg.
            <subject-component v-on:on-select-subject="chooseSubject">
            </subject-component>
             */
            this.$emit('on-select-subject', this.selected);
        },
    }
}
</script>
