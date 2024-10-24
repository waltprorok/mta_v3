<template>
    <div class="card">
        <div class="form-control" v-cloak>
            <div class="form-group pull-left">
                <div class="form-group">
                    <select id="single-select" v-model="per_page" class="form-control">
                        <option v-for="page in pages" :value="page">{{ page }}</option>
                    </select>
                </div>
            </div>

            <!--            <div class="form-group pull-right">-->
            <!--                <button type="button" class="form-control btn btn-primary" @click="show_form=true">Add Student</button>-->
            <!--            </div>-->
            <div class="form-group pull-right pr-2">
                <input type="text" class="form-control" v-model="filter" placeholder="Search" @keydown="$event.stopImmediatePropagation()">
            </div>
            <datatable class="table table-responsive-md table-hover" :columns="columns" :data="list" :filter="filter" :per-page="per_page">
                <template v-slot="{ columns, row }">
                    <tr>
<!--                        <td>{{ row.i_created_at | dateParse('YYYY-MM-DD') | dateFormat('MM-DD-YYYY') }}</td>-->
                        <td>{{ row.i_updated_at| dateParse('YYYY-MM-DD') | dateFormat('MM-DD-YYYY') }}</td>
                        <td v-text="row.name"></td>
                        <td>{{ row.total | toCurrency }}</td>
                        <td>{{ row.payment | toCurrency }}</td>
                        <td>{{ row.balance_due | toCurrency }}</td>
                        <td class="text-nowrap">
<!--                            <a :href="`/payments/show/${row.v_id}`" class="btn btn-sm btn-outline-primary" role="button" title="view"><i class="fa fa-file-pdf-o"></i></a>-->
                            <a :href="`/payments/download/pdf/${row.v_id}`" class="btn btn-sm btn-outline-secondary" role="button" title="download invoice"><i class="fa fa-download"></i></a>
                        </td>
                    </tr>
                </template>
            </datatable>
            <total-entries v-bind:list="list"></total-entries>
            <div class="pull-right">
                <bootstrap-3-datatable-pager class="pagination" v-model="page" type="abbreviated" :per-page="per_page"></bootstrap-3-datatable-pager>
            </div>
        </div>
        <notifications position="bottom right"/>
    </div>
</template>

<script>
import TotalEntries from "../TotalEntries";
import PhoneNumberFormat from "../PhoneNumberFormat";

export default {
    data: function () {
        return {
            payments: null,
            filter: '',
            columns: [
                // {label: 'Date', field: '',},
                {label: 'Payment Date', field: '',},
                {label: 'Description', field: 'name', sortable: false,},
                {label: 'Total', field: 'total', sortable: false,},
                {label: 'Paid', field: 'payment', sortable: false,},
                {label: 'Balance', field: 'balance_due', sortable: false,},
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
                        duration: 10000,
                    });
                });
        },

    },
}
</script>
