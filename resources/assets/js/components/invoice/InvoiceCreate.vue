<template>
    <div class="card">
        <div class="form-control">
            <form action="#" @submit.prevent>
                <div class="row pl-4 pt-2">
                    <div class="col-md-4">
                        <div class="form-group">
                            <select id="single-select" class="form-control" @change="getSelectedStudent($event)">
                                <option>-- Select Student --</option>
                                <option v-for="row in list" :value="row.student_id" :key="row.id">
                                    {{ row.first_name }} {{ row.last_name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div v-show="selected">
                    <div class="row pl-4">
                        <div class="col-md-6 text-left" v-if="selected">
                            <p class="font-weight-bold mb-4">Teacher Information</p>
                            <p class="mb-1"><span class="text-muted"></span><strong>{{ student.student_teacher.studio_name }}</strong></p>
                            <p class="mb-1"><span class="text-muted"></span>{{ student.student_teacher.first_name }} {{ student.student_teacher.last_name }}</p>
                            <p class="mb-1"><span class="text-muted"></span>{{ student.student_teacher.address }} {{ student.student_teacher.address_2 }}
                                <br>{{ student.student_teacher.city }}, {{ student.student_teacher.state }} {{ student.student_teacher.zip }}</p>
                            <br/>
                            <p class="mb-1"><span class="text-muted"></span>{{ student.student_teacher.email }}</p>
                            <p class="mb-1"><span class="text-muted"></span>
                                <phone-number-format v-bind:data="student.student_teacher"></phone-number-format>
                            </p>
                        </div>
                    </div>

                    <hr class="my-1">

                    <div class="row p-4 pb-1">
                        <div class="col-md-6">
                            <p class="font-weight-bold mb-4">Student Information</p>
                            <p class="mb-1">{{ student.first_name }} {{ student.last_name }}</p>
                            <p class="mb-1" v-if="selected">
                                <phone-number-format v-bind:data="student"></phone-number-format>
                            </p>
                            <p class="mb-1">{{ student.email }}</p>
                            <p class="mb-1">{{ student.parent_email }}</p>
                            <p class="mb-1">{{ student.instrument }}</p>
                        </div>
                    </div>

                    <div class="row pl-4">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="border-0 text-uppercase small font-weight-bold">Lesson Start Date</th>
                                    <th class="border-0 text-uppercase small font-weight-bold">Lesson End Date</th>
                                    <th class="border-0 text-uppercase small font-weight-bold">Completed</th>
                                    <th class="border-0 text-uppercase small font-weight-bold">Rate Type</th>
                                    <th class="border-0 text-uppercase small font-weight-bold">Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="lesson in student.lessons">
                                    <td>{{ lesson.start_date | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY h:mm a') }}</td>
                                    <td>{{ lesson.end_date | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY h:mm a') }}</td>
                                    <td>
                                        <select id="single-select" class="form-control" @change="updateLesson(lesson.id, $event)">
                                            <option :value="lesson.complete" v-model="lesson.complete">
                                                {{ lesson.complete ? "Yes" : "No" }}
                                            </option>
                                            <option :value="!lesson.complete" v-model="!lesson.complete">
                                                {{ lesson.complete ? "No" : "Yes" }}
                                            </option>
                                        </select>
                                    </td>
                                    <td>{{ lesson.billing_rate.description }}</td>
                                    <td>{{ lesson.billing_rate.amount | toCurrency }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row pl-4 pt-2 pb-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">Due Date</label>
                                <input id="normal-input"
                                       class="form-control"
                                       v-model.trim="invoice.due_date"
                                       type="date"
                                       v-on:keydown.enter.prevent>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="normal-input" class="form-control-label">Send to Email</label>
                                <div class="input-group mb-3">
                                    <input id="normal-input"
                                           class="form-control"
                                           v-model.trim="student.additional_email"
                                           type="email"
                                           placeholder="Send additional copy to this email"
                                           v-on:keydown.enter.prevent>
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex flex-row bg-dark text-white p-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="discount" class="form-control-label">Discount</label>
                                <div class="input-group mb-3">
                                    <input id="discount"
                                           class="form-control"
                                           v-model="invoice.discount"
                                           type="number"
                                           min="0"
                                           max="100"
                                           @input="updateTotal()"
                                           v-on:keydown.enter.prevent>
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="subtotal" class="form-control-label">Sub Total</label>
                                <p class="form-control-plaintext h4">{{ invoice.subtotal | toCurrency }}</p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="total" class="form-control-label">Total</label>
                                <p class="form-control-plaintext h4">{{ invoice.total | toCurrency }}</p>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="balance-due" class="form-control-label">Balance Due</label>
                                <p class="form-control-plaintext h4">{{ invoice.balance_due | toCurrency }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group pull-right pt-4 pr-md-4">
                        <button @click="clearForm()" class="btn btn-default">Cancel</button>
                        <button @click="createInvoice()" class="btn btn-primary" type="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
        <notifications position="bottom right"/>
    </div>
</template>


<script>
import PhoneNumberFormat from "../PhoneNumberFormat.vue";

export default {
    data() {
        return {
            // classError: false,
            list: [],
            student: [],
            selected: false,
            lesson: {
                id: null,
                complete: false,
                start_date: null,
                end_date: null,
            },
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
        createInvoice: function () {
            let self = this;
            let lessons = [];
            this.student.lessons.forEach((lesson) => lessons.push(lesson.id));
            self.invoice.student_id = self.student.id;
            self.invoice.teacher_id = self.student.student_teacher.teacher_id;
            self.invoice.lesson_id = lessons.toString();
            console.log(self.invoice);
            // let params = Object.assign({}, self.invoice);
            // axios.post('/web/invoice-post', params)
            //     .then(() => {
            //         this.$notify({
            //             type: 'success',
            //             title: 'Success',
            //             text: 'Invoice created.',
            //             duration: 10000,
            //         });
            //     })
            //     .catch((error) => {
            //         self.getErrorMessage(error);
            //         this.$notify({
            //             type: 'error',
            //             title: 'Error',
            //             text: 'Could not create invoice.',
            //             duration: 10000,
            //         });
            //     });
        },

        updateTotal: function () {
            let subTotal = this.invoice.subtotal;
            let discount = subTotal * (this.invoice.discount / 100);
            this.invoice.total = subTotal - discount;
            this.invoice.balance_due = subTotal - discount;
        },

        calculateTotal: function () {
            // TODO: need logic for different billing rate situations
            this.student.lessons.map((i) => {
                this.invoice.discount = 0;
                this.invoice.total = i.billing_rate.amount;
                this.invoice.subtotal = i.billing_rate.amount;
                this.invoice.balance_due = i.billing_rate.amount;
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
            self.invoice.discount = null;
            self.invoice.subtotal = null;
            self.invoice.total = null;
            self.invoice.balance_due = null;
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
                    this.student = response.data;
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
