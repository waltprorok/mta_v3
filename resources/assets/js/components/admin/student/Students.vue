<template src="./student-template.html"></template>

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
        TotalEntries,
        PhoneNumberFormat,
    },

    methods: {
        fetchStudentList: function () {
            axios.get('/web/student')
                .then((response) => {
                    this.list = response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load student list.',
                        duration: 10000,
                    });
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
