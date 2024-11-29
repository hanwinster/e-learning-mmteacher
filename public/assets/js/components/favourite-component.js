var favourite_component = Vue.component('favourite-component', {
    template: `
    <span>
        <a title="Add to favourite" href="#" v-if="isFavorited" @click.prevent="unFavorite(post)">
            <i  class="fa fa-heart fa-2x"></i>
        </a>
        <a title="Remove from favourite" href="#" v-else @click.prevent="favorite(post)">
            <i  class="fa fa-heart-o fa-2x"></i>
        </a>
    </span>`,

        props: ['post', 'favorited'],

        data: function() {
            return {
                isFavorited: '',
            }
        },

        mounted() {
            this.isFavorited = this.isFavorite ? true : false;
        },

        computed: {
            isFavorite() {
                return this.favorited;
            },
        },

        methods: {
            favorite(post) {
                axios.get('/en/resource/'+post +'/favourite')
                    .then(response => this.isFavorited = true)
                    .catch(response => console.log(response.data));
            },

            unFavorite(post) {
                axios.get('/en/resource/'+post +'/unfavourite')
                    .then(response => this.isFavorited = false)
                    .catch(response => console.log(response.data));
            }
        }
    });

new Vue({
    el: '#app-root',
    data: {
    },
    //components: [commodity_component],

    mounted() {
    },
    methods: {

    }

});
