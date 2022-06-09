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
                        <td v-text="row.address"></td>
                        <td v-text="row.address_2"></td>
                        <td v-text="row.city"></td>
                        <td v-text="row.state"></td>
                        <td v-text="row.zip"></td>
                        <td v-text="row.phone"></td>
                        <td v-text="row.email"></td>
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
                {label: 'Address', field: 'address',},
                {label: 'Address 2', field: 'address_2',},
                {label: 'City', field: 'city',},
                {label: 'State', field: 'state',},
                {label: 'Zip', field: 'zip',},
                {label: 'Phone', field: 'phone',},
                {label: 'Email', field: 'email',},
            ],
            teacher: {
                id: null,
                first_name: null,
                last_name: null,
                address: null,
                address_2: null,
                city: null,
                state: null,
                zip: null,
                email: null,
                phone: null,
            },
        }
    },

    mounted: function () {
        this.fetchTeacherList();
    },

    computed: {
        hasListData() {
            return this.list ? this.list.length > 0 : false;
        }
    },

    methods: {
        fetchTeacherList: function () {
            axios.get('/web/teacher')
                .then((response) => {
                    this.list = response.data;
                }).catch((error) => {
                console.log(error);
            });
        },

        // updateTeacher: function (id, complete) {
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
