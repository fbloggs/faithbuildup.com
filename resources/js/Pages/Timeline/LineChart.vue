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
            borderColor: "red",
            pointBackgroundColor: "white",
            borderWidth: 2,
            pointBorderColor: "red",
            borderDash: [10, 5],
            backgroundColor: "transparent",
            data: this.chartLifeEvents,
          },

          {
            label: "Faith Strength",
            borderColor: "#249EBF",
            pointBackgroundColor: "white",
            borderWidth: 2,
            pointBorderColor: "#249EBF",
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
