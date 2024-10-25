<template src="./invoice-create-template.html"></template>

<script>
import PhoneNumberFormat from "../PhoneNumberFormat.vue";

export default {
    data() {
        return {
            list: [],
            student: [],
            lessons: [],
            selected: false,
            lesson: {
                id: null,
                complete: false,
                start_date: null,
                end_date: null,
            },
            lastInvoice: {},
            invoice: {
                student_id: null,
                teacher_id: null,
                lesson_id: null,
                subtotal: 0,
                discount: 0,
                total: 0,
                balance_due: 0,
                payment: 0,
                adjustments: 0,
                payment_type_id: 1,
                due_date: null,
                additional_email: '',
            },
        }
    },

    computed: {
        isDisabled: function () {
            return this?.lessons?.length === 0;
        },
    },

    components: {
        PhoneNumberFormat
    },

    mounted: function () {
        this.fetchInvoiceData();
    },

    filters: {
        capitalising: function (data) {
            let capitalized = [];
            data.split(' ').forEach(word => {
                capitalized.push(
                    word.charAt(0).toUpperCase() +
                    word.slice(1).toLowerCase()
                )
            });
            return capitalized.join(' ');
        },

        toCurrency: function (value) {
            let formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            });

            return formatter.format(value);
        }
    },

    methods: {
        calculateLessonAmount: function (lesson) {
            if (lesson.billing_rate.type === 'lesson') {
                return lesson.billing_rate.amount;
            }

            if (lesson.billing_rate.type === 'hourly') {
                let lessonInterval = (lesson.interval / 60);
                return lesson.billing_rate.amount * lessonInterval;
            }

            if (lesson.billing_rate.type === 'monthly') {
                let numberOfLessons = this.lessons.length;
                return lesson.billing_rate.amount / numberOfLessons;
            }
        },

        createInvoice: function () {
            let self = this;
            let lessons = [];
            this.lessons.forEach((lesson) => lessons.push(lesson.id));
            self.invoice.student_id = self.student.id;
            self.invoice.teacher_id = self.student.get_teacher.teacher_id;
            self.invoice.lesson_id = lessons.toString();
            let params = Object.assign({}, self.invoice);
            axios.post('/web/invoice-post', params)
                .then(() => {
                    this.clearForm();
                })
                .then(() => {
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'Invoice created.',
                        duration: 10000,
                    });
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not create invoice.',
                        duration: 10000,
                    });
                });
        },

        updateTotal: function () {
            let subTotal = this.invoice.subtotal;
            let discount = subTotal * (this.invoice.discount / 100);
            this.invoice.total = subTotal - discount;
            this.invoice.balance_due = subTotal - discount;
            if (this.lastInvoice != null && this.lastInvoice.balance_due) {
                this.invoice.balance_due = Number(this.invoice.balance_due) + Number(this.lastInvoice.balance_due);
            }
        },

        calculateTotal: function () {
            this.invoice.discount = 0;
            this.lessons.map((lesson) => {
                let numberOfLessons = this.lessons.length;
                if (lesson.billing_rate.type === 'lesson') {
                    this.invoice.total = lesson.billing_rate.amount * numberOfLessons;
                    this.invoice.subtotal = lesson.billing_rate.amount * numberOfLessons;
                    this.invoice.balance_due = lesson.billing_rate.amount * numberOfLessons;
                    if (this.lastInvoice != null && this.lastInvoice.balance_due) {
                        this.invoice.balance_due = Number(this.invoice.balance_due) + Number(this.lastInvoice.balance_due);
                    }
                }

                if (lesson.billing_rate.type === 'hourly') {
                    let lessonInterval = (lesson.interval / 60);
                    this.invoice.total = lesson.billing_rate.amount * lessonInterval * numberOfLessons;
                    this.invoice.subtotal = lesson.billing_rate.amount * lessonInterval * numberOfLessons;
                    this.invoice.balance_due = lesson.billing_rate.amount * lessonInterval * numberOfLessons;
                    if (this.lastInvoice != null && this.lastInvoice.balance_due) {
                        this.invoice.balance_due = Number(this.invoice.balance_due) + Number(this.lastInvoice.balance_due);
                    }
                }

                if (lesson.billing_rate.type === 'monthly') {
                    this.invoice.total = lesson.billing_rate.amount;
                    this.invoice.subtotal = lesson.billing_rate.amount;
                    this.invoice.balance_due = Number(lesson.billing_rate.amount);
                    if (this.lastInvoice != null && this.lastInvoice.balance_due) {
                        this.invoice.balance_due = Number(this.invoice.balance_due) + Number(this.lastInvoice.balance_due);
                    }
                }
            });
        },

        clearForm: function () {
            let self = this;
            self.list = [];
            self.student.student_id = null;
            self.student.first_name = null;
            self.student.last_name = null;
            self.student.email = null;
            self.student.phone = null;
            self.student.teacher_id = null;
            self.student.lesson_id = null;
            self.student.subtotal = null;
            self.student.discount = null;
            self.student.total = null;
            self.student.balance_due = null;
            self.student.payment = null;
            self.student.adjustments = null;
            self.student.payment_type_id = null;
            self.student.due_date = null;
            self.selected = false;
            self.invoice.discount = null;
            self.invoice.subtotal = null;
            self.invoice.total = null;
            self.invoice.balance_due = null;
            self.invoice.additional_email = null;
            this.fetchInvoiceData();
        },

        fetchInvoiceData: function () {
            axios.get('/web/invoice-create/')
                .then((response) => {
                    let self = this;
                    self.list = response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load invoice.',
                        duration: 10000,
                    });
                });
        },

        getSelectedStudent: function (event) {
            this.clearForm();
            axios.get('/web/invoice-get-student/' + event.target.value)
                .then((response) => {
                    this.student = response.data.studentTeacher;
                    this.invoice.additional_email = response.data.studentTeacher.parent ? response.data.studentTeacher.parent.email : null;
                    this.lessons = response.data.lessons;
                    this.lastInvoice = response.data.lastInvoice;
                    this.selected = true;
                })
                .then(() => {
                    this.calculateTotal();
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load student.',
                        duration: 10000,
                    });
                });
        },

        hasLessons: function (student) {
            return student.lessons.length > 0;
        },

        updateLesson: function (id, event) {
            let self = this;
            self.lesson.id = id;
            self.lesson.complete = event.target.value === 'true' ? 1 : 0;
            self.lesson.start_date = event.target.inputValue;
            self.lesson.end_date = event.target.inputValue;
            let params = Object.assign({}, self.lesson);

            axios.patch('/lessons/update/' + id, params)
                .then(() => {
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'The lesson was updated.',
                        duration: 10000,
                    })
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'The lesson failed to update.',
                        duration: 10000,
                    })
                });
        },
    },
}

</script>
