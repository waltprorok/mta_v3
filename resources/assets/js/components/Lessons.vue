<template>
    <div class="card">
        <!-- vue js data table -->
        <div class="form-control">
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
            <div class="pull-right">
                <bootstrap-3-datatable-pager class="pagination" v-model="page" type="long" :per-page="per_page"></bootstrap-3-datatable-pager>
            </div>
        </div>
        <!-- end of vue js data table -->
    </div>
</template>

<script>
export default {
    data: function () {
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

    mounted: function () {
        this.fetchLessonList();
    },

    computed: {
        hasListData() {
            return this.list ? this.list.length > 0 : false;
        }
    },

    methods: {
        fetchLessonList: function () {
            axios.get('lessons/list')
                .then((response) => {
                    this.list = response.data.data;
                }).catch((error) => {
                console.log(error);
            });
        },

        updateLesson: function (id, complete) {
            let self = this;
            self.lesson.id = id;
            self.lesson.complete = !complete;
            let params = Object.assign({}, self.lesson);

            axios.patch('lessons/update/' + id, params)
                .then(function (response) {
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
table thead tr th {
    text-align: left !important;
}

.pagination {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    padding-left: 0;
    list-style: none
}

.pagination ul li {
    position: relative;
    display: block;
    padding: .5rem .75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #777;
    background-color: #fff;
    border: 1px solid #dee2e6
}

.pagination ul li:hover {
    color: #515151;
    text-decoration: none;
    background-color: #e9ecef;
    border-color: #dee2e6
}

.pagination ul li:focus {
    z-index: 2;
    outline: 0;
    -webkit-box-shadow: none;
    box-shadow: none
}

.pagination ul li:not(:disabled):not(.disabled) {
    cursor: pointer
}

.pagination ul li:first-child {
    margin-left: 0
}

.pagination ul li.active {
    z-index: 1;
    color: #fff;
    background-color: #42a5f5;
    border-color: #42a5f5
}

.pagination ul li.disabled {
    color: #999;
    pointer-events: none;
    cursor: auto;
    background-color: #fff;
    border-color: #dee2e6
}
</style>
