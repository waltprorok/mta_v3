<template>
    <div class="card">
        <div class="card-header bg-light">{{ title }}</div>
        <div class="list-group list-group-flush">
            <div v-if="lessons.length === 0" class="list-group-item text-muted text-center">
                No lessons scheduled.
            </div>
            <a
                v-else
                v-for="(lesson, index) in lessons"
                :key="index"
                :href="`/students/reschedule/${lesson.id}`"
                class="list-group-item list-group-item-action"
            >
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-1">
                    <div class="text-left">
                        <div class="font-weight-bold">{{ lesson.title }}</div>
                        <div class="text-muted small">
                            Scheduled · {{ lesson.interval }} minutes
                        </div>
                    </div>

                    <div class="text-right small text-nowrap">
                        <div class="font-weight-bold">
                            {{ formatDay(lesson.start_date) }}
                        </div>
                        <div class="text-muted">
                            {{ formatTimeRange(lesson.start_date, lesson.end_date) }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</template>

<script>
export default {
    name: 'LessonsCard',
    props: {
        title: {
            type: String,
            required: true,
        },
        lessons: {
            type: Array,
            default: () => []
        }
    },
    methods: {
        formatDay(dateStr) {
            return new Date(dateStr).toLocaleDateString(undefined, {
                weekday: 'short',
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        },
        formatTimeRange(start, end) {
            const s = new Date(start).toLocaleTimeString(undefined, {
                hour: '2-digit',
                minute: '2-digit'
            });
            const e = new Date(end).toLocaleTimeString(undefined, {
                hour: '2-digit',
                minute: '2-digit'
            });
            return `${s} - ${e}`;
        }
    }
};
</script>


