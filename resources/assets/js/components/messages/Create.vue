<template>
    <div>
        <div class="card">
            <div class="form-control">
                <form action="#" @submit.prevent="createMessage()">
                    <div class="row mt-2">
                        <div class="col-md-10">
                            <div class="form-group pull-left input-group col-2" v-if="showDropDown">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                <div class="input-group-prepend"></div>
                                <select id=users class="form-control" @change="getOnChangeList($event)">
                                    <option value="1" selected>Active</option>
                                    <option value="3">Leads</option>
                                    <option value="2">Wait List</option>
                                    <option value="4">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="toggle-switch" data-ts-color="primary">
                                <label for="ts2" class="ts-label">Email All</label>
                                <input id="ts2" type="checkbox" hidden="hidden" v-model="message.all">
                                <label for="ts2" class="ts-helper"></label>
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group" :class="error_to && classError">
                            <label for="to">To <span class="text-danger">*</span></label>
                            <select class="form-control" id="to" v-model="message.to">
                                <option v-for="user in list" :value="user?.teacher_id ?? user?.student_teacher?.teacher_id ?? user?.id">
                                    {{ user?.student_teacher?.first_name ?? user?.first_name }} {{ user?.student_teacher?.last_name ?? user?.last_name }}
                                </option>
                            </select>
                            <small>{{ error_to }}</small>
                        </div>

                        <div class="form-group" :class="error_subject && classError">
                            <label for="subject">Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Enter subject" v-model="message.subject">
                            <small>{{ error_subject }}</small>

                        </div>

                        <div class="form-group" :class="error_message && classError">
                            <label for="message">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="6" v-model="message.message"></textarea>
                            <small>{{ error_message }}</small>

                        </div>

                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
            <notifications position="bottom right"/>
        </div>
    </div>
</template>

<script>

export default {
    data: function () {
        return {
            list: [],
            message: {
                all: false,
                to: null,
                subject: null,
                message: null,
            },
            classError: '',
            error_to: '',
            error_subject: '',
            error_message: '',
            showDropDown: false,
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
            self.message.message = null;
        },

        createMessage: function () {
            let self = this;
            let params = Object.assign({}, self.message);
            axios.post('/messages/send', params)
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
                    this.list = response.data.users;
                    console.log(response.data.teacher);
                    this.showDropDown = response.data.teacher;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load users list.',
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
    }
}
</script>


