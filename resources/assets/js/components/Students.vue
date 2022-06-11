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
                        <td v-text="row.phone"></td>
                        <td v-text="row.email"></td>
                        <td v-text="row.instrument"></td>
                        <td v-html="getStatus(row)"></td>
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
                {label: 'Phone', field: 'phone',},
                {label: 'Email', field: 'email',},
                {label: 'Instrument', field: 'instrument',},
                {label: 'Status', field: 'status',},


            ],
            student: {
                id: null,
                first_name: null,
                last_name: null,
                phone: null,
                email: null,
                instrument: null,
                status: null,
            },
        }
    },

    mounted: function () {
        this.fetchStudentList();
    },

    computed: {
        hasListData() {
            return this.list ? this.list.length > 0 : false;
        },
    },

    methods: {
        fetchStudentList: function() {
            axios.get('/web/student')
                .then((response) => {
                    this.list = response.data;
                }).catch((error) => {
                console.log(error);
            });
        },

        getStatus: function(row) {
            switch (parseInt(row.status)) {
                case 1:
                    return 'Active';
                case 2:
                    return 'Waitlist';
                case 3:
                    return 'Lead';
                case 4:
                    return 'Active';
                default:
                    return 'this';
            }
        }

        // updateTeacher (id, complete) {
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
