<template src="./profile-template.html"></template>

<script>
import PhoneNumberFormat from "../PhoneNumberFormat";

export default {
    data() {
        return {
            profiles: [],
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
            let self = this;
            let parameters = this.$route.fullPath
            let id = parameters.split('/').pop()

            axios.get('/students/' + id + '/profile')
                .then((response) => {
                    this.profiles = response.data;
                }).catch((error) => {
                console.log(error);
            });
        },
    },
}
</script>
