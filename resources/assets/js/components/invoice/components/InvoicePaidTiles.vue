<template>
    <div class="row">
        <div class="col-md-3">
            <div class="card p-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <span class="h4 d-block font-weight-normal mb-2">{{ paid }}</span>
                        <span class="font-weight-light">Paid</span>
                    </div>

                    <div class="h2 text-muted">
                        <i class="fa fa-money" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <span class="h4 d-block font-weight-normal mb-2">{{ notPaid }}</span>
                        <span class="font-weight-light">Not Paid</span>
                    </div>

                    <div class="h2 text-muted">
                        <i class="fa fa-bank" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'InvoicePaidTiles',
    props: {
        paid: {
            type: Number,
            required: true,
            validator: (value) => {
                return Number.isInteger(value);
            }
        },
        notPaid: {
            type: Number,
            required: true,
            validator: (value) => {
                return Number.isInteger(value);
            }
        },
    },

}
</script>
