<template>
    <div class="card">
        <div class="form-control">
            <div class="form-group pull-left">
                <div class="form-group">
                    <select id="single-select" v-model="per_page" class="form-control">
                        <option v-for="page in pages" :value="page">{{ page }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group pull-right">
                <a :href="`/invoice/create`" class="btn btn-primary" role="button" title="create invoice">Create Invoice</a>
            </div>


            <div class="form-group pull-right pr-2">
                <input type="text" class="form-control" v-model="filter" placeholder="Search" @keydown="$event.stopImmediatePropagation()">
            </div>
            <datatable class="table table-responsive-md table-hover" :columns="columns" :data="list" :filter="filter" :per-page="per_page">
                <template v-slot="{ columns, row }">
                    <tr>
                        <td>{{ row.id }}</td>
                        <td>{{ row.payment_type.name }}</td>
                        <td>{{ row.payment | toCurrency }}</td>
                        <td>{{ row.balance_due | toCurrency }}</td>
                        <td>{{ row.updated_at | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY h:mm a') }}</td>
                        <td class="text-nowrap">
                            <a :href="`/invoice/show/${row.id}`" class="btn btn-sm btn-outline-primary" role="button" title="view"><i class="fa fa-file-pdf-o"></i></a>
                            <a :href="`/invoice/download/pdf/${row.id}`" class="btn btn-sm btn-outline-secondary" role="button" title="download invoice"><i class="fa fa-download"></i></a>
                            <!--                    <button @click="showInvoicePaymentModal(row.id)"-->
                            <!--                            class="btn btn-outline-success btn-sm"-->
                            <!--                            title="make payment"-->
                            <!--                            v-if="disableButton(row)">-->
                            <!--                        <i class="fa fa-dollar"></i>-->
                            <!--                    </button>-->
                        </td>
                    </tr>
                </template>
            </datatable>
            <total-entries v-bind:list="list"></total-entries>
            <div class="pull-right">
                <bootstrap-3-datatable-pager class="pagination" v-model="page" type="abbreviated" :per-page="per_page"></bootstrap-3-datatable-pager>
            </div>
        </div>
        <!-- end of vue js data table -->
        <notifications position="bottom right"/>
    </div>
</template>


<script>
import TotalEntries from "../TotalEntries";
import PhoneNumberFormat from "../PhoneNumberFormat";
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
            columns: [
                // {label: 'Paid', field: 'is_paid',},
                {label: 'Invoice', field: 'id',},
                // {label: 'First Name', field: 'first_name',},
                // {label: 'Last Name', field: 'last_name',},
                // {label: 'Phone', field: 'phone', sortable: false},
                // {label: 'Email', field: 'email',},
                {label: 'Payment Type', field: 'payment_type',},
                {label: 'Paid', field: 'payment',},
                {label: 'Balance', field: 'balance',},
                {label: 'Date', field: 'date',},
                {label: 'Actions', filterable: false},
            ],
        }
    },

    mounted: function () {
        this.fetchListOfPayments();
    },

    components: {
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
    }
}
</script>
