<template>
    <div class="col-md-10 col-md-offset-1">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="messaging">
                        <div class="inbox_msg">
                            <div class="inbox_people">
                                <div class="headind_srch">
                                    <div class="recent_heading">
                                        <h4>Recent</h4>
                                    </div>
                                    <div class="srch_bar">
                                        <div class="stylish-input-group">
                                            <input type="text" class="search-bar" placeholder="Search">
                                            <span class="input-group-addon"><button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="inbox_chat">
                                    <div v-for="(person, index) in persons" :key="index">
                                        <div class="chat_list" @click="fetchConversationMessages(person.user_id_from, index)" :class="{active_chat: active_person_chat_id === index}">
                                            <div class="chat_people">
                                                <div class="chat_img"><img src="/webapp/img/avatar.jpeg" alt="avatar"></div>
                                                <div class="chat_ib">
                                                    <h5>{{ person.user_from.first_name }} {{ person.user_from.last_name }}<span class="chat_date">{{ person.created_at }}</span></h5>
                                                    <p>{{ person.body | short }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mesgs">
                                <div class="msg_history" v-for="message in messages">
                                    <div class="incoming_msg" v-show="message.user_from.parent || message.user_from.student">
                                        <div class="incoming_msg_img"><img src="/webapp/img/avatar.jpeg" alt="avatar"></div>
                                        <div class="received_msg">
                                            <div class="received_withd_msg">
                                                <span class="time_date">{{ message.user_from.first_name }}</span>
                                                <p>{{ message.body }}</p>
                                                <span class="time_date">{{ message.created_at }}</span></div>
                                        </div>
                                    </div>
                                    <div class="outgoing_msg" v-show="message.user_from.teacher">
                                        <div class="sent_msg">
                                            <span class="time_date">{{ message.user_from.first_name }}</span>
                                            <p>{{ message.body }}</p>
                                            <span class="time_date">{{ message.created_at }}</span></div>
                                    </div>
                                </div>
                                <form action="#" @submit.prevent="createMessage()">
                                    <div class="type_msg">
                                        <div class="input_msg_write">
                                            <textarea class="form-control" rows="4" v-model="message.body" placeholder="Type a message..."></textarea>
<!--                                            <input type="text" class="write_msg" v-model="message.body" placeholder="Type a message..."/>-->
                                            <button class="btn btn-rounded btn-primary pull-right mt-2" type="submit">Send</button>
<!--                                            <button class="msg_send_btn" type="submit"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>-->
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <notifications position="bottom right"/>
    </div>
</template>
<script>
// Example Code Template: https://bootsnipp.com/snippets/1ea0N
import {dateFormat} from "vue-filter-date-format";
import {dateParse} from "@vuejs-community/vue-filter-date-parse";

export default {
    data: function () {
        return {
            active_person_chat_id: null,
            messages: [],
            persons: [],
            message: {
                user_id_from: null,
                user_id_to: null,
                body: null,
                read: false,
            },

            user: null,
        }
    },

    filters: {
        short: function (value) {
            return value.substr(0, 80) + '...';
        }
    },

    mounted: function () {
        this.fetchMessages();
    },

    methods: {
        dateFormat,
        dateParse,

        createMessage: function () {
            let self = this;
            let params = Object.assign({}, self.message);
            console.log(params)
            axios.post('/web/messages/send', params)
                .then(() => {
                    this.fetchMessages();
                    this.message = {};
                })
                .then(() => {
                    this.$notify({
                        type: 'success',
                        title: 'Success',
                        text: 'The message was created.',
                        duration: 5000,
                    })
                })
                .catch((error) => {
                    self.getErrorMessage(error);
                });
        },

        fetchMessages: function () {
            axios.get('/web/messages/inbox')
                .then((response) => {
                    this.persons = response.data.persons;
                    this.messages = response.data.messages;
                    this.user = response.data.user;
                    this.message.user_id_from =this.user;
                    this.message.user_id_to = this.persons[0].user_id_from;

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
                    this.active_person_chat_id = index
                    this.messages = [];
                    this.messages = response.data.messages;
                    this.user = null;
                    this.user = response.data.user;
                    this.message.user_id_from = this.user;
                    this.message.user_id_to = id;
                    console.log(this.message);
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
    }
}
</script>
<style>
@import "/public/webapp/css/messages.css";
</style>
