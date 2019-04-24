
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
Vue.use(BootstrapVue);
import Notifications from 'vue-notification';

Vue.use(Notifications)


// Import component
import Loading from 'vue-loading-overlay';
// Import stylesheet
import 'vue-loading-overlay/dist/vue-loading.css';
// Init plugin
Vue.use(Loading);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';
import VoerroTagsInput from '@voerro/vue-tagsinput';
Vue.component('tags-input', VoerroTagsInput);
Vue.component('VueCtkDateTimePicker', VueCtkDateTimePicker);
Vue.component('draft-data-list', require('./components/DraftDataList.vue'));
Vue.component('setting', require('./components/Setting.vue'));
Vue.component('api-passport', require('./components/ApiPassport.vue'));
Vue.component('keyword-list', require('./components/KeywordList.vue'));
Vue.component('keyword-layout', require('./components/KeywordLayout.vue'));
Vue.component('job-history', require('./components/JobHistory.vue'));


// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});
