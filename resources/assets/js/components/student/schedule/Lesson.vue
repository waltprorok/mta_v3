<template>
    <div class="card">
        <div class="form-control">
            <div class="row pt-2 pb-2">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row ">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Name</h6>
                                </div>
                                <div class="col-sm-9">
                                    {{ student.first_name }} {{ student.last_name }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Email</h6>
                                </div>
                                <div class="col-sm-9">
                                    <a :href="'mailto:' + student.email">{{ student.email }}</a>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Phone</h6>
                                </div>
                                <div class="col-sm-9">
                                    <phone-number-format v-bind:data="student"></phone-number-format>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Scheduled</h6>
                                </div>
                                <div class="col-sm-9" v-if="studentScheduled">
                                        <span class="btn btn-success">
                                            <i class="fa fa-check"></i>&nbsp;Has Appointment
                                        </span>
                                </div>
                                <div class="col-sm-9" v-else>
                                        <span class="btn btn-danger">
                                            <i class="fa fa-times"></i>&nbsp;Needs Appointment
                                        </span>
                                </div>
                            </div>
                            <hr>
                            <div class="row" v-if="student.has_one_lesson">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Last Lesson</h6>
                                </div>
                                <div class="col-sm-9">
                                    <i class="fa fa-clock-o"></i>
                                    &nbsp;{{ student.has_one_lesson.start_date | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY h:mm a') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form action="#" @submit.prevent="saveLessons()">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" :class="error_start_date && classError">
                            <label for="start_date" class="control-label">Start Date</label>
                            <v-date-picker v-model="startDate" mode="date" :disabled-dates='[{ weekdays: closedDay }, {
      start: new Date(2024, 10, 18),
      end: new Date(2024, 10, 22)
    },  ]'
                                           :model-config="modelConfig">
                                <template v-slot="{ inputValue, inputEvents }">
                                    <input
                                        class="px-2 py-1 border rounded focus:outline-none focus:border-blue-300 form-control"
                                        :value="inputValue"
                                        v-on="inputEvents"
                                    />
                                </template>
                            </v-date-picker>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="error_start_time && classError">
                            <label for="start_time" class="control-label">Start Time</label>
                            <select class="form-control" id="start_time" v-model="lesson.start_time" @change="checkDurationTimes($event)" v-on:keydown.enter.prevent>
                                <option>-- Select Time --</option>
                                <option v-for="time in allTimes" :value="time" :key="time.id" :selected="time.id === 1">
                                    {{ time | dateParse('HH:mm:ss') | dateFormat('h:mm a') }}
                                </option>
                            </select>
                            <small>{{ error_start_time }}</small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" :class="error_end_time && classError">
                            <label for="end_time" class="control-label">Duration</label>
                            <select class="form-control" id="end_time" v-model="lesson.end_time" v-on:keydown.enter.prevent>
                                <option v-for="interval in duration" :value="interval.value" :key="interval.id">
                                    {{ interval.name }}
                                </option>

                            </select>
                            <small>{{ error_end_time }}</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group" :class="error_recurrence && classError">
                            <label for="end_time" class="control-label">Recurrence</label>
                            <select class="form-control" id="recurrence" v-model="lesson.recurrence" v-on:keydown.enter.prevent>
                                <option value="Monthly">Monthly</option>
                                <option value="Once">One Time</option>
                            </select>
                            <small>{{ error_recurrence }}</small>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group" :class="error_billing_rate_id && classError">
                            <label for="billing_rate" class="control-label">Billing Rate</label>
                            <select class="form-control" id="billing_rate" v-model="lesson.billing_rate_id" v-on:keydown.enter.prevent>
                                <option v-for="rate in billingRates" :value="rate.id">
                                    {{ rate.amount | toCurrency }} | {{ rate.type | capitalising }} - {{ rate.description | capitalising }}
                                </option>
                            </select>
                            <small>{{ error_billing_rate_id }}</small>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group" :class="error_color && classError">
                            <label for="color" class="control-label">Color</label>
                            <select class="form-control" id="color" v-model="lesson.color" v-on:keydown.enter.prevent>
                                <option v-for="color in colors" :value="color.code" :key="color.id">
                                    {{ color.name }}
                                </option>
                            </select>
                            <small>{{ error_color }}</small>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="pull-left">
                    <button v-show="allTimes" type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-outline-secondary" @click="cancelLessonForm">Cancel</button>
                </div>
            </form>
        </div>
        <notifications position="bottom right"/>
    </div>
</template>

<script>
import PhoneNumberFormat from "../../PhoneNumberFormat";

let today = new Date();
let startDate = today.toISOString().slice(0, 10); // TODO: fix timezone issue

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
            closedDay: [],
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
            holidays: [],
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
                // this.checkStartTimeWithHolidays();
            },
            deep: true,
        },
    },

    mounted: function () {
        this.getData();
    },

    methods: {
        getTimeDifference: function getTimeDifference(startTime, endTime) {
            // Convert both times to Date objects
            const start = new Date('1970-01-01T' + startTime);
            const end = new Date('1970-01-01T' + endTime);
            // Calculate the difference in milliseconds
            const diff = end.getTime() - start.getTime();
            // Convert to hours, minutes, and seconds
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            // const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            return {hours, minutes};
        },

        getDuration: function (diff) {
           let totalTime = diff.hours + '.' + diff.minutes;
            console.log(totalTime);
            switch (totalTime) {
                case '0.15':
                    this.duration = [
                        {name: '15 minutes', value: "15"},
                        {name: '30 minutes', value: "30"},
                        // {name: '45 minutes', value: "45"},
                        // {name: '60 minutes', value: "60"},

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
                    ];
                    break;
                default:
                    this.duration = [
                        // {name: '15 minutes', value: "15"},
                    ];
            }
        },

        checkDurationTimes: function (event) {
            const allTimesArray = [];
            for (const key in this.allTimes) {
                allTimesArray.push(this.allTimes[key]);
            }
            let index = allTimesArray.indexOf(event.target.value);
            let nextTime = allTimesArray[index + 1];
            const diff = this.getTimeDifference(event.target.value, nextTime);
            this.getDuration(diff);
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
            console.log(startDate);
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
                    this.closedDay.push(row.day + 1);
                }
            });
            // this.holidays.forEach((day) => {
            //     this.
            // })
        },

        checkStartTimeWithHolidays: function () {
            this.holidays.forEach((day) => {
                // console.log(day);
                // console.log(this.lesson.start_date);
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
            let self = this;
            self.lesson.student_id = this.student.id;
            self.lesson.title = this.student.first_name + ' ' + this.student.last_name;
            self.lesson.start_date = this.startDate;
            console.log(self.lesson);
            let params = Object.assign({}, self.lesson);
            axios.post('/web/student/lessons', params)
                .then(() => {
                    self.clearLessonData();
                    self.clearErrorData();
                    self.getData();
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'Lesson(s) was saved.',
                        duration: 10000,
                    });
                })
                .catch((error) => {
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
