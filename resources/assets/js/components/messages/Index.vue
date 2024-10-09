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
                                    <div v-for="person in persons">
                                        <!--                                        <div class="chat_list " @click="$set(person, 'active_chat', !person.active)" :class="active">-->
                                        <div class="chat_list" @click="fetchConversationMessages(person.user_id_from)">
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
                                <div class="type_msg">
                                    <div class="input_msg_write">
                                        <input type="text" class="write_msg" placeholder="Type a message"/>
                                        <button class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
//https://bootsnipp.com/snippets/1ea0N
import {dateFormat} from "vue-filter-date-format";
import {dateParse} from "@vuejs-community/vue-filter-date-parse";

export default {
    data: function () {
        return {
            active: '',
            messages: [],
            persons: [],
            message: {
                to: null,
                subject: null,
                body: null,
            },
            fromUser: [{
                id: 1,
                teacher_id: 1,
                first_name: 'John',
                last_name: 'Nolan',
                email: 'email@example.com',
            }],
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
        activeChat: function (item) {
            return this.active = 'active_chat';
        },

        fetchMessages: function () {
            axios.get('/web/messages/inbox')
                .then((response) => {
                    this.persons = response.data.persons;
                    this.messages = response.data.messages;
                    this.user = response.data.user;

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

        fetchConversationMessages: function (id) {
            axios.get('/web/messages/index/' + id)
                .then((response) => {
                    this.messages = [];
                    this.messages = response.data.messages;
                    this.user = null;
                    this.user = response.data.user;
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
.container {
    max-width: 1170px;
    margin: auto;
}

img {
    max-width: 100%;
}

.inbox_people {
    background: #f8f8f8 none repeat scroll 0 0;
    float: left;
    overflow: hidden;
    width: 40%;
    border-right: 1px solid #c4c4c4;
}

.inbox_msg {
    border: 1px solid #c4c4c4;
    clear: both;
    overflow: hidden;
}

.top_spac {
    margin: 20px 0 0;
}


.recent_heading {
    float: left;
    width: 40%;
}

.srch_bar {
    display: inline-block;
    text-align: right;
    width: 60%;
}

.headind_srch {
    padding: 10px 29px 10px 20px;
    overflow: hidden;
    border-bottom: 1px solid #c4c4c4;
}

.recent_heading h4 {
    color: #05728f;
    font-size: 21px;
    margin: auto;
}

.srch_bar input {
    border: 1px solid #cdcdcd;
    border-width: 0 0 1px 0;
    width: 80%;
    padding: 2px 0 4px 6px;
    background: none;
}

.srch_bar .input-group-addon button {
    background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
    border: medium none;
    padding: 0;
    color: #707070;
    font-size: 18px;
}

.srch_bar .input-group-addon {
    margin: 0 0 0 -27px;
}

.chat_ib h5 {
    font-size: 15px;
    color: #464646;
    margin: 0 0 8px 0;
}

.chat_ib h5 span {
    font-size: 13px;
    float: right;
}

.chat_ib p {
    font-size: 14px;
    color: #989898;
    margin: auto
}

.chat_img {
    float: left;
    width: 11%;
}

.chat_ib {
    float: left;
    padding: 0 0 0 15px;
    width: 88%;
}

.chat_people {
    overflow: hidden;
    clear: both;
}

.chat_list {
    border-bottom: 1px solid #c4c4c4;
    margin: 0;
    padding: 18px 16px 10px;
}

.inbox_chat {
    height: 550px;
    overflow-y: scroll;
}

.active_chat {
    background: #ebebeb;
}

.incoming_msg_img {
    display: inline-block;
    width: 6%;
}

.received_msg {
    display: inline-block;
    padding: 0 0 0 10px;
    vertical-align: top;
    width: 92%;
}

.received_withd_msg p {
    background: #ebebeb none repeat scroll 0 0;
    border-radius: 3px;
    color: #646464;
    font-size: 14px;
    margin: 0;
    padding: 5px 10px 5px 12px;
    width: 100%;
}

.time_date {
    color: #747474;
    display: block;
    font-size: 12px;
    margin: 8px 0 0;
}

.received_withd_msg {
    width: 57%;
}

.mesgs {
    float: left;
    padding: 30px 15px 0 25px;
    width: 60%;
    height: 550px;
    overflow-y: scroll;
}

.sent_msg p {
    background: #187fe7 none repeat scroll 0 0;
    border-radius: 3px;
    font-size: 14px;
    margin: 0;
    color: #fff;
    padding: 5px 10px 5px 12px;
    width: 100%;
}

.outgoing_msg {
    overflow: hidden;
    margin: 26px 0 26px;
}

.sent_msg {
    float: right;
    width: 46%;
}

.input_msg_write input {
    background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
    border: medium none;
    color: #4c4c4c;
    font-size: 15px;
    min-height: 48px;
    width: 100%;
}

.type_msg {
    margin-top: 16px;
    border-top: 1px solid #c4c4c4;
    position: relative;
}

.msg_send_btn {
    background: #05728f none repeat scroll 0 0;
    border: medium none;
    border-radius: 50%;
    color: #fff;
    cursor: pointer;
    font-size: 17px;
    height: 33px;
    position: absolute;
    right: 0;
    top: 11px;
    width: 33px;
}

.messaging {
    padding: 0 0 50px 0;
}

.msg_history {
    //height: 160px;
    margin: 4px;
    overflow-y: auto;
}

</style>
