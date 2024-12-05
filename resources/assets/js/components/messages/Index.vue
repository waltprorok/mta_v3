<template src="./messages-template.html"></template>

<script>
// Example Code Template: https://bootsnipp.com/snippets/1ea0N
import {dateFormat} from "vue-filter-date-format";
import {dateParse} from "@vuejs-community/vue-filter-date-parse";

export default {
    data: function () {
        return {
            active_person_chat_id: 0,
            fromList: false,
            messages: [],
            persons: [],
            message: {
                user_id_from: null,
                user_id_to: null,
                body: null,
                read: false,
            },
            users: [],
            user: {
                teacher: false,
                student: false,
                parent: false,
            },
            status: {
                inbox: 0,
                active: 1,
                leads: 3,
                wait_list: 2,
                inactive: 4,
                parent: 5,
                teacher: 6,
            },
        }
    },

    filters: {
        short: function (value) {
            return value.substr(0, 90) + '...';
        }
    },

    mounted: function () {
        this.fetchMessages();
    },

    methods: {
        dateFormat,
        dateParse,
        createMessage: function () {
            if (this.message.body === '') {
                this.message.body = null;
                return;
            }
            let self = this;
            let params = Object.assign({}, self.message);
            axios.post('/web/messages/store', params)
                .then(() => {
                    this.fetchConversationMessages(self.message.user_id_to, this.active_person_chat_id);
                    this.message.body = null;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not save message.',
                        duration: 10000,
                    });
                });
        },

        getOnChangeList: function (event) {
            let id = event.target.value;
            if (id === '0') {
                this.fromList = false;
                this.persons = [];
                return this.fetchMessages();
            }
            axios.get('/web/messages/status/' + id)
                .then((response) => {
                    this.fromList = true;
                    this.persons = [];
                    this.persons = response.data.persons;
                    if (this.persons) {
                        this.message.user_id_to = this.persons[0].user_id_from ?? this.persons[0].id;
                    }
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

        fetchMessages: function () {
            axios.get('/web/messages/inbox')
                .then((response) => {
                    this.persons = response.data.persons;
                    this.messages = response.data.messages;
                    this.user = response.data.user;
                    this.message.user_id_from = this.user.id;
                    if (this.persons) {
                        this.message.user_id_to = this.persons[0].user_id_from;
                    }
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load messages.',
                        duration: 10000,
                    });
                });
        },

        fetchConversationMessages: function (id, index) {
            axios.get('/web/messages/index/' + id)
                .then((response) => {
                    this.user = response.data.user;
                    this.active_person_chat_id = index;
                    this.messages = response.data.messages;
                    this.message.user_id_from = this.user.id;
                    this.message.user_id_to = id;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load messages.',
                        duration: 10000,
                    });
                });
        },

        isDisabled: function () {
            return this.message.body === null || this.message.body === '';
        },
    }
}
</script>
<style>
@import "/public/webapp/css/messages.css";
</style>
