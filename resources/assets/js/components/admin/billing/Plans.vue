<template>
    <div class="card">
        <!-- vue js data table -->
        <div class="form-control" v-cloak>
            <div class="form-group pull-right pr-2">
                <input type="text" class="form-control" v-model="filter" placeholder="Search" @keydown="$event.stopImmediatePropagation()">
            </div>
            <datatable class="table table-hover table-responsive-md" :columns="columns" :data="list" :filter="filter" :per-page="per_page">
                <template v-slot="{ columns, row }">
                    <tr>
                        <td v-text="row.name"></td>
                        <td v-text="row.slug"></td>
                        <td v-text="row.stripe_plan"></td>
                        <td>{{ row.cost | toCurrency }}</td>
                        <td v-text="row.description"></td>
                        <td>{{ row.created_at | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY h:mm a') }}</td>
                    </tr>
                </template>
            </datatable>
            <total-entries v-bind:list="list"></total-entries>
            <notifications position="bottom right"/>
        </div>
    </div>
</template>


<script>
import TotalEntries from "../../TotalEntries";
import {dateParse} from "@vuejs-community/vue-filter-date-parse";
import {dateFormat} from "vue-filter-date-format";

export default {
    data: function () {
        return {
            filter: '',
            columns: [
                {label: 'Name', field: 'name', sortable: false},
                {label: 'Slug', field: 'slug', sortable: false},
                {label: 'Stripe Plan', field: 'stripe_plan', sortable: false},
                {label: 'Cost', field: 'cost', sortable: false},
                {label: 'Description', field: 'cost', sortable: false},
                {label: 'Created', field: 'created_at', sortable: false},
            ],
            list: [],
        }
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

    components: {
        TotalEntries
    },

    mounted: function () {
        this.fetchBillingPlanList();
    },

    methods: {
        dateFormat,
        dateParse,
        fetchBillingPlanList: function () {
            axios.get('/web/billing/plans')
                .then((response) => {
                    this.list = response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load billing plan list.',
                        duration: 10000,
                    });
                });
        },
    },
}

</script>
