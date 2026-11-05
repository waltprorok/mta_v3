<template src="./instrument-template.html"></template>

<script>
import TotalEntries from "../TotalEntries";
import {dateParse} from "@vuejs-community/vue-filter-date-parse";
import {dateFormat} from "vue-filter-date-format";

export default {
    name: 'Instruments',
    data: function () {
        return {
            classError: '',
            filter: '',
            columns: [
                {label: 'Name', field: 'name', sortable: false},
                {label: 'Created', filterable: false, sortable: false},
                {label: 'Action', filterable: false, sortable: false},
            ],
            edit: false,
            id: null,
            showForm: false,
            showModal: false,
            list: [],
            page: 1,
            per_page: 10,
            pages: [10, 25],
            instrument: {
                id: null,
                name: null,
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
        this.fetchInstrumentList();
    },

    methods: {
        dateFormat,
        dateParse,
        cancelForm: function () {
            let self = this;
            self.clearErrorData();
            self.clearInstrumentData();
        },

        clearErrorData: function () {
            let self = this;
            self.classError = '';
            self.error_name = '';
        },

        clearInstrumentData: function () {
            this.showForm = false;
            this.instrument.id = null;
            this.instrument.name = null;
            this.edit = false;
        },

        createInstrument: function () {
            let self = this;

            if (!self.validateInstrument()) {
                return;
            }

            let params = Object.assign({}, self.instrument);

            axios.post('/web/instrument', params)
                .then(() => {
                    self.clearInstrumentData();
                    self.clearErrorData();
                    self.fetchInstrumentList();

                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'Instrument was created.',
                        duration: 6000,
                    });
                })
                .catch((error) => {
                    self.getErrorMessage(error);

                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not create instrument.',
                        duration: 6000,
                    });
                });
        },

        deleteInstrument: function (id) {
            let self = this;
            let params = Object.assign({}, self.instrument);
            axios.delete('/web/instrument/' + id, params)
                .then(() => {
                    self.showModal = false;
                    self.fetchInstrumentList();
                    this.$notify({
                        type: 'warn',
                        title: 'Deleted',
                        text: 'Instrument was deleted.',
                        duration: 6000,
                    });
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not delete instrument.',
                        duration: 6000,
                    });
                });
        },

        getErrorMessage: function (error) {
            this.clearErrorData();

            if (error.response && error.response.status === 422) {
                const data = error.response.data;

                const errors = data.errors || data.error || {};

                if (errors.name) {
                    this.error_name = Array.isArray(errors.name)
                        ? errors.name[0]
                        : errors.name;

                    this.classError = 'has-error';
                }
            }
        },

        fetchInstrumentList: function () {
            axios.get('/web/instrument')
                .then((response) => {
                    this.list = response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load instruments.',
                        duration: 6000,
                    });
                });
        },

        showModalDelete: function (id) {
            let self = this;
            self.showModal = true;
            self.id = id;
        },

        showInstrument: function (id) {
            let self = this;
            self.showForm = true;
            self.edit = true;
            axios.get('/web/instrument/' + id)
                .then((response) => {
                    self.instrument.id = response.data.id;
                    self.instrument.name = response.data.name;
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not show instrument.',
                        duration: 6000,
                    });
                });
        },

        updateInstrument: function (id) {
            let self = this;

            if (!self.validateInstrument()) {
                return;
            }

            let params = Object.assign({}, self.instrument);

            axios.patch('/web/instrument/' + id, params)
                .then(() => {
                    self.clearInstrumentData();
                    self.clearErrorData();
                    self.fetchInstrumentList();

                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'Updated instrument.',
                        duration: 6000,
                    });
                })
                .catch((error) => {
                    self.getErrorMessage(error);

                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not update instrument.',
                        duration: 6000,
                    });
                });
        },

        validateInstrument: function () {
            this.clearErrorData();

            if (!this.instrument.name || !this.instrument.name.trim()) {
                this.error_name = 'Instrument name is required.';
                return false;
            }

            if (this.instrument.name.trim().length > 50) {
                this.error_name = 'Instrument name cannot exceed 50 characters.';
                return false;
            }

            return true;
        },

    },
}
</script>
