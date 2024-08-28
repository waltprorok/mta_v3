<template>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="#" @submit.prevent="createMessage()">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group" :class="error_to && classError">
                                <label for="to">To <span class="text-danger">*</span></label>
                                <select class="form-control" id="to" v-model="message.to">
                                    <option v-for="user in fromUser"
                                            :value="getUserIdValue(user)"
                                            v-on:keydown.enter.prevent>
                                        {{ showUserNameDisplay(user) }}
                                    </option>
                                </select>
                                <small>{{ error_to }}</small>
                            </div>

                            <div class="form-group" :class="error_subject && classError">
                                <label for="subject">Subject <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" v-model="message.subject" v-on:keydown.enter.prevent>
                                <small>{{ error_subject }}</small>
                            </div>

                            <div class="form-group">
                                <label for="message">Previous Message<span class="text-danger"></span></label>
                                <p class="form-control" v-html="body"></p>
                            </div>

                            <div class="form-group" :class="error_body && classError">
                                <label for="message">Reply Message <span class="text-danger">*</span></label>
                                <wysiwyg v-model="message.body"/>
                                <small>{{ error_body }}</small>
                            </div>

                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <notifications position="bottom right"/>
    </div>
</template>

<script>

export default {
    data: function () {
        return {
            message: {
                to: null,
                subject: null,
                body: null,
            },
            fromUser: [{
                id: 1,
                student_id: 1,
                teacher_id: 1,
                first_name: 'John',
                last_name: 'Nolan',
                email: 'email@example.com',
                student: true,
                teacher: false,
                parent: false,
            }],
            classError: '',
            error_to: '',
            error_subject: '',
            error_body: '',
            body: null,
            parameters: this.$route.fullPath,
        }
    },

    mounted: function () {
        this.fetchReplyMessage();
    },

    methods: {
        clearErrorData: function () {
            let self = this;
            self.classError = '';
            self.error_to = '';
            self.error_subject = '';
            self.error_body = '';
        },

        clearMessageData: function () {
            let self = this;
            self.message.to = null;
            self.message.subject = null;
            self.message.body = null;
        },

        fetchReplyMessage: function () {
            axios.get('/web' + this.parameters)
                .then((response) => {
                    this.fromUser = response.data.user;
                    this.message.subject = response.data.subject;
                    this.body = response.data.body;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load reply message.',
                        duration: 10000,
                    });
                });
        },

        getErrorMessage: function (error) {
            let self = this;
            self.error_to = error.response.data.error.to;
            self.error_subject = error.response.data.error.subject;
            self.error_body = error.response.data.error.body;
            self.classError = 'has-error';
        },

        createMessage: function () {
            let self = this;
            let params = Object.assign({}, self.message);
            axios.post('/web/messages/send', params)
                .then(() => {
                    self.clearMessageData()
                    self.clearErrorData();
                })
                .then(() => {
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'The reply message was created and emailed.',
                        duration: 10000,
                    })
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                });
        },

        getUserIdValue: function (user) {
            return user?.teacher_id ?? user?.student_teacher?.teacher_id ?? user?.id;
        },

        showUserNameDisplay: function (user) {
            if (user?.student_teacher?.first_name && user?.student_teacher?.last_name) {
                return user.student_teacher.first_name + ' ' + user.student_teacher.last_name;
            }
            if (user?.first_name && user?.last_name) {
                return user.first_name + ' ' + user.last_name;
            }
            return '';
        },
    },
}

</script>
<style>
@import "~vue-wysiwyg/dist/vueWysiwyg.css";
</style>
