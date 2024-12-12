<script>
import {Line} from 'vue-chartjs'

export default {
    extends: Line,
    data() {
        return {
            url: '/web/dashboard/completed-lessons',
            getCompletedLessonsData: [],
            months: [],
            completed: [],
        }
    },

    mounted() {
        this.getCompletedLessons();
    },

    methods: {
        getCompletedLessons: function () {
            axios.get(this.url)
                .then((response) => {
                    this.getCompletedLessonsData = response.data.getCompletedLessonsData;
                    for (let row of this.getCompletedLessonsData) {
                        this.months.push(row.month);
                        this.completed.push(row.completed);
                    }
                    if (this.getCompletedLessonsData) {
                        this.renderChart({
                                labels: this.months,
                                datasets: [
                                    {
                                        label: 'Completed',
                                        data: this.completed,
                                        backgroundColor: 'rgba(66, 165, 245, 0.5)',
                                        borderColor: '#2196F3',
                                        borderWidth: 1
                                    },
                                    // { WIP: should this data be available?
                                    //     label: 'Cancelled',
                                    //     data: [0,1,2,3,2,3,4,2,0,1,1,0],
                                    //     backgroundColor: 'rgba(244,39,70,0.5)',
                                    //     borderColor: '#CD6155',
                                    //     borderWidth: 1
                                    // },
                                    // {
                                    //     label: 'Rescheduled',
                                    //     data: [3,4,5,2,1,7,2,3,4,0,2,1],
                                    //     backgroundColor: 'rgba(211,114,39,0.5)',
                                    //     borderColor: '#E59866',
                                    //     borderWidth: 1
                                    // },
                                ]
                            },
                            {
                                responsive: true,
                                legend: {
                                    display: false
                                },
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true,
                                            stepSize: 5,
                                        }
                                    }]
                                }
                            })
                    }
                })
                .catch((error) => {
                    console.log(error);
                    this.$notify({
                        type: 'error',
                        title: 'Error',
                        text: 'Could not load completed lessons report.',
                        duration: 10000,
                    });
                });
        },
    }
}

</script>
