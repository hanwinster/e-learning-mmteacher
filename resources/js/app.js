
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VeeValidate from 'vee-validate';
import Divider from './components/Divider.vue';
import Card from './components/Card.vue';
//import DiscussionBoard from './components/DiscussionBoard.vue';

Vue.use(VeeValidate, {
    classes: true,
    classNames: {
      // valid: 'is-valid',
      invalid: 'is-invalid'
    }
  });

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.config.devtools = true;
Vue.component('divider', Divider);
Vue.component('card', Card);
//Vue.component('discussion-board', DiscussionBoard);
//Vue.component('example-component', require('./components/ExampleComponent.vue'));
//Vue.component('favourite-component', require('./components/Favourite.vue'));
//Vue.component('subject-component', require('./components/SubjectComponent.vue'));
//Vue.component('resource-format', require('./components/ResourceFormat.vue'));
//Vue.component('resource-list-item', require('./components/ResourceListItem.vue'));
//Vue.component('link-report-component', require('./components/LinkReport.vue'));
Vue.component('ppt-viewer', require('./components/Viewer'));
// Vue.component('resource-modal-component', require('./components/ResourceModalComponent.vue'));



const app = new Vue({
    el: '#app-root',
    data: {

    },
    mounted() {
        
        // window.Echo('chat')
        // .listen('MessageSent', (e) => {
        //     console.log('from echo messag sent event ', e);
        //     // this.messages.push({
        //     //   message: e.message.message,
        //     //   user: e.user
        //     // });
        // });
    },
    methods: {
        validateBeforeSubmit: function(e) {
            e.preventDefault(); 
            this.$validator.validateAll().then((result) => {        
                if (result) {                   
                    return true;
                }               
            });
        }
    }
});
