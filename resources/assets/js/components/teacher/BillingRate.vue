<template>
    <div>
        <div v-if="alert" class="alert alert-success alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" @click="alert=false" aria-label="close">&times;</a>
            {{ toast.success }}
        </div>

        <div class="card">
            <div v-if="showForm">
                <!-- modal create read edit -->
                <transition name="modal">
                    <div class="modal-mask">
                        <div class="modal-wrapper">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" v-show="edit">Edit Rate Record</h5>
                                        <h5 class="modal-title" v-show="!edit">Create a New Rate</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" @click="cancelForm()">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body" v-show="!read">
                                        <form class="form-horizontal" action="#" @submit.prevent="edit ? updateRate(rate.id) : createBillingRate()">
                                            <div class="row">
                                                <div class="col-md-6 offset-3">
                                                    <div class="form-group" :class="error_type && classError">
                                                        <label for="single-select">Rate Types</label>
                                                        <select id="single-select" class="form-control" v-model="rate.type">
                                                            <option v-for="type in types" :value="type">{{ type | capitalising }}</option>
                                                        </select>
                                                        <small>{{ error_type }}</small>
                                                    </div>
                                                    <div class="form-group" :class="error_amount && classError">
                                                        <label for="amount">Amount</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">$</span>
                                                            </div>
                                                            <input id="amount" v-model="rate.amount" type="text" class="form-control">
                                                        </div>
                                                        <small>{{ error_amount }}</small>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="description">Description</label>
                                                        <div class="input-group mb-3">
                                                            <input id="description" v-model="rate.description" placeholder="optional" type="text" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr/>

                                            <div class="form-group pull-right">
                                                <button @click="cancelForm()" class="btn btn-default">Cancel</button>
                                                <button v-show="!edit" type="submit" class="btn btn-primary">Save</button>
                                                <button v-show="edit" type="submit" class="btn btn-primary">Update</button>
                                                <p>{{title}}</p>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
                <!-- end of modal create read edit -->
            </div>

            <div class="card-header bg-light">Billing Rates</div>
            <!-- vue js data table -->
            <div class="form-control">
                <div class="form-group pull-left">
                    <div class="form-group">
                        <select id="single-select" v-model="per_page" class="form-control">
                            <option v-for="page in pages" :value="page">{{ page }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group pull-right">
                    <button type="button" class="btn btn-primary" @click="showForm=true" v-show="!showForm">Create Rate</button>
                </div>
                <div class="form-group pull-right pr-2">
                    <input type="text" class="form-control" v-model="filter" placeholder="Search" @keydown="$event.stopImmediatePropagation()">
                </div>
                <datatable class="table table-responsive-md" :columns="columns" :data="list" :filter="filter" :per-page="per_page">
                    <template v-slot="{ columns, row }">
                        <tr>
                            <td>{{ row.type | capitalising }}</td>
                            <td>{{ row.amount | toCurrency }}</td>
                            <td v-text="row.description"></td>
                            <td>{{ row.created_at | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY hh:mm a') }}</td>
                            <td class="text-nowrap">
                                <button @click="showRate(row.id, false)" class="btn btn-outline-primary btn-sm" title="edit"><i class="fa fa-edit"></i></button>
                                <button @click="showModalDelete(row.id)" class="btn btn-outline-danger btn-sm" title="click to delete"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                    </template>
                </datatable>
                <div class="pull-left">
                    Total: {{ list.length }} entries
                </div>
                <div class="pull-right">
                    <bootstrap-3-datatable-pager class="pagination" v-model="page" type="abbreviated" :per-page="per_page"></bootstrap-3-datatable-pager>
                </div>
            </div>
            <!-- end of vue js data table -->

            <!-- modal delete contact -->
            <div v-if="showModal">
                <transition name="modal">
                    <div class="modal-mask">
                        <div class="modal-wrapper">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Delete Rate Record</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" @click="showModal=false">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Do you want to delete this rate record?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" @click="showModal = false">Cancel</button>
                                        <button type="button" @click="deleteRate(id)" class="btn btn-danger">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
            <!-- end of modal delete contact -->

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
                {label: 'Type', field: 'type',},
                {label: 'Amount', field: 'amount',},
                {label: 'Description', filterable: false},
                {label: 'Created', field: 'created_at',},
                {label: 'Actions', filterable: false}
            ],
            types: ['lesson', 'hourly', 'weekly', 'monthly', 'yearly'],
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
                description: null,
            },
            error_type: '',
            error_amount: '',
        }
    },

    props: {
        title: String,
    },

    filters: {
        capitalising: function (data) {
            let capitalized = [];
            data.split(' ').forEach(word => {
                capitalized.push(
                    word.charAt(0).toUpperCase() +
                    word.slice(1).toLowerCase()
                )
            });
            return capitalized.join(' ');
        },

        toCurrency: function (value) {
            let formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            });

            return formatter.format(value);
        }
    },

    mounted: function () {
        this.fetchRateList();
    },

    // computed: {
    //     // hasListData: function () {
    //     //     return this.list ? this.list.length > 0 : false;
    //     // }
    // },

    methods: {
        cancelForm: function () {
            let self = this;
            self.clearErrorData();
            self.clearRateData();
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
            self.showForm = false;
            self.rate.type = null;
            self.rate.amount = null;
            self.rate.description = null;
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
            axios.post('/web/billing-rate', params)
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

        showRate: function (id, read) {
            let self = this;
            self.showForm = true;
            self.read = read;
            self.edit = true;
            axios.get('/web/billing-rate/' + id)
                .then(function (response) {
                    self.rate.id = response.data.id;
                    self.rate.type = response.data.type;
                    self.rate.amount = response.data.amount;
                    self.rate.description = response.data.description;
                })
            self.edit = true;
        },

        updateRate: function (id) {
            let self = this;
            let params = Object.assign({}, self.rate);
            axios.patch('/web/billing-rate/' + id, params)
                .then(function (success) {
                    self.alert = true;
                    self.toast = success.data;
                    self.clearRateData();
                    self.clearErrorData();
                    self.fetchRateList();
                })
                .catch(function (error) {
                    self.getErrorMessage(error);
                });
        },

        deleteRate: function (id) {
            let self = this;
            let params = Object.assign({}, self.rate);
            axios.delete('/web/billing-rate/' + id, params)
                .then(function (success) {
                    self.alert = true;
                    self.showModal = false;
                    self.toast = success.data;
                    self.fetchRateList();
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

    },
}
</script>

<style>
@import '/webapp/css/stylesheet.css';
</style>

