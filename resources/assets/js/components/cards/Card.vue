<template>
    <div class="col-md-3">
        <div class="card p-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <span class="h4 d-block font-weight-normal mb-2">{{ count }}</span>
                    <span class="font-weight-light">{{ title }}</span>
                </div>

                <div class="h2 text-muted">
                    <i :class="icon" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Card',
    props: {
        count: {
            type: Number,
            required: true,
            validator: (value) => {
                return Number.isInteger(value);
            }
        },
        title: {
            type: String,
            required: true,
        },
        icon: {
            type: String,
            required: true,
        }
    }
}
</script>
