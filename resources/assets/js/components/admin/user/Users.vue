<template src="./user-template.html"></template>

<script>
import TotalEntries from "../../TotalEntries";
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
                {label: 'Created At', field: 'created_at',},
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
            axios.get('/web/user')
                .then((response) => {
                    this.list = response.data;
                }).catch((error) => {
                console.log(error);
                this.$notify({
                    type: 'error',
                    title: 'Error',
                    text: 'Could not load user list.',
                    duration: 10000,
                });
            });
        },

        getUserType: function (row) {
            if (row) {
                return "&#10004;";
            } else {
                return '';
            }
        }

        // updateUser: function (id, complete) {
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
