<template src="./contact-template.html"></template>

<script>
import TotalEntries from "../../TotalEntries";

export default {
    data: function () {
        return {
            classError: '',
            filter: '',
            columns: [
                {label: 'Name', field: 'name',},
                {label: 'Email', field: 'email',},
                {label: 'Subject', field: 'subject',},
                {label: 'Message', field: 'message',},
                {label: 'Created', field: 'created_at',},
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
            axios.get('/web/contact')
                .then((response) => {
                    this.list = response.data.data;
                }).catch((error) => {
                console.log(error);
                this.$notify({
                    type: 'error',
                    title: 'Error',
                    text: 'Could not load contact list.',
                    duration: 10000,
                });
            });
        },

        createContact: function () {
            let self = this;
            let params = Object.assign({}, self.contact);
            axios.post('/web/contact', params)
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
            axios.get('/web/contact/' + id)
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
            axios.patch('/web/contact/' + id, params)
                .then(() => {
                    self.clearContactData();
                    self.clearErrorData();
                    self.fetchContactList();
                })
                .then(() => {
                    this.$notify({
                        type: 'warn',
                        title: 'Updated',
                        text: 'The contact was updated.',
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
            axios.delete('/web/contact/' + id, params)
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
                        text: 'Could not load delete contact.',
                        duration: 10000,
                    });
                });
        },
    },
}
</script>
