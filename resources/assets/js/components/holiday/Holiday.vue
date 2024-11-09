<template src="./holiday-template.html"></template>

<script>
import TotalEntries from "../TotalEntries";
import {dateParse} from "@vuejs-community/vue-filter-date-parse";
import {dateFormat} from "vue-filter-date-format";

export default {
    name: 'Holidays',
    data: function () {
        return {
            classError: '',
            filter: '',
            columns: [
                {label: 'Title', field: 'title', sortable: false},
                {label: 'Start Date', field: 'start_date', sortable: true},
                {label: 'End Date', filterable: false, sortable: false},
                {label: 'All Day', filterable: false, sortable: false},
                {label: 'Action', filterable: false, sortable: false},
            ],
            colors: [
                {name: 'Blue', code: '#5499C7'},
                {name: 'Red', code: '#CD6155'},
                {name: 'Purple', code: '#A569BD'},
                {name: 'Green', code: '#52BE80'},
                {name: 'Yellow', code: '#F4D03F'},
                {name: 'Orange', code: '#E59866'},
                {name: 'Grey', code: '#85929E'},
            ],
            edit: false,
            id: null,
            showForm: false,
            showModal: false,
            list: [],
            page: 1,
            per_page: 10,
            pages: [10, 25],
            holiday: {
                id: null,
                title: null,
                color: 'Blue',
                start_date: new Date(),
                end_date: new Date(),
                all_day: true,
            },
            error_name: '',
        }
    },

    components: {
        TotalEntries
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
    },

    mounted: function () {
        this.fetchHolidayList();
    },

    methods: {
        dateFormat,
        dateParse,
        cancelForm: function () {
            let self = this;
            self.clearErrorData();
            self.clearData();
        },

        clearErrorData: function () {
            let self = this;
            self.classError = '';
            self.error_title = '';
        },

        clearData: function () {
            let self = this;
            self.showForm = false;
            self.holiday.id = null;
            self.holiday.title = null;
            self.holiday.color = null;
            self.holiday.start_date = new Date();
            self.holiday.end_date = new Date();
            self.holiday.all_day = null
            self.edit = false;
        },

        createHoliday: function () {
            let self = this;
            let params = Object.assign({}, self.holiday);
            axios.post('/web/holiday', params)
                .then(() => {
                    self.clearData();
                    self.clearErrorData();
                    self.fetchHolidayList();
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'Date was created.',
                        duration: 10000,
                    });
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not create date.',
                        duration: 10000,
                    });
                });
        },

        deleteHoliday: function (id) {
            let self = this;
            let params = Object.assign({}, self.holiday);
            axios.delete('/web/holiday/' + id, params)
                .then(() => {
                    self.showModal = false;
                    self.fetchHolidayList();
                    this.$notify({
                        type: 'warn',
                        title: 'Deleted',
                        text: 'Date was deleted.',
                        duration: 10000,
                    });
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not delete date.',
                        duration: 10000,
                    });
                });
        },

        getErrorMessage: function (error) {
            let self = this;
            self.error_title = error.response.data.error.title;
            self.classError = 'has-error';
        },

        fetchHolidayList: function () {
            axios.get('/web/holiday')
                .then((response) => {
                    this.list = response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load dates.',
                        duration: 10000,
                    });
                });
        },

        isAllDay: function () {
            return this.holiday.all_day ? 'dateTime' : 'date';
        },

        showModalDelete: function (id) {
            let self = this;
            self.showModal = true;
            self.id = id;
        },

        showHoliday: function (id) {
            let self = this;
            self.showForm = true;
            self.edit = true;
            axios.get('/web/holiday/' + id)
                .then((response) => {
                    this.holiday.id = response.data.id;
                    this.holiday.title = response.data.title;
                    this.holiday.color = response.data.color;
                    this.holiday.start_date = response.data.start_date;
                    this.holiday.end_date = response.data.end_date;
                    this.holiday.all_day = response.data.all_day;
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not show date.',
                        duration: 10000,
                    });
                });
        },

        updateHoliday: function (id) {
            let self = this;
            let params = Object.assign({}, self.holiday);
            axios.patch('/web/holiday/' + id, params)
                .then(() => {
                    self.clearData();
                    self.clearErrorData();
                    self.fetchHolidayList();
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'Updated date.',
                        duration: 10000,
                    });
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not update date.',
                        duration: 10000,
                    });
                });
        },

    },
}
</script>
