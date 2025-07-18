require('./bootstrap');
import Vue from 'vue';
import vuetify from './plugins/vuetify';

Vue.component('task-listing', require('./components/Tasks/TaskListing.vue').default);

const app = new Vue({
    el: '#vue-app',
    vuetify,
});
