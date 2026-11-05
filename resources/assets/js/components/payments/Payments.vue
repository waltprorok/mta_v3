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

    computed: {
        totalInvoiced() {
            return this.list.reduce((sum, row) => {
                return sum + Number(row.total || 0);
            }, 0);
        },

        totalPaid() {
            return this.list.reduce((sum, row) => {
                return sum + Number(row.payment || 0);
            }, 0);
        },

        totalBalance() {
            return this.totalInvoiced - this.totalPaid;
        }
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

<style>
.border-left-primary {
    border-left: 4px solid #007bff !important;
}

.border-left-success {
    border-left: 4px solid #28a745 !important;
}

.border-left-danger {
    border-left: 4px solid #dc3545 !important;
}

.border-left-warning {
    border-left: 4px solid #ffc107 !important;
}

.border-left-secondary {
    border-left: 4px solid #6c757d !important;
}
</style>
