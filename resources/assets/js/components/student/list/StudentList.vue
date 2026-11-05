<template src="./student-list-template.html"></template>

<script>
import TotalEntries from "../../TotalEntries";
import PhoneNumberFormat from "../../PhoneNumberFormat";

export default {
    name: 'StudentList',
    data: function () {
        return {
            class_error: '',
            filter: '',
            columns: [
                {label: 'Scheduled', field: 'has_one_lesson', sortable: false},
                {label: 'First Name', field: 'first_name',},
                {label: 'Last Name', field: 'last_name',},
                {label: 'Phone', field: 'phone', sortable: false},
                {label: 'Email', field: 'email', sortable: false},
                {label: 'Instrument', field: 'instrument', sortable: false},
                {label: 'Action', filterable: false}
            ],
            show_form: false,
            show_parent: false,
            list: [],
            studentStats: {
                active: 0,
                notScheduled: 0,
                waitlist: 0,
                leads: 0,
            },
            page: 1,
            per_page: 10,
            pages: [10, 25, 50, 100],
            saving: false,
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
        this.fetchStudentStats();
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

        async createStudent() {
            if (this.saving) {
                return;
            }

            this.saving = true;
            this.clearErrorData();

            try {
                const params = Object.assign({}, this.student);

                await axios.post('/web/student-save', params);

                this.$notify({
                    type: 'success',
                    title: 'Success',
                    text: 'The student was added.',
                    duration: 6000,
                });

                this.clearStudentData();
                this.clearErrorData();
                await this.fetchStudentList();
                await this.fetchStudentStats();
            } catch (error) {
                if (error.response?.data?.error) {
                    this.getErrorMessage(error);
                } else {
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Unable to add student. Please try again.',
                        duration: 6000,
                    });
                }
            } finally {
                this.saving = false;
            }
        },

        getErrorMessage: function (error) {
            const errors = error.response?.data?.error
                || error.response?.data?.errors
                || {};

            const message = (field) => {
                const value = errors[field];

                return Array.isArray(value)
                    ? value[0] || ''
                    : value || '';
            };

            this.error_first_name = message('first_name');
            this.error_last_name = message('last_name');
            this.error_phone = message('phone');
            this.error_email = message('email');
            this.error_parent_first_name = message('parent_first_name');
            this.error_parent_last_name = message('parent_last_name');
            this.error_parent_email = message('parent_email');
            this.error_status = message('status');

            this.class_error = 'has-error';
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
                        duration: 6000,
                    });
                });
        },

        fetchStudentList: async function () {
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
                        duration: 6000,
                    });
                });
        },

        fetchStudentStats: async function () {
            try {
                const [active, waitlist, leads] = await Promise.all([
                    axios.get('/web/active'),
                    axios.get('/web/waitlist'),
                    axios.get('/web/leads'),
                ]);

                this.studentStats.active = active.data.length;
                this.studentStats.waitlist = waitlist.data.length;
                this.studentStats.leads = leads.data.length;

                this.studentStats.notScheduled = active.data.filter((student) => {
                    return student.has_one_future_lesson === null;
                }).length;

            } catch (error) {
                console.error('Unable to load student statistics:', error);
            }
        },

        showParent: function () {
            this.show_parent = ! this.show_parent;
            if (this.show_parent === false) {
                this.student.parent_first_name = null;
                this.student.parent_last_name = null;
                this.student.parent_email = null;
            }
        }
    },
}
</script>
