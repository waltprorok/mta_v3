<template>
    <div>
        <div class="col-12">
            <div class="row">
                <div class="col-md-3">
                    <div class="card p-4">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <span class="h4 d-block font-weight-normal mb-2">{{ activeStudentCount }}</span>
                                <span class="font-weight-light">Active Students</span>
                            </div>

                            <div class="h2 text-muted">
                                <i class="fa fa-users" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-4">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <span class="h4 d-block font-weight-normal mb-2">{{ lessonsThisWeek }}</span>
                                <span class="font-weight-light">Lessons This Week</span>
                            </div>

                            <div class="h2 text-muted">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-4">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <span class="h4 d-block font-weight-normal mb-2">{{ cancelledLessonsThisWeek }}</span>
                                <span class="font-weight-light">Cancelled Lessons This Week</span>
                            </div>

                            <div class="h2 text-muted">
                                <i class="fa fa-calendar-times-o" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-4">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <span class="h4 d-block font-weight-normal mb-2">{{ openTimeBlocks }}</span>
                                <span class="font-weight-light">Open Time Blocks This Week</span>
                            </div>

                            <div class="h2 text-muted">
                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-4">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <span class="h4 d-block font-weight-normal mb-2">${{ todayIncome }}</span>
                                <span class="font-weight-light">Today's Income</span>
                            </div>

                            <div class="h2 text-muted">
                                <i class="fa fa-money" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-4">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <span class="h4 d-block font-weight-normal mb-2">${{ weeklyIncome }}</span>
                                <span class="font-weight-light">Weekly Income</span>
                            </div>

                            <div class="h2 text-muted">
                                <i class="fa fa-money" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-4">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <span class="h4 d-block font-weight-normal mb-2">${{ monthlyIncome }}</span>
                                <span class="font-weight-light">Monthly Income</span>
                            </div>

                            <div class="h2 text-muted">
                                <i class="fa fa-money" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card p-4">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <span class="h4 d-block font-weight-normal mb-2">${{ yearlyIncome }}</span>
                                <span class="font-weight-light">Yearly Income</span>
                            </div>

                            <div class="h2 text-muted">
                                <i class="fa fa-money" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            Lessons
                        </div>
                        <div class="card-body p-0">
                            <div class="p-4">
                                <report-completed-lessons-line :width="100" :height="20"></report-completed-lessons-line>
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

export default {
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
                        duration: 10000,
                    });
                });
        },
    }
}

</script>
