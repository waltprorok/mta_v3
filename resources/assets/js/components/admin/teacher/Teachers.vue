<template src="./teacher-template.html"></template>

<script>
import TotalEntries from "../../TotalEntries";
import PhoneNumberFormat from "../../PhoneNumberFormat";

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
                {label: 'Address', field: 'address', sortable: false},
                {label: 'Address 2', field: 'address_2', sortable: false},
                {label: 'City', field: 'city',},
                {label: 'State', field: 'state',},
                {label: 'Zip', field: 'zip', sortable: false},
                {label: 'Phone', field: 'phone', sortable: false},
                {label: 'Email', field: 'email', sortable: false},
            ],
            teacher: {
                id: null,
                first_name: null,
                last_name: null,
                address: null,
                address_2: null,
                city: null,
                state: null,
                zip: null,
                email: null,
                phone: null,
            },
        }
    },

    mounted: function () {
        this.fetchTeacherList();
    },

    components: {
        TotalEntries,
        PhoneNumberFormat,
    },

    methods: {
        fetchTeacherList: function () {
            axios.get('/web/teachers')
                .then((response) => {
                    this.list = response.data;
                }).catch((error) => {
                console.log(error);
                this.$notify({
                    type: 'error',
                    title: 'Error',
                    text: 'Could not load teacher list.',
                    duration: 10000,
                })
            });
        },
    },
}
</script>
