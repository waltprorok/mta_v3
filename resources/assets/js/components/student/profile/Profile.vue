<template src="./profile-template.html"></template>

<script>
import PhoneNumberFormat from "../../PhoneNumberFormat";
import { dateParse } from "@vuejs-community/vue-filter-date-parse";
import { dateFormat } from "vue-filter-date-format";

export default {
    name: 'Profile',

    components: {
        PhoneNumberFormat
    },

    props: {
        student: {
            type: Object,
            required: true
        }
    },

    computed: {
        profile() {
            return this.student || {};
        },

        fullName() {
            return [this.profile.first_name, this.profile.last_name]
                .filter(Boolean)
                .join(' ');
        },

        profileImage() {
            return this.profile.photo
                ? `/storage/student/${this.profile.photo}`
                : '/webapp/img/avatar.jpeg';
        },

        nextLesson() {
            return this.profile.has_one_future_lesson || null;
        }
    },

    filters: {
        dateParse,
        dateFormat
    }
};
</script>
