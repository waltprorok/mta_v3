<template src="./list-of-payments-template.html"></template>

<script>
import TotalEntries from "../../TotalEntries";
import PaymentModal from "../../modals/invoice/PaymentModal.vue";
import PhoneNumberFormat from "../../PhoneNumberFormat";
import {dateParse} from "@vuejs-community/vue-filter-date-parse";
import {dateFormat} from "vue-filter-date-format";

export default {
    data: function () {
        return {
            filter: '',
            list: [],
            page: 1,
            per_page: 10,
            pages: [10, 25, 50, 100],
            showModalPayment: false,
            columns: [
                {label: 'Invoice', field: 'id', sortable: false,},
                {label: 'Payment Date', field: 'date', sortable: false,},
                {label: 'Payment Type', field: 'payment_type', sortable: false,},
                {label: 'Total', field: 'total', sortable: false,},
                {label: 'Paid', field: 'payment', sortable: false,},
                {label: 'Balance', field: 'balance', sortable: false,},
                {label: 'Action', filterable: false},
            ],
        }
    },

    mounted: function () {
        this.fetchListOfPayments();
    },

    components: {
        PaymentModal,
        TotalEntries,
        PhoneNumberFormat,
    },

    filters: {
        toCurrency: function (value) {
            let formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            });

            return formatter.format(value);
        }
    },

    methods: {
        dateFormat,
        dateParse,
        fetchListOfPayments: function () {
            axios.get('/web/invoice/list-of-payments')
                .then((response) => {
                    this.list = response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load list of payments list.',
                        duration: 10000,
                    });
                });
        },
        handleModalClose(value) {
            let self = this;
            self.showModalPayment = value;
        },
        showModal: function (row) {
            let self = this;
            self.showModalPayment = true;
            self.row = row;
        },
    }
}
</script>
