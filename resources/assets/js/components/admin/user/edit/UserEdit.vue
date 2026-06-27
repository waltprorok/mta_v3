<template src="./user-edit-template.html"></template>

<script>

import {dateParse} from "@vuejs-community/vue-filter-date-parse";
import {dateFormat} from "vue-filter-date-format";

export default {
    data: function () {
        return {
            statuses: [
                {type: 'Yes', value: 1},
                {type: 'No', value: 0},
            ],
            timezones: [
                {name: 'America/New_York', value: 'America/New_York'},
                {name: 'America/Chicago', value: 'America/Chicago'},
                {name: 'America/Denver', value: 'America/Denver'},
                {name: 'America/Los_Angeles', value: 'America/Los_Angeles'},
                {name: 'America/Anchorage', value: 'America/Anchorage'},
                {name: 'Pacific/Honolulu', value: 'Pacific/Honolulu'},
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
                trial_ends_at: null,
                timezone: null,
                is_active: null,
                pm_type: null,
                pm_last_four: null,
                stripe_id: null,
            },
            stripe_status: '',
            modelConfig: {
                type: 'string',
                mask: 'YYYY-MM-DD HH:mm:ss',
            },
        }
    },

    mounted: function () {
        this.fetchEditUser();
    },

    computed: {
        userEdit() {
            return userEdit
        },
        formatDate() {
            return this.user.trial_ends_at ? new Date(this.user.trial_ends_at) : null
        },
    },

    methods: {
        dateFormat,
        dateParse,
        fetchEditUser: function () {
            let parameters = this.$route.fullPath;
            let id = parameters.split('/').slice(-2)[0];
            axios.get('/web/user/' + id + '/edit')
                .then((response) => {
                    console.log(response.data);
                    this.user = response.data.user;
                    this.stripe_status = response.data.stripe_status;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load user.',
                        duration: 6000,
                    });
                });
        },
        getUserType: function () {
            if (this.user.admin) {
                return 'Admin';
            }
            if (this.user.teacher) {
                return 'Teacher';
            }
            if (this.user.student) {
                return 'Student';
            }
            if (this.user.parent) {
                return 'Parent';
            } else {
                return 'Needs Assigned';
            }
        },
        updateUser: function () {
            let self = this;
            let params = Object.assign({}, self.user);
            axios.patch('/web/user/edit/' + self.user.id, params)
                .then(() => {
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'Updated user.',
                        duration: 6000,
                    });
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not update user.',
                        duration: 6000,
                    });
                });
        },
    }
}
</script>
