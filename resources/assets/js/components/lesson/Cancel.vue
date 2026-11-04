<template src="./cancel-template.html"></template>

<script>
import PhoneNumberFormat from "../PhoneNumberFormat";
import {dateFormat} from "vue-filter-date-format";
import {dateParse} from "@vuejs-community/vue-filter-date-parse";
const CANCELLED = 'Cancelled';

export default {
    name: 'Cancel',
    data() {
        return {
            disableUpdateButton: false,
            lesson: {
                title: null,
                start_date: '',
                end_date: '',
                start_time: '',
                end_time: null,
                status: null,
                status_updated_at: '',
            },
            statuses: [
                {name: CANCELLED, value: CANCELLED},
            ],
            student: {
                first_name: '',
                last_name: '',
                email: '',
                phone: ''
            },
            selectedStatus: null,
        }
    },

    components: {
        PhoneNumberFormat,
    },

    mounted: function () {
        this.getData();
    },

    methods: {
        dateFormat,
        dateParse,
        getData: function () {
            let parameters = this.$route.fullPath;
            let id = parameters.split('/').pop();
            axios.get('/web/lesson/get/' + id)
                .then((response) => {
                    this.lesson = response.data.lesson;
                    this.student = response.data.lesson.student;
                    this.selectedStatus = response.data.lesson.status;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Not authorized.',
                        duration: 6000,
                    });
                });
        },

        cancelLesson: function () {
            if (this.lesson.status === CANCELLED) {
                return this.$notify({
                    type: 'warn',
                    title: 'Warning',
                    text: 'Lesson is already cancelled.',
                    duration: 6000,
                });
            }

            if (this.selectedStatus !== CANCELLED) {
                return;
            }

            this.disableUpdateButton = true;

            const params = {
                ...this.lesson,
                status: this.selectedStatus,
            };

            axios.patch('/web/lesson/cancel', params)
                .then(() => {
                    this.getData();

                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'Lesson was cancelled.',
                        duration: 6000,
                    });
                })
                .catch((error) => {
                    console.error(error);

                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not cancel lesson.',
                        duration: 6000,
                    });
                })
                .finally(() => {
                    this.disableUpdateButton = false;
                });
        },

        isCancelSelected: function () {
            return this.selectedStatus !== CANCELLED ||
                this.lesson.status === CANCELLED;
        },
    }
}
</script>
