<template>
    <div>
        {{ formatPhoneNumber() }}
    </div>
</template>


<script>
export default {
    name: 'PhoneNumberFormat',
    props: {
        data: Object,
    },

    methods: {
        formatPhoneNumber: function () {
            if (this.data.phone && this.data.phone.length === 10) {
                return this.data.phone.replace(/[^0-9]/g, '').replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
            } else if (this.data.phone && this.data.phone.length === 11) {
                return this.data.phone.replace(/[^0-9]/g, '').replace(/(\d{1})(\d{3})(\d{3})(\d{4})/, '$1-$2-$3-$4');
            } else {
                return this.data.phone;
            }
        },
    },
}
</script>
