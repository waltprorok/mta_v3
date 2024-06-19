<template>
    <div class="card">
        <!-- vue js data table -->
        <div class="form-control">
            <form action="#" @submit.prevent="createInvoice()">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="single-select">Students</label>
                            <select id="single-select" class="form-control" @change="getSelectedStudent($event)">
                                <option>-- Select --</option>
                                <option v-for="row in list" :value="row.student_id">
                                    {{ row.first_name }} {{ row.last_name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div v-show="selected">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">First Name</label>
                                <input id="normal-input" class="form-control" v-model.trim="student.first_name" type="text">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">Last Name</label>
                                <input id="normal-input" class="form-control" v-model.trim="student.last_name" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">Email</label>
                                <input id="normal-input" class="form-control" v-model.trim="student.email" type="email">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">Parent Email</label>
                                <input id="normal-input" class="form-control" v-model.trim="student.parent_email" type="email">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">Phone</label>
                                <input id="normal-input" class="form-control" v-model.trim="student.phone" type="text">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">Date of Birth</label>
                                <input id="normal-input" class="form-control" v-model.trim="student.date_of_birth" type="date">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">Instrument</label>
                                <input id="normal-input" class="form-control" v-model.trim="student.instrument" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">Lesson Start Date</label>
                                <input id="normal-input" class="form-control" v-for="lesson in student.lessons" v-model.trim="lesson.start_date" type="text">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">Lesson End Date</label>
                                <input id="normal-input" class="form-control" v-for="lesson in student.lessons" v-model.trim="lesson.end_date" type="text">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">Completed</label>
                                <input id="normal-input" class="form-control" v-for="lesson in student.lessons" v-model.trim="lesson.complete" type="text">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">Type</label>
                                <input id="normal-input" class="form-control" v-for="lesson in student.lessons" v-model.trim="lesson.billing_rate.description" type="text">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">Amount</label>
                                <input id="normal-input" class="form-control" v-for="lesson in student.lessons" v-model.trim="lesson.billing_rate.amount" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">Due Date</label>
                                <input id="normal-input" class="form-control" v-model="invoice.due_date" type="date">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">Adjustments</label>
                                <input id="normal-input" class="form-control" v-model="invoice.adjustments" type="number">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">Sub Total</label>
                                <input id="normal-input" class="form-control" v-model="invoice.subtotal" type="number">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">Discount</label>
                                <input id="normal-input" class="form-control" v-model="invoice.discount" type="number">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">Total</label>
                                <input id="normal-input" class="form-control" v-model="invoice.total" type="number">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">Balance Due</label>
                                <input id="normal-input" class="form-control" v-model="invoice.balance_due" type="number">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="form-group pull-right">
                    <button @click="clearForm()" class="btn btn-default">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <notifications position="bottom right"/>
    </div>
</template>


<script>
// import {dateParse} from "@vuejs-community/vue-filter-date-parse";
// import {dateFormat} from "vue-filter-date-format";

export default {
    data() {
        return {
            filter: '',
            cancelForm: '',
            columns: [
                {label: 'Completed', field: 'complete',},
                {label: 'Status', field: 'end_date',},
                {label: 'Name', field: 'title',},
                {label: 'Start Date', field: 'start_date',},
                {label: 'End Date', field: 'end_date',},
                {label: 'Duration', field: 'interval',},
            ],
            classError: false,
            list: [],
            student: [],
            selected: false,
            todayLesson: false,
            pastLesson: false,
            page: 1,
            per_page: 10,
            pages: [10, 25, 50, 100],
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
            },
        }
    },

    mounted: function () {
        this.fetchInvoiceData();
    },

    methods: {
        createInvoice: function () {
            let self = this;
            let lessons = [];
            this.student.lessons.forEach((lesson) => lessons.push(lesson.id));
            self.invoice.student_id = self.student.id;
            self.invoice.teacher_id = self.student.student_teacher.teacher_id;
            self.invoice.lesson_id = lessons.toString();
            let params = Object.assign({}, self.invoice);
            axios.post('/web/invoice-post', params)
                .then(() => {
                    // self.clearRateData()
                    // self.clearErrorData();
                    // self.fetchRateList();
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

        clearForm: function () {
            let self = this;
            self.student.first_name = null;
            self.student.last_name = null;
            self.student.email = null;
            self.student.phone = null;
            self.student.student_id = null;
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
            axios.get('/web/invoice-get-student/' + event.target.value)
                .then((response) => {
                    this.student = response.data;
                    this.selected = true;
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
    },
}

</script>
