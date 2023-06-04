<template src="./profile-template.html"></template>

<script>
import PhoneNumberFormat from "../PhoneNumberFormat";

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
        fetchProfile() {
            let parameters = this.$route.fullPath
            let id = parameters.split('/').pop()

            axios.get('/students/' + id + '/profile')
                .then((response) => {
                    this.profile = response.data;

                }).catch((error) => {
                console.log(error);
            });
        },

        hasPhoto() {
            return this.profile.id !== null && this.profile.photo !== null;
        },

        getProfileImg() {
            return this.profile.photo ? '/storage/student/' + this.profile.photo : '/webapp/imgs/avatar.jpeg';
        },
    },
}
</script>
