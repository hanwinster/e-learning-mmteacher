<template>
    <span>
        <a href="#" title="Unlike" v-if="isFavourited" @click.prevent="unFavourite(post)">
            <i class="fa fa-heart fa-3x"></i>
        </a>
        <a href="#" title="Like" v-else @click.prevent="favourite(post)">
            <i class="fa fa-heart-o fa-3x"></i>
        </a>
    </span>
</template>

<script>
    export default {
        props: ['post', 'favourited'],

        data: function() {
            return {
                isFavourited: '',
            }
        },

        mounted() {
            this.isFavourited = this.isFavorite ? true : false;
        },

        computed: {
            isFavorite() {
                return this.favourited;
            },
        },

        methods: {
            favourite(post) {
                axios.get('/en/resource/'+ post + '/favourite')
                    .then(response => this.isFavourited = true)
                    .catch(response => console.log(response.data));
            },

            unFavourite(post) {
                axios.get('/en/resource/'+ post + '/unfavourite')
                    .then(response => this.isFavourited = false)
                    .catch(response => console.log(response.data));
            }
        }
    }
</script>
