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
                        <td v-text="row.first_name"></td>
                        <td v-text="row.last_name"></td>
                        <td v-text="row.email"></td>
                        <td v-html="getUserType(row.admin)"></td>
                        <td v-html="getUserType(row.teacher)"></td>
                        <td v-html="getUserType(row.student)"></td>
                        <td v-html="getUserType(row.parent)"></td>
                        <td>{{ row.created_at | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY hh:mm a') }}</td>
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
    data: function () {
        return {
            filter: '',
            list: [],
            page: 1,
            per_page: 10,
            columns: [
                {label: 'First Name', field: 'first_name',},
                {label: 'Last Name', field: 'last_name',},
                {label: 'Email', field: 'email',},
                {label: 'Admin', field: 'admin',},
                {label: 'Teacher', field: 'teacher',},
                {label: 'Student', field: 'student',},
                {label: 'Parent', field: 'parent',},
                {label: 'Created At', field: 'created_at',},

            ],
            user: {
                id: null,
                first_name: null,
                last_name: null,
                email: null,
                admin: null,
                teacher: null,
                student: null,
                parent: null,
                terms: null,
            },
        }
    },

    mounted: function () {
        this.fetchUserList();
    },

    computed: {
        hasListData: function () {
            return this.list ? this.list.length > 0 : false;
        }
    },

    methods: {
        fetchUserList: function () {
            axios.get('/web/user')
                .then((response) => {
                    this.list = response.data;
                }).catch((error) => {
                console.log(error);
            });
        },

        getUserType: function (row) {
            if (row) {
                return "&#10003;";
            } else {
                return '';
            }
        }

        // updateUser: function (id, complete) {
        //     let self = this;
        //     self.lesson.id = id;
        //     self.lesson.complete = !complete;
        //     let params = Object.assign({}, self.lesson);
        //
        //     axios.patch('lessons/update/' + id, params)
        //         .then(function () {
        //             self.fetchLessonList();
        //         })
        //         .catch(function (error) {
        //             self.fetchLessonList();
        //             console.log(error);
        //         });
        // },
    },
}
</script>

<style>
@import '/webapp/css/stylesheet.css';
</style>
