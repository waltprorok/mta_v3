<template src="./contact-template.html"></template>

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
                {label: 'Name', field: 'name', sortable: false},
                {label: 'Email', field: 'email', sortable: false},
                {label: 'Subject', field: 'subject', sortable: false},
                {label: 'Message', field: 'message', sortable: false},
                {label: 'Created', field: 'created_at', sortable: false},
                {label: 'Actions', filterable: false}
            ],
            edit: false,
            showForm: false,
            read: false,
            showModal: false,
            list: [],
            page: 1,
            per_page: 10,
            pages: [10, 25, 50, 100],
            contact: {
                id: null,
                name: null,
                email: null,
                subject: null,
                message: null,
                created_at: null,
            },
            error_name: '',
            error_email: '',
            error_subject: '',
            error_message: '',
        }
    },

    mounted: function () {
        this.fetchContactList();
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
            self.contact.name = null;
            self.contact.email = null;
            self.contact.subject = null;
            self.contact.message = null;
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

        clearContactData: function () {
            let self = this;
            self.contact.name = null;
            self.contact.email = null;
            self.contact.subject = null;
            self.contact.message = null;
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

        fetchContactList: function () {
            axios.get('/web/contacts')
                .then((response) => {
                    this.list = response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load contacts.',
                        duration: 10000,
                    });
                });
        },

        createContact: function () {
            let self = this;
            let params = Object.assign({}, self.contact);
            axios.post('/web/contacts', params)
                .then(() => {
                    self.clearContactData()
                    self.clearErrorData();
                    self.fetchContactList();
                })
                .then(() => {
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'The contact was created.',
                        duration: 10000,
                    })
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                });
        },

        showContact: function (id, read) {
            let self = this;
            self.showForm = true;
            self.read = read;
            axios.get('/web/contacts/' + id)
                .then((response) => {
                    self.contact.id = response.data.id;
                    self.contact.name = response.data.name;
                    self.contact.email = response.data.email;
                    self.contact.subject = response.data.subject;
                    self.contact.message = response.data.message;
                })
            self.edit = true;
        },

        updateContact: function (id) {
            let self = this;
            let params = Object.assign({}, self.contact);
            axios.patch('/web/contacts/' + id, params)
                .then(() => {
                    self.clearContactData();
                    self.clearErrorData();
                    self.fetchContactList();
                })
                .then(() => {
                    this.$notify({
                        type: 'warn',
                        title: 'Updated',
                        text: 'The contact record was updated.',
                        duration: 10000,
                    })
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not update contact record.',
                        duration: 10000,
                    });
                });
        },

        deleteContact: function (id) {
            let self = this;
            let params = Object.assign({}, self.contact);
            axios.delete('/web/contacts/' + id, params)
                .then(() => {
                    self.showModal = false;
                    self.fetchContactList();
                })
                .then(() => {
                    this.$notify({
                        type: 'warn',
                        title: 'Deleted',
                        text: 'The contact was deleted.',
                        duration: 10000,
                    })
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not delete contact.',
                        duration: 10000,
                    });
                });
        },
    },
}
</script>
