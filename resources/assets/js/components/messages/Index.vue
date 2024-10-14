<template>
    <div class="col-md-10 col-md-offset-1">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="messaging">
                        <div class="inbox_msg">
                            <div class="inbox_people">
                                <div class="headind_srch">
                                    <!--                                    <div class="recent_heading">-->
                                    <!--                                        <h4>Users</h4>-->
                                    <!--                                    </div>-->
                                    <div class="form-group pull-left input-group col-6">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                        <div class="input-group-prepend"></div>
                                        <select id=users class="form-control" @change="getOnChangeList($event)"
                                                v-on:keydown.enter.prevent v-cloak>
                                            <option :value="status.inbox" selected>Inbox</option>
                                            <option :value="status.active" v-if="user.teacher">Active</option>
                                            <option :value="status.leads" v-if="user.teacher">Leads</option>
                                            <option :value="status.wait_list" v-if="user.teacher">Wait List</option>
                                            <option :value="status.inactive" v-if="user.teacher">Inactive</option>
                                            <option :value="status.parent" v-if="user.teacher">Parents</option>
                                            <option :value="status.teacher" v-if="user.parent || user.student">Teacher</option>
                                        </select>
                                    </div>

                                    <div class="srch_bar">
                                        <div class="stylish-input-group">
                                            <!--                                            <input type="text" class="search-bar" placeholder="Search">-->
                                            <!--                                            <span class="input-group-addon"><button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button></span>-->
                                        </div>
                                    </div>
                                </div>
                                <div class="inbox_chat">
                                    <div v-for="(person, index) in persons" :key="index" v-if="!fromList">
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
                                    <div v-for="(person, index) in persons" :key="index" v-if="fromList">
                                        <div class="chat_list" @click="fetchConversationMessages(person.id, index)" :class="{active_chat: active_person_chat_id === index}">
                                            <div class="chat_people">
                                                <div class="chat_img"><img src="/webapp/img/avatar.jpeg" alt="avatar"></div>
                                                <div class="chat_ib">
                                                    <h5>{{ person.first_name }} {{ person.last_name }}<span
                                                        class="chat_date">{{ person.created_at | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY h:mm a') }}</span></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mesgs" v-chat-scroll="{always: false, smooth: true}">
                                <div class="msg_history" v-for="message in messages">
                                    <div class="incoming_msg" v-show="message.user_from.parent || message.user_from.student">
                                        <div class="incoming_msg_img"><img src="/webapp/img/avatar.jpeg" alt="avatar"></div>
                                        <div class="received_msg">
                                            <div class="received_withd_msg">
                                                <span class="time_date">{{ message.user_from.first_name }}</span>
                                                <p>{{ message.body }}</p>
                                                <span class="time_date">{{ message.created_at }}<span v-if="message.read">&nbsp;|&nbsp;<strong>Read</strong></span></span>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="outgoing_msg" v-show="message.user_from.teacher">
                                        <div class="sent_msg">
                                            <span class="time_date">{{ message.user_from.first_name }}</span>
                                            <p>{{ message.body }}</p>
                                            <span class="time_date">{{ message.created_at }}</span>
                                        </div>
                                    </div>
                                </div>
                                <form action="#" @submit.prevent="createMessage()">
                                    <div class="type_msg">
                                        <div class="input_msg_write">
                                            <textarea class="form-control" rows="3" v-model="message.body" placeholder="Write a message..."></textarea>
                                            <button class="btn btn-rounded btn-primary pull-right mt-2" type="submit" :class="{disabled: message.body === null}">Send</button>
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
            showDropDown: false,
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
            axios.post('/web/messages/send', params)
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
    }
}
</script>
<style>
@import "/public/webapp/css/messages.css";
</style>
