<template src="./profile-template.html"></template>

<script>
import PhoneNumberFormat from "../PhoneNumberFormat";
import {dateParse} from "@vuejs-community/vue-filter-date-parse";
import {dateFormat} from "vue-filter-date-format";

export default {
    data() {
        return {
            profile: {},
        }
    },

    components: {
        PhoneNumberFormat
    },

    mounted() {
        this.fetchProfile();
    },

    methods: {
        dateFormat,
        dateParse,
        fetchProfile() {
            let parameters = this.$route.fullPath
            let id = parameters.split('/').pop()

            axios.get('/students/' + id + '/profile')
                .then((response) => {
                    this.profile = response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load student profile.',
                        duration: 10000,
                    });
                });
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
