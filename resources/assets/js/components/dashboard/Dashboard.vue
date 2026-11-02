<template>
    <div class="dashboard">
        <!-- Loading -->
        <div v-if="loading" class="text-center py-5">
            <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
            <p class="text-muted mt-3 mb-0">Loading dashboard...</p>
        </div>

        <template v-else>
            <!-- Studio Overview -->
            <section class="mb-2">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0">Studio Overview</h5>
                    <small v-if="weeklyDates" class="text-muted">
                        {{ weeklyDates }}
                    </small>
                </div>

                <div class="row">
                    <card
                        :count="activeStudentCount"
                        title="Active Students"
                        icon="fa fa-users"
                    ></card>

                    <card
                        :count="lessonsThisWeek"
                        title="Lessons This Week"
                        icon="fa fa-calendar"
                        :dates="weeklyDates"
                    ></card>

                    <card
                        :count="cancelledLessonsThisWeek"
                        title="Cancelled Lessons"
                        icon="fa fa-calendar-times-o"
                        :dates="weeklyDates"
                    ></card>

                    <card
                        :count="openTimeBlocks"
                        title="Open Time Blocks"
                        icon="fa fa-clock-o"
                        :dates="weeklyDates"
                    ></card>
                </div>
            </section>
            <!-- Income -->
            <section class="mb-2">
                <h5 class="mb-3">Income</h5>

                <div class="row">
                    <card
                        :count="todayIncome"
                        title="Today"
                        icon="fa fa-money"
                    ></card>

                    <card
                        :count="weeklyIncome"
                        title="This Week"
                        icon="fa fa-money"
                    ></card>

                    <card
                        :count="monthlyIncome"
                        title="This Month"
                        icon="fa fa-money"
                    ></card>

                    <card
                        :count="yearlyIncome"
                        title="This Year"
                        icon="fa fa-money"
                    ></card>
                </div>
            </section>
            <!-- Lessons -->
            <section class="mb-2">
                <h5 class="mb-3">Lessons</h5>
                <div class="row">
                    <!-- Today's Lessons -->
                    <div class="col-lg-4 col-md-5 mb-3">
                        <lessons-card
                            title="Today's Lessons"
                            :lessons="dailyLessons"
                        ></lessons-card>
                    </div>
                    <!-- Completed Lessons Chart -->
                    <div class="col-lg-8 col-md-7 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <p>Completed Lessons</p>
                            </div>

                            <div class="card-body">
                                <report-completed-lessons-line
                                    :width="100"
                                    :height="32"
                                ></report-completed-lessons-line>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </template>
        <notifications position="bottom right"/>
    </div>
</template>

<script>
import Card from "../cards/Card.vue";
import LessonsCard from "../cards/LessonsCard.vue";

export default {
    name: 'Dashboard',
    components: {LessonsCard, Card},

    data() {
        return {
            loading: true,

            activeStudentCount: 0,
            lessonsThisWeek: 0,
            cancelledLessonsThisWeek: 0,
            openTimeBlocks: 0,

            todayIncome: 0,
            weeklyIncome: 0,
            monthlyIncome: 0,
            yearlyIncome: 0,

            weeklyDates: '',
            dailyLessons: [],
        };
    },

    mounted() {
        this.fetchData();
    },

    methods: {
        async fetchData() {
            this.loading = true;

            try {
                const {data} = await axios.get('/web/dashboard');

                this.activeStudentCount = data.activeStudentCount ?? 0;
                this.lessonsThisWeek = data.lessonsThisWeek ?? 0;
                this.cancelledLessonsThisWeek = data.cancelledLessonsThisWeek ?? 0;
                this.openTimeBlocks = data.openTimeBlocks ?? 0;

                this.todayIncome = data.todayIncome ?? 0;
                this.weeklyIncome = data.weeklyIncome ?? 0;
                this.monthlyIncome = data.monthlyIncome ?? 0;
                this.yearlyIncome = data.yearlyIncome ?? 0;

                this.weeklyDates = data.weeklyDates ?? '';
                this.dailyLessons = data.dailyLessons ?? [];

                if (data.subscriptionMessage) {
                    this.$notify({
                        type: data.subscriptionType || 'info',
                        title: data.subscriptionText || 'Subscription',
                        text: data.subscriptionMessage,
                        duration: 30000,
                    });
                }

            } catch (error) {
                console.error('Dashboard error:', error);

                this.$notify({
                    type: 'error',
                    title: 'Error',
                    text: 'Could not load dashboard data.',
                    duration: 6000,
                });

            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
