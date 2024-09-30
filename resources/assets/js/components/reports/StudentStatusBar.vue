<script>
import {Bar} from 'vue-chartjs'

export default {
    extends: Bar,
    data() {
        return {
            url: '/web/status',
            backgroundColor: ['green', 'purple', 'blue', 'orange'],
            data: [],
            labels: ['Active', 'Inactive', 'Leads', 'Waitlist'],
        }
    },

    mounted() {
        this.getReportStudentStatus();
    },

    methods: {
        getReportStudentStatus: function () {
            axios.get(this.url)
                .then((response) => {
                    this.data = response.data;

                    if (this.data) {
                        this.renderChart({
                            labels: this.labels,
                            datasets: [
                                {
                                    label: 'Students',
                                    backgroundColor: this.backgroundColor,
                                    data: this.data
                                }
                            ]
                        }, {
                            responsive: true,
                            maintainAspectRatio: false,
                            legend: {
                                display: false
                            },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        stepSize: 1,
                                    }
                                }]
                            }
                        })
                    }
                }).catch((error) => {
                console.log(error);
                this.$notify({
                    type: 'error',
                    title: 'Error',
                    text: 'Could not load student statuses report.',
                    duration: 10000,
                });
            });
        },

    },
}
</script>
