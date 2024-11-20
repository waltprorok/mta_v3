<template src="./profile-template.html"></template>

<script>
import PhoneNumberFormat from "../../PhoneNumberFormat";
import {dateParse} from "@vuejs-community/vue-filter-date-parse";
import {dateFormat} from "vue-filter-date-format";

export default {
    props: ['student'],
    data() {
        return {
            profile: {},
        }
    },

    components: {
        PhoneNumberFormat
    },

    mounted() {
        this.setProfile();
    },

    methods: {
        dateFormat,
        dateParse,
        setProfile() {
            this.profile = this.student;
        },

        hasPhoto() {
            return this.profile.id !== null && this.profile.photo !== null;
        },

        getProfileImg() {
            return this.profile.photo ? '/storage/student/' + this.profile.photo : '/webapp/img/avatar.jpeg';
        },
    },
}
</script>
