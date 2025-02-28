<template src="./user-template.html"></template>

<script>
import TotalEntries from "../../../TotalEntries";
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
                {label: 'First Name', field: 'first_name',},
                {label: 'Last Name', field: 'last_name',},
                {label: 'Email', field: 'email',},
                {label: 'Admin', field: 'admin',},
                {label: 'Teacher', field: 'teacher',},
                {label: 'Student', field: 'student',},
                {label: 'Parent', field: 'parent',},
                {label: 'Active', field: 'is_active',},
                {label: 'Created At', field: 'created_at',},
                {label: 'Action', filterable: false},
            ],
            user: {
                id: null,
                first_name: null,
                last_name: null,
                email: null,
                admin: null,
                teacher: null,
                student: null,
                parent: null,
                terms: null,
                is_active: null,
            },
        }
    },

    mounted: function () {
        this.fetchUserList();
    },

    components: {
        TotalEntries
    },

    methods: {
        dateFormat,
        dateParse,
        fetchUserList: function () {
            axios.get('/web/users')
                .then((response) => {
                    this.list = response.data;
                }).catch((error) => {
                console.log(error);
                this.$notify({
                    type: 'error',
                    title: 'Error',
                    text: 'Could not load users.',
                    duration: 10000,
                });
            });
        },
        isActiveIcon: function (row) {
            return row ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>';
        },
        isUserType: function (row) {
            return row ? '<i class="fa fa-check"></i>' : '';
        },
    },
}
</script>
