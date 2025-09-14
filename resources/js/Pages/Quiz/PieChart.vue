<script setup>
import { Pie } from 'vue-chartjs'
import {
  Chart as ChartJS,
  ArcElement,
  Tooltip,
  Legend
} from 'chart.js'

// Register Chart.js components
ChartJS.register(ArcElement, Tooltip, Legend)

const props = defineProps({
  chartdata: Object,
  options: Object
})
</script>

<template>
  <Pie 
    :data="chartdata" 
    :options="options" 
  />
</template>