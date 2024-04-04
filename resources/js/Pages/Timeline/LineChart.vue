<script>
import { Line } from "vue-chartjs";

export default {
  extends: Line,

  props: ["chartLifeEvents", "chartFaithStrengths", "chartLabels", "options"],

  mounted() {
    this.renderChart(
      {
        labels: this.chartLabels,
        datasets: [
          {
            label: 'Life Events',
            data: this.chartLifeEvents,
            fill: false,
            borderColor: 'rgb(0, 146, 142)',
            pointRadius: 6,
            pointBackgroundColor: 'rgb(0, 146, 142)',
            tension: 0.4
          },
          {
            label: 'Faith Strenghts',
            data: this.chartFaithStrengths,
            fill: false,
            borderColor: 'rgb(112, 179, 120)',
            borderDash: [10, 2],
            pointRadius: 6,
            pointBackgroundColor: 'rgb(112, 179, 120)',
            tension: 0.4
          }
        ]
      },
      {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          yAxes: {
            suggestedMin: -100,
            suggestedMax: 100,
          },
          yAxes: [
            {
              ticks: {
                beginAtZero: true,
                min: -100,
                max: 100,
            
              },
              gridLines: {
                display: true,
                zeroLineWidth: 2,
                zeroLineColor: "black",
                zeroLineBorderDash: [1, 0 ],

              },
              scaleLabel: {
                display: true,
                labelString: "Life Events (+/-) Faith Strength",

              },
            },
          ],
          xAxes: [{
            ticks: {
              fontColor: 'rgb(0, 78, 103)',
              fontFamily:'Arial Black',
              maxRotation: 45,
              minRotation: 45,
              padding: 12,
            }
          }],
        },
        legend: {
          display: false,
          position: 'bottom',
        },
      }
    );
  },
};
</script>