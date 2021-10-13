<script>
import { Line } from "vue-chartjs";

export default {
  extends: Line,

  props: ["chartLifeEvents", "chartFaithStrengths", "chartLabels", "options"],

  mounted() {
    this.renderChart(
      this.chartLifeEvents,
      this.chartFaithStrengths,
      this.chartLabels,
      this.options
    );
  },
  mounted() {
    this.renderChart(
      {
        labels: this.chartLabels,
        datasets: [
          {
            label: "Life Events (+/-)",
            borderColor: "#fa4bce",
            pointBackgroundColor: "white",
            borderWidth: 2,
            pointBorderColor: "#fa4bce",
            borderDash: [10, 5],
            backgroundColor: "transparent",
            data: this.chartLifeEvents,
          },

          {
            label: "Faith Strength",
            borderColor: "#1d60cc",
            pointBackgroundColor: "white",
            borderWidth: 4,
            pointBorderColor: "#1d60cc",
            backgroundColor: "transparent",
            data: this.chartFaithStrengths,
          },
        ],
      },
      this.options
    );
  },
};
</script>
