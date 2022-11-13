<template>
    <div v-show="alertState" class="alert alert-success alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" @click="hideAlert()" aria-label="close">&times;</a>
        {{ toast }}
    </div>
</template>

<script>
export default {
    name: "Alert",
    data() {
        return {
            alertState: false
        }
    },
    props: {
        toast: String,
        alert: Boolean,
    },
    methods: {
        hideAlert() {
            return this.alertState = !this.alertState;
        }
    },
    computed: {
        showAlert: function() {
            return this.alertState = this.alert;
        }
    }
}

</script>

<style scoped>

</style>
