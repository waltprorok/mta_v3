<template src="./lesson-template.html"></template>

<script>
import PhoneNumberFormat from "../../PhoneNumberFormat";

let today = new Date();
const year = today.getFullYear();
const month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0
const day = String(today.getDate()).padStart(2, '0');
const startDate = `${year}-${month}-${day}`; // Output: "2024-11-13"

export default {
    data() {
        return {
            classError: '',
            error_title: '',
            error_billing_rate_id: '',
            error_color: '',
            error_end_time: '',
            error_recurrence: '',
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
            disableSaveButton: false,
            lesson: {
                student_id: null,
                billing_rate_id: null,
                title: null,
                color: null,
                start_date: null,
                start_time: null,
                recurrence: null,
                end_time: null,
            },
            lessons: [],
            modelConfig: {
                type: 'string',
                mask: 'YYYY-MM-DD',
            },
            startDate: startDate,
            student: {
                first_name: '',
                last_name: '',
                email: '',
                phone: ''
            },
            studentScheduled: false,
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
                this.getData();
            },
            deep: true,
        },
    },

    mounted: function () {
        this.getData();
    },

    methods: {
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
                        // {name: '45 minutes', value: "45"},

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
            const startTime = event.target.value;

            const selectedDate = new Date(this.startDate + 'T12:00:00');
            const dayOfWeek = selectedDate.getDay();

            const businessHour = this.businessHours.find(
                row => Number(row.day) === dayOfWeek && Boolean(row.active)
            );

            if (!businessHour) {
                this.duration = [];
                this.lesson.end_time = null;
                return;
            }

            const toMinutes = (time) => {
                const [hours, minutes] = time.split(':').map(Number);
                return hours * 60 + minutes;
            };

            const remainingMinutes = toMinutes(businessHour.close_time) - toMinutes(startTime);

            const durations = [15, 30, 45, 60];

            this.duration = durations
                .filter(minutes => minutes <= remainingMinutes)
                .map(minutes => ({
                    name: `${minutes} minutes`,
                    value: String(minutes)
                }));

            // Reset duration if the previously selected value is no longer valid
            if (!this.duration.some(item => item.value === this.lesson.end_time)) {
                this.lesson.end_time = null;
            }
        },

        cancelLessonForm: function () {
            let self = this;
            self.clearErrorData();
            self.clearLessonData();
        },

        clearErrorData: function () {
            let self = this;
            self.classError = '';
            self.error_billing_rate_id = '';
            self.error_title = '';
            self.error_color = '';
            self.error_start_date = '';
            self.error_end_time = '';
            self.error_recurrence = '';
            self.error_start_time = '';
        },

        clearLessonData: function () {
            let self = this;
            self.startDate = startDate;
            self.lesson.student_id = null;
            self.lesson.billing_rate_id = null;
            self.lesson.title = null;
            self.lesson.color = null;
            self.lesson.start_date = null;
            self.lesson.recurrence = null;
            self.lesson.end_time = null;
            self.lesson.start_time = null;
        },

        getData: function () {
            let self = this;
            let parameters = this.$route.fullPath;
            let id = parameters.split('/').pop();
            axios.get('/web/student/lesson/' + id + '/' + self.startDate)
                .then((response) => {
                    this.student = response.data.student;
                    this.allTimes = response.data.allTimes;
                    this.billingRates = response.data.billingRates;
                    this.businessHours = response.data.businessHours;
                    this.holidays = response.data.holidays;
                    this.studentScheduled = response.data.studentScheduled;
                    this.lessons = response.data.lessons;
                    this.closedDays();
                })
                .catch((error) => {
                    console.log(error);
                    // self.getErrorMessage(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load data.',
                        duration: 6000,
                    });
                });
        },

        closedDays: function () {
            this.closed = [];

            // JavaScript weekdays: Sunday = 1, Saturday = 7
            for (let day = 0; day < 7; day++) {
                const businessHour = this.businessHours.find(
                    row => Number(row.day) === day
                );
                // Disable missing or inactive days
                if (!businessHour || !businessHour.active) {
                    this.closed.push({weekdays: day + 1});
                }
            }
            // Disable holidays
            this.holidays.forEach((holiday) => {
                if (holiday.all_day === true) {
                    this.closed.push({
                        start: new Date(holiday.start_date),
                        end: new Date(holiday.end_date)
                    });
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
            self.error_recurrence = error.response.data.error.recurrence;
            self.classError = 'has-error';
        },

        saveLessons: function () {
            this.disableSaveButton = true;
            let self = this;
            self.lesson.student_id = this.student.id;
            self.lesson.title = this.student.first_name + ' ' + this.student.last_name;
            self.lesson.start_date = this.startDate;
            let params = Object.assign({}, self.lesson);
            axios.post('/web/student/lessons', params)
                .then(() => {
                    self.clearLessonData();
                    self.clearErrorData();
                    self.getData();
                    this.disableSaveButton = false;
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'Lesson(s) was saved.',
                        duration: 6000,
                    });
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                    this.disableSaveButton = false;
                    // this.$notify({
                    //     type: 'error',
                    //     title: 'Error',
                    //     text: 'Could not save lesson(s).',
                    //     duration: 6000,
                    // });
                });
        },
    }
}
</script>
