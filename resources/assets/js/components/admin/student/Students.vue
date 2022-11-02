<template src="./student-template.html"></template>

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
                {label: 'Photo', field: 'photo', sortable: false},
                {label: 'First Name', field: 'first_name',},
                {label: 'Last Name', field: 'last_name',},
                {label: 'Phone', field: 'phone', sortable: false},
                {label: 'Email', field: 'email',},
                {label: 'Instrument', field: 'instrument',},
                {label: 'Status', field: 'status',},


            ],
            student: {
                id: null,
                first_name: null,
                last_name: null,
                phone: null,
                email: null,
                instrument: null,
                status: null,
            },
        }
    },

    mounted: function () {
        this.fetchStudentList();
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

        fetchStudentList: function () {
            axios.get('/web/student')
                .then((response) => {
                    this.list = response.data;
                }).catch((error) => {
                console.log(error);
            });
        },

        getStatus: function (row) {
            switch (parseInt(row.status)) {
                case 1:
                    return 'Active';
                case 2:
                    return 'Waitlist';
                case 3:
                    return 'Lead';
                case 4:
                    return 'Active';
                default:
                    return 'this';
            }
        }

        // updateTeacher (id, complete) {
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
