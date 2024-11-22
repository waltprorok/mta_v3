/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Notifications from 'vue-notification';
import wysiwyg from "vue-wysiwyg";
import Vue from 'vue';
import VueRouter from 'vue-router';
import VueFilterDateParse from '@vuejs-community/vue-filter-date-parse';
import VueFilterDateFormat from '@vuejs-community/vue-filter-date-format';
import {VuejsDatatableFactory} from 'vuejs-datatable';
import 'vuejs-datatable/dist/themes/bootstrap-3.esm';
import VCalendar from 'v-calendar';
import VueChatScroll from 'vue-chat-scroll';

require('./bootstrap');

window.Vue = require('vue').default;

Vue.use(wysiwyg, {
    // { [module]: boolean (set true to hide) }
    hideModules: { 'image': true, 'underline': true, 'code': true, },
    forcePlainTextOnPaste: true,
    // you can override icons too, if desired
    // just keep in mind that you may need custom styles in your application to get everything to align
    // iconOverrides: { "bold": "<i class="your-custom-icon"></i>" },
    // if the image option is not set, images are inserted as base64
    // image: {
    //     uploadURL: "/api/myEndpoint",
    //     dropzoneOptions: {}
    // },
    // limit content height if you wish. If not set, editor size will grow with content.
    // maxHeight: "1000px"
});
Vue.use(VueChatScroll);
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
Vue.component('createBlog', require('./components/admin/blog/Create.vue').default);
Vue.component('editBlog', require('./components/admin/blog/Edit.vue').default);
Vue.component('contacts', require('./components/admin/contact/Contacts.vue').default);
Vue.component('dashboard', require('./components/dashboard/Dashboard.vue').default);
Vue.component('instruments', require('./components/instrument/Instruments.vue').default);
Vue.component('invoiceCreate', require('./components/invoice/create/InvoiceCreate.vue').default);
Vue.component('invoiceList', require('./components/invoice/list/InvoiceList.vue').default);
Vue.component('listOfPayments', require('./components/invoice/payments/ListOfPayments.vue').default);
Vue.component('holiday', require('./components/holiday/Holiday.vue').default);
Vue.component('lessons', require('./components/Lessons.vue').default);
Vue.component('lesson', require('./components/student/schedule/Lesson.vue').default);
Vue.component('profile', require('./components/student/profile/Profile.vue').default);
Vue.component('reschedule', require('./components/student/reschedule/Reschedule.vue').default);
Vue.component('students', require('./components/admin/student/Students.vue').default);
Vue.component('support', require('./components/admin/support/Support.vue').default);
Vue.component('studentList', require('./components/student/list/StudentList.vue').default);
Vue.component('teachers', require('./components/admin/teacher/Teachers.vue').default);
Vue.component('users', require('./components/admin/user/Users.vue').default);
Vue.component('reportCompletedLessonsLine', require('./components/reports/CompletedLessonsLine.vue').default);
Vue.component('reportStudentStatusBar', require('./components/reports/StudentStatusBar.vue').default);
Vue.component('messages', require('./components/messages/Index.vue').default);
Vue.component('plans', require('./components/admin/billing/Plans.vue').default);
Vue.component('payments', require('./components/payments/Payments.vue').default);


const app = new Vue({
    el: '#app',
    router,
});
