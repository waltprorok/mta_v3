<script>
import {Bar} from 'vue-chartjs'

export default {
    extends: Bar,
    data() {
        return {
            url: '/web/status',
            data: ''
        }
    },
    methods: {
        getReportStudentStatus: function () {
            axios.get(this.url)
                .then((response) => {
                    this.data = response.data;

                    if (this.data) {
                        this.renderChart({
                            labels: ['Active', 'Inactive', 'Leads', 'Waitlist'],
                            datasets: [
                                {
                                    label: 'Students',
                                    backgroundColor: ['green', 'red', 'blue', 'orange'],
                                    data: this.data
                                }
                            ]
                        }, {
                            responsive: true,
                            maintainAspectRatio: false
                        })
                    }
                }).catch((error) => {
                console.log(error);
                this.$notify({
                    type: 'error',
                    title: 'Error',
                    text: 'Could not load student status report.',
                    duration: 10000,
                });
            });
        },

    },

    mounted() {
        this.getReportStudentStatus();
    }
}
</script>
