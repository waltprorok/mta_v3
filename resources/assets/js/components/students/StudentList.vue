<template>
    <div>
        <div v-if="alert" class="alert alert-success alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" @click="alert=false" aria-label="close">&times;</a>
            {{ toast.success }}
        </div>
        <div class="card">
            <div v-if="showForm">
                <transition name="modal">
                    <div class="modal-mask">
                        <div class="modal-wrapper">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Student</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" @click="cancelForm()">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <form action="#" @submit.prevent="createStudent()">
                                            <div class="form-group" :class="error_first_name && classError">
                                                <label for="first_name">First Name <span class="text-danger">*</span></label>
                                                <input id="first_name" v-model.trim="student.first_name" type="text" class="form-control">
                                                <small>{{ error_first_name }}</small>
                                            </div>
                                            <div class="form-group" :class="error_last_name && classError">
                                                <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                                <input id="last_name" v-model.trim="student.last_name" type="text" class="form-control">
                                                <small>{{ error_last_name }}</small>
                                            </div>
                                            <div class="form-group" :class="error_phone && classError">
                                                <label for="phone">Phone <span class="text-danger">*</span></label>
                                                <input id="phone" v-model.trim="student.phone" type="text" class="form-control">
                                                <small>{{ error_phone }}</small>
                                            </div>
                                            <div class="form-group" :class="error_email && classError">
                                                <label for="email">Email
                                                    <span class="text-danger">*</span>
                                                    <small><em>Use parent email address if student does not have one.</em></small>
                                                </label>
                                                <input id="email" v-model.trim="student.email" type="text" class="form-control">
                                                <small>{{ error_email }}</small>
                                            </div>
                                            <div class="form-group" :class="error_status && classError">
                                                <label for="status">Status: </label>
                                                <div class="form-check-inline">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" :checked="student.status === 1" v-model="student.status" :value="1">Active
                                                    </label>
                                                </div>
                                                <div class="form-check-inline">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" v-model="student.status" :value="3">Lead
                                                    </label>
                                                </div>
                                                <div class="form-check-inline">
                                                    <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" v-model="student.status" :value="2">Waitlist
                                                    </label>
                                                </div>
                                                <small>{{ error_status }}</small>
                                                <hr/>
                                                <div class="form-group pull-right">
                                                    <button v-show="showForm" @click="cancelForm()" class="btn btn-default">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>

            <!-- vue js data table -->
            <div class="form-control">
                <div class="form-group pull-left">
                    <div class="form-group">
                        <select id="single-select" v-model="per_page" class="form-control">
                            <option v-for="page in pages" :value="page">{{ page }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group pull-left input-group mb-3 col-2">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                    <div class="input-group-prepend"></div>
                    <select id=students class="form-control" @change="getOnChangeList($event)">
                        <option value="student-index" selected>Active</option>
                        <option value="leads">Leads</option>
                        <option value="waitlist">Wait List</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="form-group pull-right">
                    <button type="button" class="btn btn-primary" @click="showForm=true">Add Student</button>
                </div>
                <div class="form-group pull-right pr-2">
                    <input type="text" class="form-control" v-model="filter" placeholder="Search" @keydown="$event.stopImmediatePropagation()">
                </div>
                <datatable class="table table-responsive-md" :columns="columns" :data="list" :filter="filter" :per-page="per_page">
                    <template v-slot="{ columns, row }">
                        <tr>
                            <td v-show="row.has_one_lesson === null && row.status === 1">
                                <span class="btn btn-sm btn-danger btn-rounded"><i class="fa fa-times"></i></span>
                            </td>
                            <td v-show="row.has_one_lesson !== null && row.status === 1">
                                <span class="btn btn-sm btn-success btn-rounded"><i class="fa fa-check"></i></span>
                            </td>
                            <td v-show="getStatusData(row)">
                                <span class="btn btn-sm btn-default btn-rounded"><i class="fa fa-calendar-minus-o"></i></span>
                            </td>
                            <td v-text="row.first_name"></td>
                            <td v-text="row.last_name"></td>
                            <td v-html="formatPhoneNumber(row)"></td>
                            <td><a :href="'mailto:' + row.email" v-text="row.email"></a></td>
                            <td v-text="row.instrument"></td>
                            <td class="text-nowrap">
                                <a :href="`/students/${row.id}/edit`" class="btn btn-sm btn-outline-primary" role="button" title="edit"><i class="fa fa-edit"></i></a>
                                <a :href="`/students/${row.id}/schedule`" class="btn btn-sm btn-outline-secondary" role="button" title="schedule"><i class="fa fa-calendar"></i></a>
                                <a :href="`/students/${row.id}/profile`" class="btn btn-sm btn-outline-success" role="button" title="profile"><i class="fa fa-user"></i></a>
                            </td>
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
        </div>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            toast: '',
            alert: false,
            classError: '',
            filter: '',
            columns: [
                {label: 'Scheduled', field: 'has_one_lesson',},
                {label: 'First Name', field: 'first_name',},
                {label: 'Last Name', field: 'last_name',},
                {label: 'Phone', field: 'phone', sortable: false},
                {label: 'Email', field: 'email',},
                {label: 'Instrument', field: 'instrument',},
                {label: 'Actions', filterable: false}
            ],
            showForm: false,
            showModal: false,
            list: [],
            page: 1,
            per_page: 10,
            pages: [10, 25, 50, 100],
            student: {
                id: null,
                has_one_lesson: null,
                first_name: null,
                last_name: null,
                phone: null,
                email: null,
                instrument: null,
                status: 1,
            },
            error_first_name: '',
            error_last_name: '',
            error_phone: '',
            error_email: '',
            error_status: '',
        }
    },

    mounted: function () {
        this.fetchStudentList();
    },

    computed: {
        // getStatusData: function (row) {
        //     return row.status === 2 || row.status === 3 || row.status === 4;
        // }
    },

    methods: {
        cancelForm: function () {
            let self = this;
            self.student.first_name = null;
            self.student.last_name = null;
            self.student.phone = null;
            self.student.email = null;
            self.showForm = false;
            self.clearErrorData();
        },

        clearErrorData: function () {
            let self = this;
            self.classError = '';
            self.error_first_name = '';
            self.error_last_name = '';
            self.error_phone = '';
            self.error_email = '';
        },

        clearStudentData: function () {
            let self = this;
            self.student.first_name = null;
            self.student.last_name = null;
            self.student.phone = null;
            self.student.email = null;
            self.showForm = false;
        },

        createStudent: function () {
            let self = this;
            let params = Object.assign({}, self.student);
            axios.post('/web/student-save', params)
                .then(function (success) {
                    self.alert = true;
                    self.toast = success.data;
                    self.clearStudentData()
                    self.clearErrorData();
                    self.fetchStudentList();
                })
                .catch(function (error) {
                    self.getErrorMessage(error);
                });
        },

        formatPhoneNumber: function (row) {
            if (row.phone && row.phone.length === 10) {
                return row.phone.replace(/[^0-9]/g, '').replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
            } else if (row.phone && row.phone.length === 11) {
                return row.phone.replace(/[^0-9]/g, '').replace(/(\d{1})(\d{3})(\d{3})(\d{4})/, '$1-$2-$3-$4');
            } else {
                return row.phone;
            }
        },

        getErrorMessage: function (error) {
            let self = this;
            self.error_first_name = error.response.data.error.first_name;
            self.error_last_name = error.response.data.error.last_name;
            self.error_phone = error.response.data.error.phone;
            self.error_email = error.response.data.error.email;
            self.classError = 'has-error';
        },

        getStatusData: function (row) {
            return row.status === 2 || row.status === 3 || row.status === 4;
        },

        getOnChangeList: function (event) {
            axios.get('/web/' + event.target.value)
                .then((response) => {
                    this.list = response.data;
                }).catch((error) => {
                console.log(error);
            });
        },

        fetchStudentList: function () {
            axios.get('/web/student-index')
                .then((response) => {
                    this.list = response.data;
                }).catch((error) => {
                console.log(error);
            });
        },
    },
}
</script>

<style>
@import '/webapp/css/stylesheet.css';
</style>
