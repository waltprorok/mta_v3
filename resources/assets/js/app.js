/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

let axios = require('axios');

import Vue from 'vue';
import VueFilterDateParse from '@vuejs-community/vue-filter-date-parse';
import VueFilterDateFormat from '@vuejs-community/vue-filter-date-format';
import {VuejsDatatableFactory} from 'vuejs-datatable';
import 'vuejs-datatable/dist/themes/bootstrap-3.esm';


Vue.use(VuejsDatatableFactory);
Vue.use(VueFilterDateParse);
Vue.use(VueFilterDateFormat);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('contacts', require('./components/Contacts.vue'));
Vue.component('example', require('./components/Example.vue'));
Vue.component('lessons', require('./components/Lessons.vue'));

const app = new Vue({
    el: '#app'
});
