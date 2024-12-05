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
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Not authorized.',
                        duration: 10000,
                    });
                });
        },

        cancelLesson: function () {
            if (this.lesson.status === CANCELLED && this.lesson.status_updated_at !== null) {
                return this.$notify({
                    type: 'warn',
                    title: 'Warning',
                    text: 'Can not update cancelled lesson.',
                    duration: 10000,
                });
            }
            this.disableUpdateButton = true;
            let self = this;
            let params = Object.assign({}, self.lesson);
            axios.patch('/web/lesson/cancel', params)
                .then(() => {
                    self.getData();
                    this.disableUpdateButton = false;
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'Lesson status was saved.',
                        duration: 10000,
                    });
                })
                .catch((error) => {
                    console.log(error);
                    this.disableUpdateButton = false;
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not update status.',
                        duration: 10000,
                    });
                });
        },

        isCancelSelected: function () {
            return this.lesson.status !== CANCELLED;
        },
    }
}
</script>
