<template src="./teacher-template.html"></template>

<style>
/*@import '/webapp/css/stylesheet.css';*/
</style>

<script>

import TotalEntries from "../../TotalEntries";

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
        TotalEntries
    },

    methods: {
        formatPhoneNumber: function (row) {
            if (row.phone && row.phone.length === 10) {
                return row.phone.replace(/[^0-9]/g, '').replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
            } else if (row.phone && row.phone.length === 11) {
                return row.phone.replace(/[^0-9]/g, '').replace(/(\d{1})(\d{3})(\d{3})(\d{4})/, '$1-$2-$3-$4');
            } else {
                return row.phone;
            }
        },

        fetchTeacherList: function () {
            axios.get('/web/teacher')
                .then((response) => {
                    this.list = response.data;
                }).catch((error) => {
                console.log(error);
            });
        },

        // updateTeacher: function (id, complete) {
        //     let self = this;
        //     self.lesson.id = id;
        //     self.lesson.complete = !complete;
        //     let params = Object.assign({}, self.lesson);
        //
        //     axios.patch('lessons/update/' + id, params)
        //         .then(function () {
        //             self.fetchLessonList();
        //         })
        //         .catch(function (error) {
        //             self.fetchLessonList();
        //             console.log(error);
        //         });
        // },
    },
}
</script>
