<template>
    <transition name="modal">
        <div class="modal-mask" @click.self="close">
            <div class="modal-wrapper">
                <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="payment-modal-title">
                    <div class="modal-content">

                        <!-- Header -->
                        <div class="modal-header">
                            <button type="button"
                                    class="close"
                                    aria-label="Close"
                                    @click="close">
                                <span aria-hidden="true">&times;</span>
                            </button>

                            <h4 id="payment-modal-title" class="modal-title">
                                <i class="fa fa-credit-card"></i>
                                Payment Details
                            </h4>
                        </div>

                        <div class="modal-body">

                            <!-- Payment Summary -->
                            <div class="payment-summary text-center">
                                <div class="text-muted">
                                    Amount Paid
                                </div>

                                <h2 class="payment-amount">
                                    {{ row.payment | toCurrency }}
                                </h2>

                                <span class="label"
                                      :class="Number(row.balance_due) <= 0 ? 'label-success' : 'label-warning'">
                                    {{ Number(row.balance_due) <= 0 ? 'Paid in Full' : 'Balance Remaining' }}
                                </span>

                                <div class="payment-date text-muted">
                                    {{ row.updated_at | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY h:mm a') }}
                                </div>
                            </div>

                            <!-- Payment Information -->
                            <h5 class="section-title">
                                Payment Information
                            </h5>

                            <div class="detail-row">
                                <span>Invoice</span>
                                <strong>#{{ row.id }}</strong>
                            </div>

                            <div class="detail-row">
                                <span>Payment Method</span>
                                <strong>{{ row.payment_type ? row.payment_type.name : '—' }}</strong>
                            </div>

                            <div v-if="row.check_number" class="detail-row">
                                <span>Check Number</span>
                                <strong>{{ row.check_number }}</strong>
                            </div>

                            <div v-if="row.payment_information" class="detail-row">
                                <span>Additional Information</span>
                                <strong class="payment-notes">
                                    {{ row.payment_information }}
                                </strong>
                            </div>

                            <!-- Invoice Summary -->
                            <h5 class="section-title">
                                Invoice Summary
                            </h5>

                            <div class="detail-row">
                                <span>Invoice Amount</span>
                                <strong>{{ row.total | toCurrency }}</strong>
                            </div>

                            <div class="detail-row">
                                <span>Discount</span>
                                <strong>{{ row.discount | toCurrency }}</strong>
                            </div>

                            <div class="detail-row">
                                <span>Amount Paid</span>
                                <strong class="text-success">
                                    {{ row.payment | toCurrency }}
                                </strong>
                            </div>

                            <div class="detail-row balance-row">
                                <span>Remaining Balance</span>
                                <strong :class="{ 'text-danger': Number(row.balance_due) > 0 }">
                                    {{ row.balance_due | toCurrency }}
                                </strong>
                            </div>

                        </div>

                        <!-- Footer -->
                        <div class="modal-footer">
                            <button type="button"
                                    class="btn btn-default"
                                    @click="close">
                                Close
                            </button>
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

    mounted() {
        window.addEventListener('keydown', this.handleKeydown);
    },

    beforeDestroy() {
        window.removeEventListener('keydown', this.handleKeydown);
    },

    methods: {
        close() {
            this.$emit('closeModal', false);
        },

        handleKeydown(event) {
            if (event.key === 'Escape') {
                this.close();
            }
        }
    },

    filters: {
        toCurrency: function (value) {
            let formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            });

            return formatter.format(value || 0);
        }
    },
}
</script>

<style scoped>
.modal-dialog {
    max-width: 500px;
}

.modal-header {
    padding: 18px 22px;
}

.modal-title {
    font-size: 18px;
    font-weight: 600;
}

.modal-title .fa {
    margin-right: 8px;
    color: #3498db;
}

.modal-body {
    padding: 24px;
}

.payment-summary {
    padding: 20px 15px 25px;
    margin-bottom: 25px;
    background: #f8f9fa;
    border-radius: 6px;
}

.payment-amount {
    margin: 10px 0 15px;
    font-size: 36px;
    font-weight: 600;
    color: #28a745;
}

.payment-summary .label {
    display: inline-block;
    padding: 6px 12px;
    font-size: 12px;
    border-radius: 20px;
}

.payment-date {
    margin-top: 15px;
    font-size: 13px;
}

.section-title {
    margin: 25px 0 12px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e9ecef;
    font-size: 14px;
    font-weight: 600;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 15px;
    padding: 10px 0;
    font-size: 14px;
}

.detail-row > span {
    color: #777;
}

.detail-row > strong {
    text-align: right;
}

.payment-notes {
    max-width: 60%;
    overflow-wrap: anywhere;
}

.balance-row {
    margin-top: 10px;
    padding-top: 15px;
    border-top: 1px solid #ddd;
    font-size: 15px;
}

.modal-header {
    position: relative;
    padding: 18px 22px;
}

.modal-header .close {
    position: absolute;
    top: 0;
    right: 22px;
    margin: 0;
    float: none;
    font-size: 24px;
    line-height: 1;
    opacity: 0.5;
}

.modal-header .close:hover {
    opacity: 1;
}

.modal-footer {
    padding: 15px 22px;
}
</style>
