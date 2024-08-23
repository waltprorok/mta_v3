<template>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body" v-cloak>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <table class="table table-sm m-0">
                                    <tbody>
                                    <tr>
                                        <th class="border-0" style="padding: .0rem;">From:</th>
                                        <td class="border-0" style="padding: .0rem;">
                                            <strong>{{ message.user_from.first_name }} {{ message.user_from.last_name }}</strong> | {{ message.user_from.email }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="border-0" style="padding: .0rem;">To:</th>
                                        <td class="border-0" style="padding: .0rem;">{{ message.user_to.email }}</td>
                                    </tr>
                                    <tr>
                                        <th class="border-0" style="padding: .0rem;">Subject:</th>
                                        <td class="border-0" style="padding: .0rem;">{{ message.subject }}</td>
                                    </tr>
                                    <tr>
                                        <th class="border-0" style="padding: .0rem;">Sent:</th>
                                        <td class="border-0" style="padding: .0rem;">{{ message.created_at | dateParse('YYYY-MM-DD') | dateFormat(' MM-DD-YYYY h:mm a') }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="textarea"><b>Message:</b></label>
                                    <br>
                                    <p v-html="message.body"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <button v-if="message.user_from.id !== author" @click="replayToMessage(message)" class="btn btn-primary">Reply</button>
                <button v-if="! message.read" @click="deleteMessage(message)" class="btn btn-danger float-right">Delete</button>
            </div>
        </div>
        <notifications position="bottom right"/>
    </div>
</template>

<script>
import {dateParse} from "@vuejs-community/vue-filter-date-parse";
import {dateFormat} from "vue-filter-date-format";

export default {
    data: function () {
        return {
            author: 0,
            message: {
                user_from: {
                    id: '',
                    first_name: '',
                    last_name: '',
                    email: '',
                },
                user_to: {
                    email: '',
                },
                subject: '',
                body: '',
                read: false,
                created_at: '1970-01-01 00:00:00',
            },
            parameters: this.$route.fullPath,
        }
    },

    mounted: function () {
        this.fetchMessage();
    },

    methods: {
        dateFormat,
        dateParse,

        fetchMessage: function () {
            axios.get('/web' + this.parameters)
                .then((response) => {
                    this.message = response.data.message;
                    this.author = response.data.author;
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load message.',
                        duration: 10000,
                    });
                });
        },

        deleteMessage: function (message) {
            window.location.href = `/messages/delete/${message.id}`;
        },

        replayToMessage: function (message) {
            //message.user_from.id, message.subject
            window.location.href = `/messages/reply/${message.user_from.id}/${message.subject}`;
        },
    },
}

</script>
