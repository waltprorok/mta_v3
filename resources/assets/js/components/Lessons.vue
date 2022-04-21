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
            <tr v-for="lesson in list" v-show="hasData">
                <td>
                    <button class="btn btn-default btn-rounded" v-if="!lesson.complete" @click="updateLesson(lesson.id, lesson.complete)">Click to Complete</button>
                    <button class="btn btn-success btn-rounded" v-if="lesson.complete" @click="updateLesson(lesson.id, lesson.complete)">Completed</button>
                </td>
                <td v-text="lesson.title"></td>
                <td>{{ lesson.start_date | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY hh:mm a') }}</td>
                <td>{{ lesson.end_date | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('MM-DD-YYYY hh:mm a') }}</td>
                <td v-text="lesson.interval"></td>
            </tr>
            <tr v-show="!hasData">
                <td colspan="6" class="text-center">No data available in table</td>
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
            hasData: false,
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
            axios.get('lessons/list')
                .then((response) => {
                    this.list = response.data;
                    this.hasData = this.list ? this.list.length >= 1 : false;
                }).catch((error) => {
                console.log(error);
            });
        },
        updateLesson: function (id, complete) {
            let self = this;
            self.lesson.id = id;
            self.lesson.complete = !complete;
            let params = Object.assign({}, self.lesson);
            axios.patch('lessons/update/' + id, params)
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
