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
                {label: 'Name', field: 'first_name',},
                {label: 'Email', field: 'email', sortable: false},
                {label: 'Role', field: 'role', sortable: false},
                {label: 'Active', field: 'is_active',},
                {label: 'Created', field: 'created_at', sortable: false},
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
                    duration: 6000,
                });
            });
        },
        isActiveIcon: function (is_active) {
            return is_active ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>';
        },
        getRoleBadges(user) {
            const roles = [];

            if (user.admin) {
                roles.push('<span class="badge bg-danger text-white p-1">Admin</span>');
            }
            if (user.teacher) {
                roles.push('<span class="badge bg-primary text-white p-1">Teacher</span>');
            }
            if (user.student) {
                roles.push('<span class="badge bg-success text-white p-1">Student</span>');
            }
            if (user.parent) {
                roles.push('<span class="badge bg-warning text-white p-1">Parent</span>');
            }

            return roles.join(' ');
        }
    },
}
</script>
