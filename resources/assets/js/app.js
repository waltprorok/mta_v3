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

Vue.component('alert', require('./components/alert/Alert.vue'));
Vue.component('billingRate', require('./components/teacher/BillingRate.vue'));
Vue.component('blogs', require('./components/admin/blog/Blogs.vue'));
Vue.component('contacts', require('./components/admin/contact/Contacts.vue'));
Vue.component('lessons', require('./components/Lessons.vue'));
Vue.component('students', require('./components/admin/student/Students.vue'));
Vue.component('studentList', require('./components/students/StudentList.vue'));
Vue.component('teachers', require('./components/admin/teacher/Teachers.vue'));
Vue.component('users', require('./components/admin/user/Users.vue'));


const app = new Vue({
    el: '#app'
});
