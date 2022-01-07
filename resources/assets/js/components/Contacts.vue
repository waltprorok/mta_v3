<template>
    <div class="card">
        <button type="button" class="btn btn-default" @click="showForm=true" v-show="!showForm">Add Contact</button>
        <div v-if="showForm">
            <transition name="modal">
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" v-show="read">Contact Record</h5>
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
                                    <textarea class="form-control" rows="24" disabled>{{ contact.message}}</textarea>
                                    <hr/>
                                    <div class="form-group pull-right">
                                        <button v-show="showForm" @click="cancelForm()" class="btn btn-default">Cancel</button>
                                    </div>
                                </div>
                                <div class="modal-body" v-show="!read">
                                    <form action="#" @submit.prevent="edit ? updateContact(contact.id) : createContact()">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input id="name" v-model="contact.name" type="text" name="name" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input id="email" v-model="contact.email" type="text" name="email" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="subject">Subject</label>
                                            <input id="subject" v-model="contact.subject" type="text" name="subject" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="message">Message</label>
                                            <textarea id="message" v-model="contact.message" name="message" class="form-control" rows="15"></textarea>
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

        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Subject</th>
                <th scope="col">Message</th>
                <th scope="col">Created</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="contact in list">
                <td>{{ contact.name }}</td>
                <td><a v-bind:href="'mailto:' + contact.email">{{ contact.email }}</a></td>
                <!-- TODO: Make an anchor tag to open a new page to send a response email -->
                <td>{{ contact.subject }}</td>
                <td>{{ contact.message.substring(0, 100) + "..." }}</td>
                <td>{{ contact.created_at | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY hh:mm a') }}</td>
                <td class="text-nowrap">
                    <button @click="showContact(contact.id, true)" class="btn btn-outline-primary btn-sm" title="read"><i class="fa fa-envelope-open"></i></button>
                    <button @click="showContact(contact.id, false)" class="btn btn-outline-secondary btn-sm" title="edit"><i class="fa fa-edit"></i></button>
                    <button @click="showModalDelete(contact.id)" class="btn btn-outline-danger btn-sm" title="click to delete"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </td>
            </tr>
            </tbody>
        </table>

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
    data() {
        return {
            edit: false,
            showForm: false,
            read: false,
            showModal: false,
            list: [],
            contact: {
                id: null,
                name: null,
                email: null,
                subject: null,
                message: null,
                created_at: null,
            },
        }
    },
    mounted: function () {
        this.fetchContactList();
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
        },
        showModalDelete: function (id) {
            let self = this;
            self.showModal = true;
            self.id = id;
        },
        fetchContactList: function () {
            axios.get('api/contact')
                .then((response) => {
                    this.list = response.data;
                }).catch((error) => {
                console.log(error);
            });
        },
        createContact: function () {
            let self = this;
            let params = Object.assign({}, self.contact);
            axios.post('api/contact/store', params)
                .then(function () {
                    self.contact.name = null;
                    self.contact.email = null;
                    self.contact.subject = null;
                    self.contact.message = null;
                    self.edit = false;
                    self.showForm = false;
                    self.fetchContactList();
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        showContact: function (id, read) {
            let self = this;
            self.showForm = true;
            self.read = read;
            axios.get('api/contact/' + id)
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
            axios.patch('api/contact/' + id, params)
                .then(function (response) {
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
            axios.delete('api/contact/' + id, params)
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
</style>
