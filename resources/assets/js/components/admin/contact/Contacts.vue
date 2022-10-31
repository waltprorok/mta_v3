<template src="./contact-template.html"></template>

<style>
/*@import '/webapp/css/stylesheet.css';*/
</style>

<script>
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

    computed: {
        hasListData: function () {
            return this.list ? this.list.length > 0 : false;
        }
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
            });
        },

        createContact: function () {
            let self = this;
            let params = Object.assign({}, self.contact);
            axios.post('/web/contact', params)
                .then((success) => {
                    self.clearContactData()
                    self.clearErrorData();
                    self.fetchContactList();
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
                .catch((error) => {
                    self.getErrorMessage(error);
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
                .catch((error) => {
                    console.log(error);
                });
        },
    },
}
</script>
