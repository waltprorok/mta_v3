<!--suppress JSUnresolvedReference -->
<template>
    <div class="card">
        <!-- vue js data table -->
        <div class="form-control">
            <div class="form-group pull-left">
                <div class="form-group">
                    <select id="single-select" v-model="per_page" class="form-control">
                        <option v-for="page in pages" :value="page">{{ page }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group pull-right">
                <input type="text" class="form-control" v-model="filter" placeholder="Search" @keydown="$event.stopImmediatePropagation()">
            </div>
            <div class="form-group pull-right form-inline p-1">
                <label for="end date" class="control-label p-1">To</label>
                <v-date-picker v-model="dateEnd.date" mode="date">
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
                <v-date-picker v-model="dateStart.date" mode="date">
                    <template v-slot="{ inputValue, inputEvents }">
                        <input
                            class="px-2 py-1 border rounded focus:outline-none focus:border-blue-300 form-control"
                            :value="inputValue"
                            v-on="inputEvents"
                        />
                    </template>
                </v-date-picker>
            </div>
            <datatable class="table table-responsive-md" :columns="columns" :data="list" :filter="filter" :per-page="per_page">
                <template v-slot="{ columns, row }">
                    <tr>
                        <td>
                            <button class="btn btn-default btn-rounded" v-if="!row.complete" @click="updateLesson(row.id, row.complete)">Click to Complete</button>
                            <button class="btn btn-success btn-rounded" v-if="row.complete" @click="updateLesson(row.id, row.complete)">Completed</button>
                        </td>
                        <td v-text="row.title"></td>
                        <td>{{ row.start_date | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY hh:mm a') }}</td>
                        <td>{{ row.end_date | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY hh:mm a') }}</td>
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
let firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
let lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);

export default {

    data() {
        return {
            // range: {
            //     start: new Date(),
            //     end: new Date()
            // },
            dateStart: {
                date: firstDay.toString(),
            },
            dateEnd: {
                date: lastDay.toString(),
            },
            filter: '',
            columns: [
                {label: 'Completed', field: 'complete',},
                {label: 'Name', field: 'title',},
                {label: 'Start Date', field: 'start_date',},
                {label: 'End Date', field: 'end_date',},
                {label: 'Duration', field: 'interval',},
            ],
            list: [],
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
        }
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
        fetchLessonList: function () {
            axios.get('lessons/list')
                .then((response) => {
                    this.list = response.data.data;
                }).catch((error) => {
                console.log(error);
                this.$notify({
                    type: 'error',
                    title: 'Error',
                    text: 'Could not load lessons list.',
                    duration: 10000,
                });
            });
        },

        updateLesson: function (id, complete) {
            let self = this;
            self.lesson.id = id;
            self.lesson.complete = !complete;
            console.log(self.dateStart);
            console.log(self.dateEnd);
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
    },
}
</script>
