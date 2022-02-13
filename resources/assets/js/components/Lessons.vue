<template>
    <div class="card">
        <table class="table table-responsive-md">
            <thead>
            <tr>
                <th scope="col">Completed</th>
                <th scope="col">Name</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Duration</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="lesson in list">
                <td>
                    <button class="btn btn-primary btn-rounded" v-if="!lesson.complete" @click="updateLesson(lesson.id, lesson.complete)">Click to Complete</button>
                    <button class="btn btn-success btn-rounded" v-if="lesson.complete" @click="updateLesson(lesson.id, lesson.complete)">Completed</button>
                </td>
                <td v-text="lesson.title"></td>
                <td>{{ lesson.start_date | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY hh:mm a') }}</td>
                <td>{{ lesson.end_date | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY hh:mm a') }}</td>
                <td v-text="lesson.interval"></td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

export default {
    data() {
        return {
            list: [],
            lesson: {
                id: null,
                complete: null,
                // title: null,
                // start_date: null,
                // end_date: null,
                // duration: null,
            },
        }
    },
    mounted: function () {
        this.fetchLessonList();
    },
    methods: {
        fetchLessonList: function () {
            axios.get('api/lesson')
                .then((response) => {
                    this.list = response.data;
                }).catch((error) => {
                console.log(error);
            });
        },
        updateLesson: function (id, complete) {
            let self = this;
            self.lesson.id = id;
            self.lesson.complete = !complete;
            let params = Object.assign({}, self.lesson);
            axios.patch('api/lesson/' + id, params)
                .then(function (response) {
                    self.fetchLessonList();
                })
                .catch(function (error) {
                    self.fetchLessonList();
                    console.log(error);
                });
        },
    },
}
</script>
