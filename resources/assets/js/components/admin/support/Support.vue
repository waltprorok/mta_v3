<template src="./support-template.html"></template>

<script>
import TotalEntries from "../../TotalEntries";
import {dateParse} from "@vuejs-community/vue-filter-date-parse";
import {dateFormat} from "vue-filter-date-format";

export default {
    data: function () {
        return {
            classError: '',
            filter: '',
            columns: [
                {label: 'Replied', field: 'reply', sortable: false},
                {label: 'Name', field: 'name', sortable: false},
                {label: 'Email', field: 'email', sortable: false},
                {label: 'Subject', field: 'subject', sortable: false},
                {label: 'Message', field: 'message', sortable: false},
                {label: 'Created', field: 'created_at', sortable: false},
                {label: 'Action', filterable: false}
            ],
            edit: false,
            showForm: false,
            read: false,
            showModal: false,
            list: [],
            page: 1,
            per_page: 10,
            pages: [10, 25, 50, 100],
            support: {
                id: null,
                name: null,
                email: null,
                subject: null,
                message: null,
                reply: false,
                created_at: null,
            },
            error_name: '',
            error_email: '',
            error_subject: '',
            error_message: '',
        }
    },

    mounted: function () {
        this.fetchSupportList();
    },

    components: {
        TotalEntries
    },

    methods: {
        dateFormat,
        dateParse,
        cancelForm: function () {
            let self = this;
            self.showForm = false;
            self.read = false;
            self.support.name = null;
            self.support.email = null;
            self.support.subject = null;
            self.support.message = null;
            self.edit = false;
            self.clearErrorData();
        },

        showModalDelete: function (id) {
            let self = this;
            self.showModal = true;
            self.id = id;
        },

        clearErrorData: function () {
            let self = this;
            self.classError = '';
            self.error_name = '';
            self.error_email = '';
            self.error_subject = '';
            self.error_message = '';
        },

        clearSupportData: function () {
            let self = this;
            self.support.name = null;
            self.support.email = null;
            self.support.subject = null;
            self.support.message = null;
            self.edit = false;
            self.showForm = false;
        },

        getErrorMessage: function (error) {
            let self = this;
            self.error_name = error.response.data.error.name;
            self.error_email = error.response.data.error.email;
            self.error_subject = error.response.data.error.subject;
            self.error_message = error.response.data.error.message;
            self.classError = 'has-error';
        },

        fetchSupportList: function () {
            axios.get('/web/support')
                .then((response) => {
                    this.list = response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load support.',
                        duration: 10000,
                    });
                });
        },

        createSupport: function () {
            let self = this;
            let params = Object.assign({}, self.support);
            axios.post('/web/support', params)
                .then(() => {
                    self.clearSupportData()
                    self.clearErrorData();
                    self.fetchSupportList();
                })
                .then(() => {
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'The support was created.',
                        duration: 10000,
                    })
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                });
        },

        showSupport: function (id, read) {
            let self = this;
            self.showForm = true;
            self.read = read;
            axios.get('/web/support/' + id)
                .then((response) => {
                    self.support.id = response.data.id;
                    self.support.name = response.data.name;
                    self.support.email = response.data.email;
                    self.support.subject = response.data.subject;
                    self.support.message = response.data.message;
                    self.support.reply = response.data.reply;
                })
            self.edit = true;
        },

        updateSupport: function (id) {
            let self = this;
            let params = Object.assign({}, self.support);
            axios.patch('/web/support/' + id, params)
                .then(() => {
                    self.clearSupportData();
                    self.clearErrorData();
                    self.fetchSupportList();
                })
                .then(() => {
                    this.$notify({
                        type: 'warn',
                        title: 'Updated',
                        text: 'The support record was updated.',
                        duration: 10000,
                    })
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not update support record.',
                        duration: 10000,
                    });
                });
        },

        updateReply: function (id) {
            let self = this;
            self.support.id = id;
            self.support.reply = true;
            let params = Object.assign({}, self.support);
            axios.patch('/web/reply-support/' + id, params)
                .then(() => {
                    self.fetchSupportList();
                })
                .then(() => {
                    this.$notify({
                        type: 'success',
                        title: 'Updated',
                        text: 'The support reply was updated.',
                        duration: 10000,
                    })
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not update support record.',
                        duration: 10000,
                    });
                });
        },

        deleteSupport: function (id) {
            let self = this;
            let params = Object.assign({}, self.support);
            axios.delete('/web/support/' + id, params)
                .then(() => {
                    self.showModal = false;
                    self.fetchSupportList();
                })
                .then(() => {
                    this.$notify({
                        type: 'warn',
                        title: 'Deleted',
                        text: 'The support was deleted.',
                        duration: 10000,
                    })
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not delete support.',
                        duration: 10000,
                    });
                });
        },
    },
}
</script>
