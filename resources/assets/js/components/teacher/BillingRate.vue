<template>
    <div>
        <div v-if="alert" class="alert alert-success alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" @click="alert=false" aria-label="close">&times;</a>
            {{ toast.success }}
        </div>

        <div class="card">
            <div class="card-header bg-light">Billing Rates</div>
            <div class="card-body">

                <form class="form-horizontal" action="#" @submit.prevent="createBillingRate()">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group" :class="error_type && classError">
                                <label for="single-select">Rate Types</label>
                                <select id="single-select" class="form-control" v-model="rate.type">
                                    <option v-for="type in types" :value="type">{{ type | capitalising }}</option>
                                </select>
                                <small>{{ error_type }}</small>
                            </div>

                            <div class="form-group" :class="error_amount && classError">
                                <label for="name">Amount</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input id="name" v-model.trim="rate.amount" type="text" class="form-control">
                                </div>
                                <small>{{ error_amount }}</small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div v-for="rate in list">
                                <label for="hourly" class="control-label">{{ rate.type | capitalising }} Type</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>

                                    <input type="text" class="form-control" name="hourly" v-model="rate.amount">
                                    <div class="input-group-append">
                                        <span class="input-group-text">.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr/>

                    <div class="pull-left">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button @click="cancelForm()" class="btn btn-default">Cancel</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            toast: '',
            alert: false,
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
            types: ['hourly', 'weekly', 'monthly'],
            edit: false,
            showForm: false,
            read: false,
            showModal: false,
            list: [],
            page: 1,
            per_page: 10,
            pages: [10, 25, 50, 100],
            rate: {
                id: null,
                type: null,
                amount: null,
            },
            error_type: '',
            error_amount: '',
        }
    },

    filters: {
        capitalising: function (data) {
            let capitalized = []
            data.split(' ').forEach(word => {
                capitalized.push(
                    word.charAt(0).toUpperCase() +
                    word.slice(1).toLowerCase()
                )
            })
            return capitalized.join(' ')
        }
    },

    mounted: function () {
        this.fetchRateList();
    },

    computed: {
        // hasListData: function () {
        //     return this.list ? this.list.length > 0 : false;
        // }
    },

    methods: {
        cancelForm: function () {
            let self = this;
            self.rate.type = null;
            self.rate.amount = null;
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
            self.error_type = '';
            self.error_amount = '';
        },

        clearRateData: function () {
            let self = this;
            self.rate.type = null;
            self.rate.amount = null;
        },

        getErrorMessage: function (error) {
            let self = this;
            self.error_type = error.response.data.error.type;
            self.error_amount = error.response.data.error.amount;
            self.classError = 'has-error';
        },

        fetchRateList: function () {
            axios.get('/web/billing-rate')
                .then((response) => {
                    this.list = response.data;
                }).catch((error) => {
                console.log(error);
            });
        },

        createBillingRate: function () {
            let self = this;
            let params = Object.assign({}, self.rate);
            axios.post('/web/billing-rate-save', params)
                .then(function (success) {
                    self.alert = true;
                    self.toast = success.data;
                    self.clearRateData()
                    self.clearErrorData();
                    self.fetchRateList();
                })
                .catch(function (error) {
                    self.getErrorMessage(error);
                });
        },

        // showContact: function (id, read) {
        //     let self = this;
        //     self.showForm = true;
        //     self.read = read;
        //     axios.get('/web/contact/' + id)
        //         .then(function (response) {
        //             self.contact.id = response.data.id;
        //             self.contact.name = response.data.name;
        //             self.contact.email = response.data.email;
        //             self.contact.subject = response.data.subject;
        //             self.contact.message = response.data.message;
        //         })
        //     self.edit = true;
        // },

        updateRate: function (id) {
            let self = this;
            let params = Object.assign({}, self.rate);
            axios.patch('/web/contact/' + id, params)
                .then(function (success) {
                    self.alert = true;
                    self.toast = success.data;
                    self.clearContactData();
                    self.clearErrorData();
                    self.fetchContactList();
                })
                .catch(function (error) {
                    self.getErrorMessage(error);
                });
        },

        // deleteContact: function (id) {
        //     let self = this;
        //     let params = Object.assign({}, self.contact);
        //     axios.delete('/web/contact/' + id, params)
        //         .then(function (success) {
        //             self.alert = true;
        //             self.showModal = false;
        //             self.toast = success.data;
        //             self.fetchContactList();
        //         })
        //         .catch(function (error) {
        //             console.log(error);
        //         });
        // },

    },
}
</script>

<style>
@import '/webapp/css/stylesheet.css';
</style>

