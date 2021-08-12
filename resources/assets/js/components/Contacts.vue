<template>
    <div class="card">
        <div class="form-control">
            <h4>Edit Contact</h4>
            <br/>
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
                    <textarea id="message" v-model="contact.message" name="message" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <button v-show="!edit" type="submit" class="btn btn-primary">New Contact</button>
                    <button v-show="edit" type="submit" class="btn btn-primary">Update Contact</button>
                </div>
            </form>
        </div>

        <br/>

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
                <td>{{ contact.message }}</td>
                <td>{{ contact.created_at | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY hh:mm a') }}
                </td>
                <td class="text-nowrap">
                    <button @click="showContact(contact.id)" class="btn btn-outline-primary btn-sm" title="edit"><i
                        class="fa fa-edit"></i></button>
                    <button @click="deleteContact(contact.id)" class="btn btn-outline-danger btn-sm"
                            title="click to delete"><i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

export default {
    data() {
        return {
            edit: false,
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
                    self.contact.name = '';
                    self.contact.email = '';
                    self.contact.subject = '';
                    self.contact.message = '';
                    self.edit = false;
                    self.fetchContactList();
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        showContact: function (id) {
            let self = this;
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
        },
        deleteContact: function (id) {
            let self = this;
            let params = Object.assign({}, self.contact);
            axios.delete('api/contact/' + id, params)
                .then(function () {
                    self.fetchContactList();
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
    },
}
</script>

