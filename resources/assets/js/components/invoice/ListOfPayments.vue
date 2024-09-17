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
                            <button @click="showModal(row)" class="btn btn-outline-info btn-sm" title="click to show"><i class="fa fa-folder-open-o" aria-hidden="true"></i></button>
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
        <!-- modal payment -->
        <div v-if="showModalPayment">
            <transition name="modal">
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Invoice Payment Information</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" @click="showModalPayment=false">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Invoice ID: {{ row.id }}</p>
                                    <p>Payment Type: {{ row.payment_type.name }}</p>
                                    <p v-if="row.check_number">Check Number: {{ row.check_number }}</p>
                                    <p>Payment Information: {{ row.payment_information }}</p>
                                    <hr />
                                    <p>Invoice Amount: {{ row.total | toCurrency  }}</p>
                                    <p>Discount: {{ row.discount | toCurrency  }}</p>
                                    <p>Amount Paid: {{ row.payment | toCurrency }}</p>
                                    <p>Date: {{ row.updated_at | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY h:mm a') }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" @click="showModalPayment=false">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
        <!-- end of modal -->
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
            showModalPayment: false,
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

        showModal: function (row) {
            let self = this;
            self.showModalPayment = true;
            self.row = row;
        },
    }
}
</script>
