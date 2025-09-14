<template>
  <div class="chart-container" style="height: 400px;">
    <Line 
      :data="chartData" 
      :options="chartOptions" 
    />
  </div>
</template>

<script>
import { computed } from 'vue'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
} from 'chart.js'
import { Line } from 'vue-chartjs'

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend
)

export default {
  name: 'LineChart',
  components: {
    Line
  },
  props: {
    chartLifeEvents: {
      type: Array,
      required: true
    },
    chartFaithStrengths: {
      type: Array,
      required: true
    },
    chartLabels: {
      type: Array,
      required: true
    },
    options: {
      type: Object,
      default: () => ({})
    }
  },
  setup(props) {
    const chartData = computed(() => ({
      labels: props.chartLabels,
      datasets: [
        {
          label: "Life Events (+/-)",
          borderColor: "#fa4bce",
          pointBackgroundColor: "white",
          borderWidth: 2,
          pointBorderColor: "#fa4bce",
          borderDash: [10, 5],
          backgroundColor: "transparent",
          data: props.chartLifeEvents,
        },
        {
          label: "Faith Strength",
          borderColor: "#1d60cc",
          pointBackgroundColor: "white",
          borderWidth: 4,
          pointBorderColor: "#1d60cc",
          backgroundColor: "transparent",
          data: props.chartFaithStrengths,
        },
      ],
    }))

    const chartOptions = computed(() => ({
      responsive: true,
      maintainAspectRatio: false, // This allows the chart to fill the container height
      ...props.options
    }))

    return {
      chartData,
      chartOptions
    }
  }
}
</script>