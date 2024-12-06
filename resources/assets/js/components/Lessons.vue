<template>
    <div class="card" v-on:keydown.esc="showModal=false">
        <div v-if="showModal">
            <!-- modal confirm complete -->
            <transition name="modal">
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Complete all past lessons?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" v-on:click="showModal=false">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Complete all past lessons from
                                    <strong>{{ new Date(this.dateStart.fromDate).toDateString() }}</strong> to <strong>{{ new Date(dateEnd.toDate).toDateString() }}?</strong>
                                    <hr/>
                                    <div class="form-group pull-right">
                                        <button v-on:click="showModal=false" class="btn btn-default">Cancel</button>
                                        <button v-on:click="completePast()" class="btn btn-primary">Complete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
            <!-- end of modal complete -->
        </div>

        <!-- vue js data table -->
        <div class="form-control">
            <div class="form-group pull-left m-1">
                <div class="form-group">
                    <select id="single-select" v-model="per_page" class="form-control">
                        <option v-for="page in pages" :value="page">{{ page }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group pull-left m-1">
                <button class="btn btn-default btn-rounded" title="complete lessons" v-on:click="showCompleteModal()">Complete Past Lessons</button>
            </div>
            <div class="form-group pull-right m-1">
                <input type="text" class="form-control" v-model="filter" placeholder="Search" @keydown="$event.stopImmediatePropagation()">
            </div>
            <div class="form-group pull-right m-1">
                <button class="btn btn-link" title="reset calendar" v-on:click="resetDates"><i class="fa fa-calendar" aria-hidden="true"></i></button>
            </div>
            <div class="form-group pull-right form-inline p-1">
                <label for="end date" class="control-label p-1">To</label>
                <v-date-picker v-model="dateEnd.toDate" mode="date">
                    <template v-slot="{ inputValue, inputEvents }">
                        <input
                            class="px-2 py-1 border rounded focus:outline-none focus:border-blue-300 form-control"
                            :value="inputValue"
                            v-on="inputEvents"
                        />
                    </template>
                </v-date-picker>
            </div>
            <div class="form-group pull-right form-inline p-1">
                <label for="start date" class="control-label p-1">From</label>
                <v-date-picker v-model="dateStart.fromDate" mode="date">
                    <template v-slot="{ inputValue, inputEvents }">
                        <input
                            class="px-2 py-1 border rounded focus:outline-none focus:border-blue-300 form-control"
                            :value="inputValue"
                            v-on="inputEvents"
                        />
                    </template>
                </v-date-picker>
            </div>
            <datatable class="table table-responsive-md table-condensed" :columns="columns" :data="list" :filter="filter" :per-page="per_page">
                <template v-slot="{ columns, row }">
                    <tr>
                        <td>
                            <button class="btn btn-rounded btn-outline-secondary" v-if="!row.complete" @click="updateLesson(row.id, row.complete)" :disabled="isCancelled(row)">Click to Complete</button>
                            <button class="btn btn-rounded btn-primary" v-if="row.complete" @click="updateLesson(row.id, row.complete)" :disabled="isCancelled(row)">Completed</button>
                        </td>
                        <td>{{ row.status }}</td>
                        <td v-if="lessonDayStatusPast(row.end_date) && pastLesson"><span class="badge badge-pill badge-danger">Past</span></td>
                        <td v-if="lessonDayStatusToday(row.end_date) && todayLesson"><span class="badge badge-pill badge-primary">Today</span></td>
                        <td v-if="lessonDayStatusUpcoming(row.end_date) && upComing"><span class="badge badge-pill badge-warning">Upcoming</span></td>
                        <td v-text="row.title"></td>
                        <td>{{ new Date(row.start_date).toDateString() }} | {{ row.start_date | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('h:mm') }} - {{ row.end_date | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('h:mm a') }}</td>
                        <td v-text="row.interval"></td>
                    </tr>
                </template>
            </datatable>
            <total-entries v-bind:list="list"></total-entries>
            <div class="pull-right">
                <bootstrap-3-datatable-pager class="pagination" v-model="page" type="abbreviated" :per-page="per_page"></bootstrap-3-datatable-pager>
            </div>
        </div>
        <!-- end of vue js data table -->
        <notifications position="bottom right"/>
    </div>
</template>

<script>
import TotalEntries from "./TotalEntries";
import {dateParse} from "@vuejs-community/vue-filter-date-parse";
import {dateFormat} from "vue-filter-date-format";

let today = new Date();
let firstDay = new Date(today.getFullYear(), today.getMonth(), 1).toDateString();
let lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0).toDateString();

export default {
    data() {
        return {
            dateStart: {
                fromDate: firstDay,
            },
            dateEnd: {
                toDate: lastDay,
            },
            filter: '',
            columns: [
                {label: 'Completed', field: 'complete', sortable: false,},
                {label: 'Status', field: 'status', sortable: false,},
                {label: 'State', field: 'end_date', sortable: false,},
                {label: 'Name', field: 'title', sortable: false,},
                {label: 'Appointment', field: 'start_date', sortable: false,},
                {label: 'Duration', field: 'interval', sortable: false,},
            ],
            list: [],
            todayLesson: false,
            pastLesson: false,
            upComing: false,
            page: 1,
            per_page: 10,
            pages: [10, 25, 50, 100],
            lesson: {
                id: null,
                complete: null,
                title: null,
                start_date: null,
                end_date: null,
                interval: null,
            },
            showModal: false,
        }
    },

    watch: {
        dateStart: {
            handler: function () {
                this.fetchLessonList();
            },
            deep: true,
        },
        dateEnd: {
            handler: function () {
                this.fetchLessonList();
            },
            deep: true,
        },
    },

    mounted() {
        this.fetchLessonList();
    },

    components: {
        TotalEntries
    },

    methods: {
        dateFormat,
        dateParse,
        lessonDayStatusPast: function (endDate) {
            let lessonEndDate = new Date(endDate);
            if (lessonEndDate < today) {
                return this.pastLesson = true;
            }
        },

        lessonDayStatusToday: function (endDate) {
            let lessonEndDate = new Date(endDate);
            if (lessonEndDate === today) {
                return this.todayLesson = true;
            }
        },

        lessonDayStatusUpcoming: function (endDate) {
            let lessonEndDate = new Date(endDate);
            if (lessonEndDate > today) {
                return this.upComing = true;
            }
        },

        fetchLessonList: function () {
            let from = new Date(this.dateStart.fromDate).toDateString();
            let to = new Date(this.dateEnd.toDate).toDateString();
            axios.get('lessons/list/' + from + '/' + to)
                .then((response) => {
                    this.list = response.data.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load lessons list.',
                        duration: 10000,
                    });
                });
        },

        isCancelled: function (row) {
            return row.status === 'Cancelled';
        },

        resetDates: function () {
            this.dateStart.fromDate = firstDay;
            this.dateEnd.toDate = lastDay;
        },

        showCompleteModal: function () {
            this.showModal = true;
        },

        updateLesson: function (id, complete) {
            let self = this;
            self.lesson.id = id;
            self.lesson.complete = !complete;
            let params = Object.assign({}, self.lesson);
            axios.patch('lessons/update/' + id, params)
                .then(() => {
                    self.fetchLessonList();
                })
                .then(() => {
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'The lesson was updated.',
                        duration: 10000,
                    })
                })
                .catch((error) => {
                    self.fetchLessonList();
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'The lesson failed to update.',
                        duration: 10000,
                    })
                });
        },

        completePast: function () {
            let self = this;
            let params = Object.assign({}, this.list);
            axios.put('/lessons/update/past', params)
                .then(() => {
                    this.showModal = false;
                    self.fetchLessonList();
                })
                .then(() => {
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'The past lessons were completed successfully.',
                        duration: 10000,
                    })
                })
                .catch((error) => {
                    this.showModal = false;
                    self.fetchLessonList();
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'The past lessons failed to complete.',
                        duration: 10000,
                    })
                });
        }
    },
}
</script>
