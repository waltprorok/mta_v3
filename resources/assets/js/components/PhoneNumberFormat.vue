<template>
    <div>
        {{ formatPhoneNumber() }}
    </div>
</template>


<script>
export default {
    name: 'PhoneNumberFormat',
    props: {
        data: String,
        required: true,
    },
    methods: {
        formatPhoneNumber: function () {
            if (! this.data) return '';

            const digits = this.data;

            if (digits.length === 10) {
                return digits.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
            } else if (digits.length === 11) {
                return digits.replace(/(\d{1})(\d{3})(\d{3})(\d{4})/, '$1-$2-$3-$4');
            } else {
                return this.data.phone;
            }
        },
    },
}
</script>
