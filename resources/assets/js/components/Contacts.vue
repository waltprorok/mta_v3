<template>
    <div class="card">
<!--        <div class="form-control">-->
<!--            <h4>Edit Contact</h4>-->
<!--            <br/>-->
<!--            <form action="#" @submit.prevent="edit ? updateContact(contact.id) : createContact()">-->
<!--                <div class="form-group">-->
<!--                    <label>Name</label>-->
<!--                    <input v-model="contact.name" type="text" name="name" class="form-control">-->
<!--                </div>-->
<!--                <div class="form-group">-->
<!--                    <label>Email</label>-->
<!--                    <input v-model="contact.email" type="text" name="email" class="form-control">-->
<!--                </div>-->
<!--                <div class="form-group">-->
<!--                    <label>Subject</label>-->
<!--                    <input v-model="contact.subject" type="text" name="subject" class="form-control">-->
<!--                </div>-->
<!--                <div class="form-group">-->
<!--                    <label>Message</label>-->
<!--                    <textarea v-model="contact.message" name="message" class="form-control"></textarea>-->
<!--                </div>-->
<!--                <div class="form-group">-->
<!--                    <button v-show="!edit" type="submit" class="btn btn-primary">New Contact</button>-->
<!--                    <button v-show="edit" type="submit" class="btn btn-primary">Update Contact</button>-->
<!--                </div>-->
<!--            </form>-->
<!--        </div>-->

<!--        <br/>-->

        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Subject</th>
                <th scope="col">Message</th>
<!--                <th scope="col">Edit</th>-->
                <th scope="col">Remove</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="contact in list">
                <td>{{ contact.name }}</td>
                <td>{{ contact.email }}</td> <!-- TODO: Make an anchor tag to open a new page to send a response email -->
                <td>{{ contact.subject }}</td>
                <td>{{ contact.message }}</td>
<!--                <td>-->
<!--                    <button @click="showContact(contact.id)" class="btn btn-default btn-xs">Edit</button>-->
<!--                </td>-->
                <td>
                    <button @click="deleteContact(contact.id)" class="btn btn-danger btn-sm">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            edit: false,
            list: [],
            contact: {
                id: '',
                name: '',
                email: '',
                subject: '',
                message: '',
            }
        }
    },
    mounted: function () {
        this.fetchContactList();
    },
    methods: {
        fetchContactList: function () {
            axios.get('api/contacts')
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
    }
}
</script>

