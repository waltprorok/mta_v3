<template src="./payments-template.html"></template>

<script>
import TotalEntries from "../TotalEntries";
import PhoneNumberFormat from "../PhoneNumberFormat";

export default {
    data: function () {
        return {
            payments: null,
            filter: '',
            columns: [
                {label: 'Invoice', field: '',},
                {label: 'Payment Type', field: 'name', sortable: false,},
                {label: 'Total', field: 'total', sortable: false,},
                {label: 'Paid', field: 'payment', sortable: false,},
                {label: 'Balance', field: 'balance_due', sortable: false,},
                {label: 'Due Date', field: '',},
                {label: 'Issued', field: '',},
                {label: 'Action', field: false}
            ],
            list: [],
            page: 1,
            per_page: 10,
            pages: [10, 25, 50, 100],
        }
    },

    components: {
        PhoneNumberFormat,
        TotalEntries
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

    mounted: function () {
        this.fetchPaymentsList();
    },

    methods: {
        fetchPaymentsList: function () {
            axios.get('/web/payments')
                .then((response) => {
                    this.list = response.data.invoices;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load payments list.',
                        duration: 6000,
                    });
                });
        },

    },
}
</script>
