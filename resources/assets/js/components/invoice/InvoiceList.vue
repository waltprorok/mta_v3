<template src="./invoice-list-template.html"></template>

<script>
import TotalEntries from "../TotalEntries";
import PhoneNumberFormat from "../PhoneNumberFormat";

export default {
    data: function () {
        return {
            filter: '',
            list: [],
            page: 1,
            per_page: 10,
            pages: [10, 25, 50, 100],
            columns: [
                {label: 'Paid', field: 'is_paid',},
                {label: 'First Name', field: 'first_name',},
                {label: 'Last Name', field: 'last_name',},
                {label: 'Phone', field: 'phone', sortable: false},
                {label: 'Email', field: 'email',},
                {label: 'Balance', field: 'balance_due',},
                {label: 'Actions', filterable: false},
            ],
            invoice: {
                id: null,
                student_id: null,
                subtotal: null,
                discount: null,
                total: null,
                balance_due: null,
                payment: null,
                check_number: null,
                payment_information: null,
                adjustments: null,
                payment_type_id: null,
                is_paid: null,
            },
            student: {
                id: null,
                first_name: null,
                last_name: null,
                phone: null,
                email: null,
                instrument: null,
                status: null,
            },
            types: [
                {label: 'Cash', type: 1,},
                {label: 'Check', type: 2,},
                {label: 'Credit Card', type: 3,},
                {label: 'Debit Card', type: 4,},
                {label: 'Stripe', type: 5,},
                {label: 'PayPal', type: 6,},
                {label: 'Cash App', type: 7,},
                {label: 'Zelle', type: 8,},
            ],
            showCheckField: false,
            showModal: false,
            placeholderValue: 'Notes about transaction',
            paymentPlaceHolderValue: 'Enter a positive amount',
            disableSave: true,
            error_payment: '',
            error_payment_type_id: '',
            classError: '',

        }
    },

    mounted: function () {
        this.fetchInvoiceList();
    },

    components: {
        TotalEntries,
        PhoneNumberFormat,
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

    methods: {
        cancelForm: function () {
            let self = this;
            self.clearErrorData();
            self.clearInvoicePaymentData();
        },

        fetchInvoiceList: function () {
            axios.get('/web/invoice')
                .then((response) => {
                    this.list = response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load invoice list.',
                        duration: 10000,
                    });
                });
        },

        clearInvoicePaymentData: function () {
            this.invoice.payment_type_id = null;
            this.invoice.payment = null;
            this.invoice.check_number = null;
            this.showCheckField = false;
            this.invoice.payment_information = null;
            this.disableSave = true;
            this.showModal = false;
        },

        createInvoicePayment: function () {
            let self = this;
            let params = Object.assign({}, self.invoice);
            axios.patch('/web/invoice/update/' + self.invoice.id, params)
                .then(() => {
                    self.cancelForm();
                    self.fetchInvoiceList();
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'Invoice payment successful.',
                        duration: 10000,
                    });
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not update invoice payment.',
                        duration: 10000,
                    });
                });
        },

        clearErrorData: function () {
            let self = this;
            self.classError = '';
            self.error_payment_type_id = '';
            self.error_payment = '';
        },

        disableButton: function (row) {
            return row.balance_due !== 0 && row.is_paid !== true;
        },

        getErrorMessage: function (error) {
            let self = this;
            self.error_payment_type_id = error.response.data.error.payment_type_id;
            self.error_payment = error.response.data.error.payment;
            self.classError = 'has-error';
        },

        hasPaymentAmount: function () {
            if (this.invoice.amount !== null) {
                this.disableSave = false;
            }
        },

        showInvoicePaymentModal: function (id) {
            let self = this;
            self.invoice.id = id
            self.showModal = true;
        },

        showCheckInput: function (event) {
            this.showCheckField = event.target.value === '2';
            if (event.target.value !== '2') {
                this.invoice.check_number = null;
            }
        },

    },
}
</script>
