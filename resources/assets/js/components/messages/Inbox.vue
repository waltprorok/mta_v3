<template>
    <div class="card">
        <!-- vue js data table -->
        <div class="form-control" v-cloak>
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
            <datatable class="table table-condensed table-hover" :columns="columns" :data="list" :filter="filter" :per-page="per_page">
                <template v-slot="{ columns, row }">
                    <tr @click="onRowClick(row.id)">
                        <td v-if="row.read === '1'"><span class="badge badge-success">Read</span></td>
                        <td v-if="row.read === '0'"><span class="badge badge-primary">New</span></td>
                        <td>{{ row.user_from.first_name }} {{ row.user_from.last_name }}</td>
                        <td v-text="row.user_from.email"></td>
                        <td v-text="row.subject"></td>
                        <td>{{ row.created_at | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY h:mm a') }}</td>
                    </tr>
                </template>
            </datatable>
            <total-entries v-bind:list="list"></total-entries>
            <div class="pull-right">
                <bootstrap-3-datatable-pager class="pagination" v-model="page" type="abbreviated" :per-page="per_page"></bootstrap-3-datatable-pager>
            </div>
            <notifications position="bottom right"/>
        </div>
    </div>
</template>


<script>
import TotalEntries from "../TotalEntries";
import {dateParse} from "@vuejs-community/vue-filter-date-parse";
import {dateFormat} from "vue-filter-date-format";

export default {
    data: function () {
        return {
            filter: '',
            columns: [
                {label: 'Status', field: 'read',},
                {label: 'From', field: 'user_from.first_name',},
                {label: 'Email', field: 'user_from.email',},
                {label: 'Subject', field: 'subject',},
                {label: 'Sent', field: 'created_at',},
            ],
            list: [],
            page: 1,
            per_page: 10,
            pages: [10, 25, 50, 100],
        }
    },

    components: {
        TotalEntries
    },

    mounted: function () {
        this.fetchMessageList();
    },

    methods: {
        dateFormat,
        dateParse,
        fetchMessageList: function () {
            axios.get('/web/messages/inbox')
                .then((response) => {
                    this.list = response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load message list.',
                        duration: 10000,
                    });
                });
        },

        onRowClick: function (id) {
            window.location.href = `/messages/read/${id}`
        },

    }
}

</script>
