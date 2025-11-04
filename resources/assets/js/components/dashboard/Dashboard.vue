<template>
    <div>
        <div class="col-12">
            <div class="row">
                <card :count="activeStudentCount" :title="`Active Students`" :icon="`fa fa-users`"></card>
                <card :count="lessonsThisWeek" :title="`Lesson(s)`" :icon="`fa fa-calendar`" :dates="weeklyDates"></card>
                <card :count="cancelledLessonsThisWeek" :title="`Cancelled Lesson(s)`" :icon="`fa fa-calendar-times-o`" :dates="weeklyDates"></card>
                <card :count="openTimeBlocks" :title="`Open Time Blocks`" :icon="`fa fa-clock-o`" :dates="weeklyDates"></card>
                <card :count="todayIncome" :title="`Today's Income`" :icon="`fa fa-money`"></card>
                <card :count="weeklyIncome" :title="`Weekly Income`" :icon="`fa fa-money`"></card>
                <card :count="monthlyIncome" :title="`Monthly Income`" :icon="`fa fa-money`"></card>
                <card :count="yearlyIncome" :title="`Yearly Income`" :icon="`fa fa-money`"></card>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <lessons-card :lessons="dailyLessons"></lessons-card>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            Completed Lessons
                        </div>
                        <div class="card-body p-0">
                            <div class="p-4">
                                <report-completed-lessons-line :width="100" :height="22"></report-completed-lessons-line>
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
import Card from "../cards/Card.vue";
import LessonsCard from "../cards/LessonsCard.vue";

export default {
    components: {LessonsCard, Card},
    data() {
        return {
            activeStudentCount: 0,
            lessonsThisWeek: 0,
            cancelledLessonsThisWeek: 0,
            openTimeBlocks: 0,
            todayIncome: 0,
            weeklyIncome: 0,
            monthlyIncome: 0,
            yearlyIncome: 0,
            subscriptionType: '',
            subscriptionText: '',
            subscriptionMessage: '',
            weeklyDates: '',
            dailyLessons: [],
        }
    },

    mounted() {
        this.fetchData();
    },

    methods: {
        fetchData: function () {
            axios.get('/web/dashboard')
                .then((response) => {
                    this.activeStudentCount = response.data.activeStudentCount;
                    this.todayIncome = response.data.todayIncome;
                    this.weeklyIncome = response.data.weeklyIncome;
                    this.monthlyIncome = response.data.monthlyIncome;
                    this.yearlyIncome = response.data.yearlyIncome;
                    this.lessonsThisWeek = response.data.lessonsThisWeek;
                    this.cancelledLessonsThisWeek = response.data.cancelledLessonsThisWeek;
                    this.openTimeBlocks = response.data.openTimeBlocks;
                    this.weeklyDates = response.data.weeklyDates;
                    this.dailyLessons = response.data.dailyLessons;
                    this.$notify({
                        type: this.subscriptionType = response.data.subscriptionType,
                        title: this.subscriptionText = response.data.subscriptionText,
                        text: this.subscriptionMessage = response.data.subscriptionMessage,
                        duration: 30000,
                    })
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load dashboard data.',
                        duration: 6000,
                    });
                });
        },
    }
}

</script>
