/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Notifications from 'vue-notification'
import Vue from 'vue';
import VueRouter from 'vue-router';
import VueFilterDateParse from '@vuejs-community/vue-filter-date-parse';
import VueFilterDateFormat from '@vuejs-community/vue-filter-date-format';
import {VuejsDatatableFactory} from 'vuejs-datatable';
import 'vuejs-datatable/dist/themes/bootstrap-3.esm';
import VCalendar from 'v-calendar';

require('./bootstrap');

window.Vue = require('vue').default;

Vue.use(VueRouter);
Vue.use(VueFilterDateParse);
Vue.use(VueFilterDateFormat);
Vue.use(VuejsDatatableFactory);
Vue.use(Notifications);
Vue.use(VCalendar, {
    // Use <vc-calendar /> instead of <v-calendar />
    // ...other defaults
});

const router = new VueRouter({
    mode: 'history',
    routes: []
})
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('billingRate', require('./components/teacher/BillingRate.vue').default);
Vue.component('blogs', require('./components/admin/blog/Blogs.vue').default);
Vue.component('contacts', require('./components/admin/contact/Contacts.vue').default);
Vue.component('invoiceList', require('./components/invoice/InvoiceList.vue').default);
Vue.component('lessons', require('./components/Lessons.vue').default);
Vue.component('profile', require('./components/student/Profile.vue').default);
Vue.component('students', require('./components/admin/student/Students.vue').default);
Vue.component('studentList', require('./components/student/StudentList.vue').default);
Vue.component('teachers', require('./components/admin/teacher/Teachers.vue').default);
Vue.component('users', require('./components/admin/user/Users.vue').default);
Vue.component('reportStudentStatusBar', require('./components/reports/StudentStatusBar.vue').default);


const app = new Vue({
    el: '#app',
    router,
});
