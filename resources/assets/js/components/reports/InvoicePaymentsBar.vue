<script>
import {Bar} from 'vue-chartjs'

export default {
    extends: Bar,
    data() {
        return {
            url: '/web/payments',
            backgroundColor: [
                'green',
                'midnightblue',
                'yellowgreen',
                'steelblue',
                'cyan',
                'purple',
                'orange',
                'beige',
                'linen',
                'burlywood',
                'yellow',
                'pink'],
            data: [],
            labels: [],
        }
    },

    mounted() {
        this.getReportInvoicePayments();
    },

    methods: {
        getReportInvoicePayments: function () {
            axios.get(this.url)
                .then((response) => {
                    this.labels = response.data.paymentTypes.length !== 0 ? response.data.paymentTypes : ['Cash'];
                    this.data = response.data.payments.length !== 0 ? response.data.payments : [0];
                    if (this.data) {
                        this.renderChart({
                            labels: this.labels,
                            datasets: [
                                {
                                    label: 'Total',
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
                                        stepSize: 100,
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
                        text: 'Could not load invoice payments report.',
                        duration: 10000,
                    });
                });
        },

    },
}
</script>
