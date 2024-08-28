<template>
    <div>
        <div class="col-md-8">
            <div class="card">
                <div class="form-control">
                    <form action="#" @submit.prevent="createMessage()">
                        <div class="row mt-2">
                            <div class="col-md-10">
                                <div class="form-group pull-left input-group col-4" v-if="showDropDown">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                    <div class="input-group-prepend"></div>
                                    <select id=users class="form-control" @change="getOnChangeList($event)"
                                            v-on:keydown.enter.prevent>
                                        <option :value="status.active" selected>Active</option>
                                        <option :value="status.leads">Leads</option>
                                        <option :value="status.wait_list">Wait List</option>
                                        <option :value="status.inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group" :class="error_to && classError">
                                <label for="to">To <span class="text-danger">*</span></label>
                                <select class="form-control" id="to" v-model="message.to"
                                        v-on:keydown.enter.prevent>
                                    <option v-for="user in users" :value="getUserIdValue(user)">
                                        {{ showUserNameDisplay(user) }}
                                    </option>
                                </select>
                                <small>{{ error_to }}</small>
                            </div>
                            <div class="form-group" :class="error_subject && classError">
                                <label for="subject">Subject <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter subject" v-model="message.subject"
                                       v-on:keydown.enter.prevent>
                                <small>{{ error_subject }}</small>
                            </div>
                            <div class="form-group" :class="error_message && classError">
                                <label for="message">Message <span class="text-danger">*</span></label>
                                <wysiwyg v-model="message.body"/>
                                <small>{{ error_message }}</small>
                            </div>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <notifications position="bottom right"/>
    </div>
</template>

<script>

export default {
    data: function () {
        return {
            users: [],
            message: {
                to: null,
                subject: null,
                body: null,
            },
            classError: '',
            error_to: '',
            error_subject: '',
            error_message: '',
            showDropDown: false,
            status: {
                active: 1,
                leads: 3,
                wait_list: 2,
                inactive: 4,
            }
        }
    },

    mounted: function () {
        this.fetchUsersList();
    },

    methods: {
        clearErrorData: function () {
            let self = this;
            self.classError = '';
            self.error_to = '';
            self.error_subject = '';
            self.error_message = '';
        },

        clearMessageData: function () {
            let self = this;
            self.message.to = null;
            self.message.subject = null;
            self.message.body = null;
        },

        createMessage: function () {
            let self = this;
            let params = Object.assign({}, self.message);
            axios.post('/web/messages/send', params)
                .then(() => {
                    self.clearMessageData()
                    self.clearErrorData();
                    self.fetchUsersList();
                })
                .then(() => {
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'The message was created and emailed.',
                        duration: 10000,
                    })
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                });
        },

        fetchUsersList: function () {
            axios.get('/web/messages/status/1')
                .then((response) => {
                    this.users = response.data.users;
                    this.showDropDown = response.data.teacher;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load users.',
                        duration: 10000,
                    });
                });
        },

        getErrorMessage: function (error) {
            let self = this;
            self.error_to = error.response.data.error.to;
            self.error_subject = error.response.data.error.subject;
            self.error_message = error.response.data.error.message;
            self.classError = 'has-error';
        },

        getOnChangeList: function (event) {
            this.clearErrorData();
            this.clearMessageData();
            axios.get('/web/messages/status/' + event.target.value)
                .then((response) => {
                    this.list = response.data.users;
                    this.showDropDown = response.data.teacher;
                }).catch((error) => {
                console.log(error);
                this.$notify({
                    type: 'error',
                    title: 'Error',
                    text: 'Could not load users list.',
                    duration: 10000,
                });
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
    }
}
</script>
<style>
@import "~vue-wysiwyg/dist/vueWysiwyg.css";
</style>

