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
            <div class="pull-left">
                Total: {{ list.length }} entries
            </div>
            <div class="pull-right">
                <bootstrap-3-datatable-pager class="pagination" v-model="page" type="abbreviated" :per-page="per_page"></bootstrap-3-datatable-pager>
            </div>
        </div>
        <!-- end of vue js data table -->
    </div>
</template>

<script>
export default {
    data() {
        return {
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

    computed: {
        hasListData() {
            return this.list ? this.list.length > 0 : false;
        }
    },

    methods: {
        fetchLessonList() {
            axios.get('lessons/list')
                .then((response) => {
                    this.list = response.data.data;
                }).catch((error) => {
                console.log(error);
            });
        },

        updateLesson(id, complete) {
            let self = this;
            self.lesson.id = id;
            self.lesson.complete = !complete;
            let params = Object.assign({}, self.lesson);

            axios.patch('lessons/update/' + id, params)
                .then(function () {
                    self.fetchLessonList();
                })
                .catch(function (error) {
                    self.fetchLessonList();
                    console.log(error);
                });
        },
    },
}
</script>

<style>
@import '/webapp/css/stylesheet.css';
</style>
