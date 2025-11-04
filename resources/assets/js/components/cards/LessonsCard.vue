<template>
    <div>
        <div class="card">
            <div class="card-header bg-light">Lessons Today</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li
                        class="list-group-item d-flex justify-content-between align-items-center"
                        v-for="(lesson, index) in lessons"
                        :key="index"
                    >
                        <span>
                            <a :href="`/students/reschedule/${lesson.id}`"
                               class="d-flex justify-content-between w-100 text-decoration-none text-dark"
                            >
                                {{ lesson.title }}
                            </a>
                        </span>
                        <span>{{ lesson.status }} </span>
                        <span>{{ lesson.interval }} minutes</span>
                        <small>{{ formatDate(lesson.start_date) }} - {{ formatEndDate(lesson.end_date) }}</small>
                    </li>
                </ul>
                <div v-if="lessons.length === 0" class="text-muted mt-2">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            No lessons scheduled.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    name: 'LessonsCard',
    props: {
        lessons: {
            type: Array,
            default: () => [],
        },
    },
    methods: {
        formatDate(dateStr) {
            const options = {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            return new Date(dateStr).toLocaleString(undefined, options);
        },
        formatEndDate(dateStr) {
            const options = {
                hour: '2-digit',
                minute: '2-digit'
            };
            return new Date(dateStr).toLocaleString(undefined, options);
        }
    }
}
</script>

