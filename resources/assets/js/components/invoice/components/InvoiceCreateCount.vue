<template>
    <div class="col-md-3">
        <div class="card p-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <span class="h4 d-block font-weight-normal mb-2">{{ needInvoicedCount }}</span>
                    <span class="font-weight-light">Need Invoiced</span>
                </div>

                <div class="h2 text-muted">
                    <i class="fa fa-file-text" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'InvoiceCreateCount',
    props: {
        needInvoicedCount: {
            type: Number,
            required: true,
            validator: (value) => {
                return Number.isInteger(value);
            }
        }
    }
}
</script>
