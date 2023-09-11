<template src="./billing-rate-template.html"></template>

<script>
import TotalEntries from "../TotalEntries";
import {dateParse} from "@vuejs-community/vue-filter-date-parse";
import {dateFormat} from "vue-filter-date-format";

export default {
    name: 'BillingRate',
    data: function () {
        return {
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

    components: {
        TotalEntries
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

    methods: {
        dateFormat,
        dateParse,
        cancelForm: function () {
            let self = this;
            self.clearErrorData();
            self.clearRateData();
        },

        /**
         * @param {Object} row
         * @property {Object} billing_rate
         * @returns {boolean}
         */
        showDeleteIcon: function (row) {
            return row.billing_rate === null;
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
            self.edit = false;
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
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load billing rate list.',
                        duration: 10000,
                    });
                });
        },

        createBillingRate: function () {
            let self = this;
            let params = Object.assign({}, self.rate);
            axios.post('/web/billing-rate', params)
                .then(() => {
                    self.clearRateData()
                    self.clearErrorData();
                    self.fetchRateList();
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'Billing rate created.',
                        duration: 10000,
                    });
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not create billing rate.',
                        duration: 10000,
                    });
                });
        },

        showRate: function (id, read) {
            let self = this;
            self.showForm = true;
            self.read = read;
            self.edit = true;
            axios.get('/web/billing-rate/' + id)
                .then((response) => {
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
                .then(() => {
                    self.clearRateData();
                    self.clearErrorData();
                    self.fetchRateList();
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'Updated billing rate.',
                        duration: 10000,
                    });
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not update billing rate.',
                        duration: 10000,
                    });
                });
        },

        deleteRate: function (id) {
            let self = this;
            let params = Object.assign({}, self.rate);
            axios.delete('/web/billing-rate/' + id, params)
                .then(() => {
                    self.showModal = false;
                    self.fetchRateList();
                    this.$notify({
                        type: 'warn',
                        title: 'Deleted',
                        text: 'Billing rate was deleted.',
                        duration: 10000,
                    });
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not delete billing rate.',
                        duration: 10000,
                    });
                });
        },

    },
}
</script>
