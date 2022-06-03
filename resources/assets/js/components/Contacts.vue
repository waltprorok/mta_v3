<template>
    <div class="card">
        <button type="button" class="btn btn-default" @click="showForm=true" v-show="!showForm">Add Contact</button>
        <div v-if="showForm">
            <transition name="modal">
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" v-show="read">Read Contact Record</h5>
                                    <h5 class="modal-title" v-show="edit && !read">Edit Contact Record</h5>
                                    <h5 class="modal-title" v-show="!edit && !read">Add Contact Record</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" @click="cancelForm()">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" v-show="read">
                                    <p>From: {{ contact.name }}</p>
                                    <p>Email: {{ contact.email }}</p>
                                    <p>Subject: {{ contact.subject }}</p>
                                    <textarea class="form-control" rows="24" disabled>{{ contact.message }}</textarea>
                                    <hr/>
                                    <div class="form-group pull-right">
                                        <button v-show="showForm" @click="cancelForm()" class="btn btn-default">Cancel</button>
                                    </div>
                                </div>
                                <div class="modal-body" v-show="!read">
                                    <form action="#" @submit.prevent="edit ? updateContact(contact.id) : createContact()">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input id="name" v-model.trim="contact.name" type="text" class="form-control">
                                            <small>{{ error_name }}</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input id="email" v-model.trim="contact.email" type="text" class="form-control">
                                            <small>{{ error_email }}</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="subject">Subject</label>
                                            <input id="subject" v-model.trim="contact.subject" type="text" class="form-control">
                                            <small>{{ error_subject }}</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="message">Message</label>
                                            <textarea id="message" v-model.trim="contact.message" class="form-control" rows="15"></textarea>
                                            <small>{{ error_message }}</small>
                                        </div>
                                        <div class="form-group pull-right">
                                            <button v-show="showForm" @click="cancelForm()" class="btn btn-default">Cancel</button>
                                            <button v-show="!edit" type="submit" class="btn btn-primary">Save</button>
                                            <button v-show="edit" type="submit" class="btn btn-primary">Update</button>
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
            <div class="form-group pull-right">
                <input type="text" class="form-control" v-model="filter" placeholder="Search" @keydown="$event.stopImmediatePropagation()">
            </div>
            <datatable class="table table-responsive-md" :columns="columns" :data="list" :filter="filter" :per-page="per_page">
                <template v-slot="{ columns, row }">
                    <tr>
                        <td v-text="row.name"></td>
                        <td><a :href="'mailto:' + row.email" v-text="row.email"></a></td>
                        <td v-text="row.subject"></td>
                        <td v-text="row.message.substring(0, 100) + '...'"></td>
                        <td>{{ row.created_at | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY hh:mm a') }}</td>
                        <td class="text-nowrap">
                            <button @click="showContact(row.id, true)" class="btn btn-outline-primary btn-sm" title="read"><i class="fa fa-envelope-open"></i></button>
                            <button @click="showContact(row.id, false)" class="btn btn-outline-secondary btn-sm" title="edit"><i class="fa fa-edit"></i></button>
                            <button @click="showModalDelete(row.id)" class="btn btn-outline-danger btn-sm" title="click to delete"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                </template>
            </datatable>
            <div class="pull-right">
                <bootstrap-3-datatable-pager class="pagination" v-model="page" type="abbreviated" :per-page="per_page"></bootstrap-3-datatable-pager>
            </div>
        </div>
        <!-- end of vue js data table -->

        <div v-if="showModal">
            <transition name="modal">
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delete Contact Record</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" @click="showModal = false">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Do you want to delete this contact us record?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" @click="showModal = false">Cancel</button>
                                    <button type="button" @click="deleteContact(id)" class="btn btn-danger">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            filter: '',
            columns: [
                {label: 'Name', field: 'name',},
                {label: 'Email', field: 'email',},
                {label: 'Subject', field: 'subject',},
                {label: 'Message', field: 'message',},
                {label: 'Created', field: 'created_at',},
                {label: 'Actions',}
            ],
            edit: false,
            showForm: false,
            read: false,
            showModal: false,
            list: [],
            page: 1,
            per_page: 10,
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
            self.error_name = '';
            self.error_email = '';
            self.error_subject = '';
            self.error_message = '';
        },

        showModalDelete: function (id) {
            let self = this;
            self.showModal = true;
            self.id = id;
        },

        fetchContactList: function () {
            axios.get('/web/contact')
                .then((response) => {
                    this.list = response.data;
                }).catch((error) => {
                console.log(error);
            });
        },

        createContact: function () {
            let self = this;
            let params = Object.assign({}, self.contact);
            axios.post('/web/contact', params)
                .then(function () {
                    self.contact.name = null;
                    self.contact.email = null;
                    self.contact.subject = null;
                    self.contact.message = null;
                    self.edit = false;
                    self.showForm = false;
                    self.error_name = '';
                    self.error_email = '';
                    self.error_subject = '';
                    self.error_message = '';
                    self.fetchContactList();
                })
                .catch(function (error) {
                    self.error_name = error.response.data.error.name;
                    self.error_email = error.response.data.error.email;
                    self.error_subject = error.response.data.error.subject;
                    self.error_message = error.response.data.error.message;
                });
        },

        showContact: function (id, read) {
            let self = this;
            self.showForm = true;
            self.read = read;
            axios.get('/web/contact/' + id)
                .then(function (response) {
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
                .then(function () {
                    self.contact.name = '';
                    self.contact.email = '';
                    self.contact.subject = '';
                    self.contact.message = '';
                    self.edit = false;
                    self.fetchContactList();
                })
                .catch(function (error) {
                    self.contact.name = '';
                    self.contact.email = '';
                    self.contact.subject = '';
                    self.contact.message = '';
                    self.edit = false;
                    self.fetchContactList();
                    console.log(error);
                });
            self.showForm = false;
        },

        deleteContact: function (id) {
            let self = this;
            let params = Object.assign({}, self.contact);
            axios.delete('/web/contact/' + id, params)
                .then(function () {
                    self.showModal = false;
                    self.fetchContactList();
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
    },
}
</script>

<style>
small {
    color: red;
}

.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    backdrop-filter: brightness(25%);
    backdrop-filter: contrast(55%);
    display: table;
    transition: opacity .8s ease;
}

.modal-wrapper {
    display: table-cell;
    vertical-align: top;
}

table thead tr th {
    text-align: left !important;
}

.pagination {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    padding-left: 0;
    list-style: none
}

.pagination ul li {
    position: relative;
    display: block;
    padding: .5rem .75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #777;
    background-color: #fff;
    border: 1px solid #dee2e6
}

.pagination ul li:hover {
    color: #515151;
    text-decoration: none;
    background-color: #e9ecef;
    border-color: #dee2e6
}

.pagination ul li:focus {
    z-index: 2;
    outline: 0;
    -webkit-box-shadow: none;
    box-shadow: none
}

.pagination ul li:not(:disabled):not(.disabled) {
    cursor: pointer
}

.pagination ul li:first-child {
    margin-left: 0
}

.pagination ul li.active {
    z-index: 1;
    color: #fff;
    background-color: #42a5f5;
    border-color: #42a5f5
}

.pagination ul li.disabled {
    color: #999;
    pointer-events: none;
    cursor: auto;
    background-color: #fff;
    border-color: #dee2e6
}
</style>
