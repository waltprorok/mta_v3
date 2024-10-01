<template src="./student-list-template.html"></template>

<script>
import TotalEntries from "../TotalEntries";
import PhoneNumberFormat from "../PhoneNumberFormat";

export default {
    data: function () {
        return {
            class_error: '',
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
            show_form: false,
            show_parent: false,
            list: [],
            page: 1,
            per_page: 10,
            pages: [10, 25, 50, 100],
            student: {
                id: null,
                has_one_lesson: null,
                first_name: null,
                last_name: null,
                email: null,
                phone: null,
                instrument: null,
                status: 1,
                add_parent: false,
                parent_first_name: null,
                parent_last_name: null,
                parent_email: null,
            },
            error_first_name: '',
            error_last_name: '',
            error_email: '',
            error_parent_first_name: '',
            error_parent_last_name: '',
            error_parent_email: '',
            error_phone: '',
            error_status: '',
        }
    },

    components: {
        PhoneNumberFormat,
        TotalEntries
    },

    mounted: function () {
        this.fetchStudentList();
    },

    methods: {
        hasOneLesson: function (row) {
            return row.has_one_future_lesson !== null && row.status === 1;
        },

        cancelForm: function () {
            let self = this;
            self.student.first_name = null;
            self.student.last_name = null;
            self.student.email = null;
            self.student.phone = null;
            self.student.status = 1;
            self.show_form = false;
            self.student.add_parent = false;
            self.show_parent = false;
            self.student.parent_first_name = null;
            self.student.parent_last_name = null;
            self.student.parent_email = null;
            self.clearErrorData();
        },

        clearErrorData: function () {
            let self = this;
            self.class_error = '';
            self.error_first_name = '';
            self.error_last_name = '';
            self.error_email = '';
            self.error_phone = '';
            self.error_parent_first_name = '';
            self.error_parent_last_name = '';
            self.error_parent_email = '';
            self.error_status = '';
        },

        clearStudentData: function () {
            let self = this;
            self.student.first_name = null;
            self.student.last_name = null;
            self.student.email = null;
            self.student.phone = null;
            self.student.status = 1;
            self.student.add_parent = false;
            self.show_parent = false;
            self.student.parent_first_name = null;
            self.student.parent_last_name = null;
            self.student.parent_email = null;
            self.show_form = false;
        },

        createStudent: function () {
            let self = this;
            let params = Object.assign({}, self.student);
            axios.post('/web/student-save', params)
                .then(() => {
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'The student was added.',
                        duration: 10000,
                    });
                })
                .then(() => {
                    self.clearStudentData()
                    self.clearErrorData();
                    self.fetchStudentList();
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                });
        },

        getErrorMessage: function (error) {
            let self = this;
            self.error_first_name = error.response.data.error.first_name;
            self.error_last_name = error.response.data.error.last_name;
            self.error_phone = error.response.data.error.phone;
            self.error_email = error.response.data.error.email;
            self.error_parent_first_name = error.response.data.error.parent_first_name;
            self.error_parent_last_name = error.response.data.error.parent_last_name;
            self.error_parent_email = error.response.data.error.parent_email;
            self.class_error = 'has-error';
        },

        getStatusData: function (row) {
            return row.status === 2 || row.status === 3 || row.status === 4;
        },

        getOnChangeList: function (event) {
            axios.get('/web/' + event.target.value)
                .then((response) => {
                    this.list = response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load student list.',
                        duration: 10000,
                    });
                });
        },

        fetchStudentList: function () {
            axios.get('/web/active')
                .then((response) => {
                    this.list = response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load student list.',
                        duration: 10000,
                    });
                });
        },

        showParent: function () {
            this.show_parent = !this.show_parent;
            if (this.show_parent === false) {
                this.student.parent_first_name = null;
                this.student.parent_last_name = null;
                this.student.parent_email = null;
            }
        }
    },
}
</script>
