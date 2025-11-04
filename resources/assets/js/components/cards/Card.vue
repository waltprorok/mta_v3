<template>
    <div class="col-md-3 mb-3">
        <div class="card shadow-sm p-3 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column justify-content-center">
                    <div class="h4 font-weight-bold mb-1">{{ count }}</div>
                    <div class="text-muted">{{ title }}</div>
                </div>
                <div class="d-flex flex-column align-items-center text-muted">
                    <div class="h2 text-muted mb-1">
                        <i :class="icon" aria-hidden="true"></i>
                    </div>
                    <div class="small">{{ dates }}</div>
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
            type: [Number, String],
            required: true,
            validator: (value) => {
                return (
                    typeof value === 'string' ||
                    (typeof value === 'number' && Number.isInteger(value))
                );
            }
        },
        title: {
            type: String,
            required: true,
        },
        icon: {
            type: String,
            required: true,
        },
        dates: {
            type: String,
            default: null,
        }
    }
}
</script>
