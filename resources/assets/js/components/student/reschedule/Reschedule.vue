<template src="./reschedule-template.html"></template>

<script>
import PhoneNumberFormat from "../../PhoneNumberFormat";
import {dateFormat} from "vue-filter-date-format";
import {dateParse} from "@vuejs-community/vue-filter-date-parse";

let today = new Date();
const year = today.getFullYear();
const month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0
const day = String(today.getDate()).padStart(2, '0');
const startDateNow = `${year}-${month}-${day}`; // Output: "2024-11-13"

export default {
    name: 'Reschedule',
    data() {
        return {
            classError: '',
            error_title: '',
            error_billing_rate_id: '',
            error_color: '',
            error_end_time: '',
            error_status: '',
            error_start_date: '',
            error_start_time: '',
            allTimes: [],
            billingRates: [],
            businessHours: [],
            closed: [],
            colors: [
                {name: 'Blue', code: '#5499C7'},
                {name: 'Red', code: '#CD6155'},
                {name: 'Purple', code: '#A569BD'},
                {name: 'Green', code: '#52BE80'},
                {name: 'Yellow', code: '#F4D03F'},
                {name: 'Orange', code: '#E59866'},
                {name: 'Grey', code: '#85929E'},
            ],
            duration: [
                {name: '15 minutes', value: "15"},
                {name: '30 minutes', value: "30"},
                {name: '45 minutes', value: "45"},
                {name: '60 minutes', value: "60"},
            ],
            disableUpdateButton: false,
            lesson: {
                student_id: null,
                billing_rate_id: null,
                title: null,
                color: null,
                start_date: '',
                end_date: '',
                start_time: '',
                recurrence: null,
                end_time: null,
                status: null,
            },
            modelConfig: {
                type: 'string',
                mask: 'YYYY-MM-DD',
            },
            showModal: false,
            statuses: [
                {name: 'Scheduled', value: "Scheduled"},
                {name: 'Re-Scheduled', value: "Re-Scheduled"},
                {name: 'Cancelled', value: "Cancelled"},
            ],
            startDate: '',
            student: {
                first_name: '',
                last_name: '',
                email: '',
                phone: ''
            },
        }
    },

    components: {
        PhoneNumberFormat,
    },

    filters: {
        capitalising: function (data) {
            let capitalized = [];
            data.split(' ').forEach(word => {
                capitalized.push(
                    word.charAt(0).toUpperCase() +
                    word.slice(1).toLowerCase()
                )
            });
            return capitalized.join(' ');
        },

        toCurrency: function (value) {
            let formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            });
            return formatter.format(value);
        }
    },

    watch: {
        startDate: {
            handler: function () {
                this.getDates();
            },
            deep: true,
        },
    },

    mounted: function () {
        this.getData();
    },

    methods: {
        dateFormat,
        dateParse,
        deleteLesson: function (id) {
            let self = this;
            let params = Object.assign({}, self.lesson);
            axios.delete('/web/lesson/delete/' + id, params)
                .then(() => {
                    window.location ='/calendar';
                    this.$notify({
                        type: 'warn',
                        title: 'Deleted',
                        text: 'Lesson was deleted.',
                        duration: 10000,
                    });
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not delete lesson.',
                        duration: 10000,
                    });
                });
        },

        getTimeDifference: function getTimeDifference(startTime, endTime) {
            const start = new Date('1970-01-01T' + startTime);
            const end = new Date('1970-01-01T' + endTime);
            const diff = end.getTime() - start.getTime();
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            // const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            return {hours, minutes};
        },

        getDuration: function (diff) {
            let totalTime = diff.hours + '.' + diff.minutes;
            switch (totalTime) {
                case '0.15':
                    this.duration = [
                        {name: '15 minutes', value: "15"},
                        {name: '30 minutes', value: "30"},
                        {name: '45 minutes', value: "45"},
                        {name: '60 minutes', value: "60"},
                    ];
                    break;
                case '0.30':
                    this.duration = [
                        {name: '15 minutes', value: "15"},
                        {name: '30 minutes', value: "30"},
                    ];
                    break;
                case '0.45':
                    this.duration = [
                        {name: '15 minutes', value: "15"},
                    ];
                    break;
                case '1.0':
                    this.duration = [
                        {name: '15 minutes', value: "15"},
                        {name: '30 minutes', value: "30"},
                        {name: '45 minutes', value: "45"},
                        {name: '60 minutes', value: "60"},
                    ];
                    break;
                case '1.15':
                    this.duration = [
                        {name: '15 minutes', value: "15"},
                        {name: '30 minutes', value: "30"},
                        {name: '45 minutes', value: "45"},
                        {name: '60 minutes', value: "60"},
                    ];
                    break;
                default:
                    this.duration = [
                        {name: '15 minutes', value: "15"},
                    ];
            }
        },

        checkDurationTimes: function (event) {
            this.lesson.status = 'Re-Scheduled';
            const allTimesArray = [];
            for (const key in this.allTimes) {
                allTimesArray.push(this.allTimes[key]);
            }
            let index = allTimesArray.indexOf(event.target.value);
            let nextTime = allTimesArray[index + 1];
            const diff = this.getTimeDifference(event.target.value, nextTime);
            this.getDuration(diff);
        },

        clearErrorData: function () {
            let self = this;
            self.classError = '';
            self.error_billing_rate_id = '';
            self.error_title = '';
            self.error_color = '';
            self.error_start_date = '';
            self.error_end_time = '';
            self.error_status = '';
            self.error_start_time = '';
        },

        getData: function () {
            let parameters = this.$route.fullPath;
            let id = parameters.split('/').pop();
            axios.get('/web/lesson/reschedule/' + id)
                .then((response) => {
                    this.lesson = response.data.lesson;
                    this.startDate = response.data.lesson.start_date;
                    this.student = response.data.lesson.student;
                    this.allTimes = response.data.allTimes;
                    this.billingRates = response.data.billingRates;
                    this.businessHours = response.data.businessHours;
                    this.holidays = response.data.holidays;
                    this.closedDays();
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        getDates: function () {
            let self = this;
            let parameters = this.$route.fullPath;
            let id = parameters.split('/').pop();
            axios.get('/web/lesson/reschedule/' + id + '/' + self.startDate)
                .then((response) => {
                    this.lesson = response.data.lesson;
                    this.startDate = self.startDate;
                    this.student = response.data.lesson.student;
                    this.allTimes = response.data.allTimes;
                    this.billingRates = response.data.billingRates;
                    this.businessHours = response.data.businessHours;
                    this.holidays = response.data.holidays;
                    this.closedDays();
                })
                .catch((error) => {
                    console.log(error);
                    // self.getErrorMessage(error);
                    // this.$notify({
                    //     type: 'error',
                    //     title: 'Error',
                    //     text: 'Could not load data.',
                    //     duration: 10000,
                    // });
                });
        },

        closedDays: function () {
            this.businessHours.forEach((row) => {
                if (row.active === false) {
                    this.closed.push({'weekdays': row.day + 1});
                }
            });

            this.holidays.forEach((day) => {
                if (day.all_day === true) {
                    this.closed.push({'start': new Date(day.start_date), 'end': new Date(day.end_date)});
                }
            });
        },

        getErrorMessage: function (error) {
            let self = this;
            self.error_billing_rate_id = error.response.data.error.billing_rate_id;
            self.error_title = error.response.data.error.title;
            self.error_color = error.response.data.error.color;
            self.error_end_time = error.response.data.error.end_time;
            self.error_start_date = error.response.data.error.start_date;
            self.error_start_time = error.response.data.error.start_time;
            self.error_status = error.response.data.error.status;
            self.classError = 'has-error';
        },

        showModalDelete: function (id) {
            let self = this;
            self.showModal = true;
            self.id = id;
        },

        updateLesson: function () {
            this.disableUpdateButton = true;
            let self = this;
            if (self.lesson.end_time && self.lesson.start_time === undefined) {
                this.disableUpdateButton = false;
                return this.$notify({
                    type: 'warn',
                    title: 'Warning',
                    text: 'Select start time.',
                    duration: 10000,
                });
            }
            self.lesson.student_id = this.student.id;
            self.lesson.start_date = this.startDate;
            let params = Object.assign({}, self.lesson);
            axios.patch('/web/lesson/reschedule/update', params)
                .then(() => {
                    self.clearErrorData();
                    self.getData();
                    this.disableUpdateButton = false;
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'Lesson was updated.',
                        duration: 10000,
                    });
                })
                .catch((error) => {
                    console.log(error);
                    this.disableUpdateButton = false;
                    self.getErrorMessage(error);
                    // this.$notify({
                    //     type: 'error',
                    //     title: 'Error',
                    //     text: 'Could not save lesson(s).',
                    //     duration: 10000,
                    // });
                });
        },
    }
}
</script>
