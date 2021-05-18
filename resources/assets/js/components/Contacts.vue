<template>
    <div>
<!--        <h1>Add Contact</h1>-->
<!--        <form action="#" @submit.prevent="edit ? updateContact(contact.id) : createContact()">-->
<!--            <div class="form-group">-->
<!--                <label>Name</label>-->
<!--                <input v-model="contact.name" type="text" name="name" class="form-control">-->
<!--            </div>-->
<!--            <div class="form-group">-->
<!--                <label>Email</label>-->
<!--                <input v-model="contact.email" type="text" name="email" class="form-control">-->
<!--            </div>-->
<!--            <div class="form-group">-->
<!--                <label>Phone</label>-->
<!--                <input v-model="contact.phone" type="text" name="phone" class="form-control">-->
<!--            </div>-->
<!--            <div class="form-group">-->
<!--                <button v-show="!edit" type="submit" class="btn btn-primary">New Contact</button>-->
<!--                <button v-show="edit" type="submit" class="btn btn-primary">Update Contact</button>-->
<!--            </div>-->
<!--        </form>-->

        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Edit</th>
                <th>Remove</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="contact in list">
                <td>{{contact.name}}</td>
                <td>{{contact.email}}</td>
                <td>{{contact.subject}}</td>
                <td>{{contact.message}}</td>
                <td>
                    <button @click="showContact(contact.id)" class="btn btn-default btn-xs">Edit</button>
                </td>
                <td>
                    <button @click="deleteContact(contact.id)" class="btn btn-danger btn-xs">Delete</button>
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
        console.log('Contacts Component Loaded...');
        this.fetchContactList();
    },
    methods: {
        fetchContactList: function () {
            console.log('fetching contacts...');
            axios.get('api/contacts')
                .then((response) => {
                    console.log(response.data);
                    this.list = response.data;
                }).catch((error) => {
                console.log(error);
            });
        },
        // createContact: function () {
        //     console.log('creating contact...');
        //     let self = this;
        //     let params = Object.assign({}, self.contact);
        //     axios.post('api/contact/store', params)
        //         .then(function () {
        //             self.contact.name = '';
        //             self.contact.email = '';
        //             self.contact.phone = '';
        //             self.edit = false;
        //             self.fetchContactList();
        //         })
        //         .catch(function (error) {
        //             console.log(error);
        //         });
        // },
        // showContact: function (id) {
        //     let self = this;
        //     axios.get('api/contact/' + id)
        //         .then(function (response) {
        //             self.contact.id = response.data.id;
        //             self.contact.name = response.data.name;
        //             self.contact.email = response.data.email;
        //             self.contact.phone = response.data.phone;
        //         })
        //     self.edit = true;
        // },
        // updateContact: function (id) {
        //     console.log('updating contact ' + id + '...');
        //     let self = this;
        //     let params = Object.assign({}, self.contact);
        //     axios.patch('api/contact/' + id, params)
        //         .then(function () {
        //             self.contact.name = '';
        //             self.contact.email = '';
        //             self.contact.phone = '';
        //             self.edit = false;
        //             self.fetchContactList();
        //         })
        //         .catch(function (error) {
        //             self.contact.name = '';
        //             self.contact.email = '';
        //             self.contact.phone = '';
        //             self.edit = false;
        //             self.fetchContactList();
        //             console.log(error);
        //         });
        // },
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

