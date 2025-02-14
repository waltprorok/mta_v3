<template>
    <div class="card" v-on:keydown.esc="showModalPayment=false">
        <div class="form-control">
            <div class="form-group pull-left">
                <div class="form-group">
                    <select id="single-select" v-model="per_page" class="form-control">
                        <option v-for="page in pages" :value="page">{{ page }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group pull-right pr-2">
                <input type="text" class="form-control" v-model="filter" placeholder="Search" @keydown="$event.stopImmediatePropagation()">
            </div>
            <datatable class="table table-responsive-md table-hover" :columns="columns" :data="list" :filter="filter" :per-page="per_page">
                <template v-slot="{ columns, row }">
                    <tr>
                        <td>{{ row.id }}</td>
                        <td>{{ row.updated_at | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY h:mm a') }}</td>
                        <td>{{ row.payment_type.name }}</td>
                        <td>{{ row.total | toCurrency }}</td>
                        <td>{{ row.payment | toCurrency }}</td>
                        <td>{{ row.balance_due | toCurrency }}</td>
                        <td class="text-nowrap">
                            <a :href="`/invoice/show/${row.id}`" class="btn btn-sm btn-outline-primary" role="button" title="view"><i class="fa fa-file-pdf-o"></i></a>
                            <a :href="`/invoice/download/pdf/${row.id}`" class="btn btn-sm btn-outline-secondary" role="button" title="download invoice"><i class="fa fa-download"></i></a>
                            <button @click="showModal(row)" class="btn btn-outline-info btn-sm" title="click to show"><i class="fa fa-folder-open-o" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                </template>
            </datatable>
            <total-entries :list="list"></total-entries>
            <div class="pull-right">
                <bootstrap-3-datatable-pager class="pagination" v-model="page" type="abbreviated" :per-page="per_page"></bootstrap-3-datatable-pager>
            </div>
        </div>
        <!-- end of vue js data table -->
        <!-- modal payment -->
        <div v-if="showModalPayment">
            <payment-modal :row="row" @closeModal="handleModalClose"></payment-modal>
        </div>
        <!-- end of modal -->
        <notifications position="bottom right"/>
    </div>
</template>


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
        handleModalClose(value) {
            let self = this;
            self.showModalPayment = value;
        },
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

        showModal: function (row) {
            let self = this;
            self.showModalPayment = true;
            self.row = row;
        },
    }
}
</script>
