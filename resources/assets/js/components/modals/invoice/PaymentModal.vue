<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Invoice Payment Information</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" @click="close">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Invoice ID: {{ row.id }}</p>
                            <p>Payment Type: {{ row.payment_type.name }}</p>
                            <p v-if="row.check_number">Check Number: {{ row.check_number }}</p>
                            <p v-if="row.payment_information">Payment Information: {{ row.payment_information }}</p>
                            <hr />
                            <p>Invoice Amount: {{ row.total | toCurrency }}</p>
                            <p>Discount: {{ row.discount | toCurrency }}</p>
                            <p>Amount Paid: {{ row.payment | toCurrency }}</p>
                            <p>Date: {{ row.updated_at | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY h:mm a') }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" @click="close">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    name: 'PaymentModal',
    props: {
        row: Object,
    },
    methods: {
        close() {
            this.$emit('closeModal', false);
        }
    },

    filters: {
        toCurrency: function (value) {
            let formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            });

            return formatter.format(value);
        }
    },
}
</script>
