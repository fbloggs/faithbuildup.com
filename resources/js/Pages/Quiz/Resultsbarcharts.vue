<script setup>
import { computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import CategoryChart from '@/Pages/Quiz/CategoryChart.vue'

const props = defineProps({
  userid: Number,
  quiz: Object,
  catresults: Array,
  subcatresults: Array,
})

const sending = false
const pageError = false
const quizdone = true

const catoptions = {
  responsive: true,
  plugins: {
    legend: {
      display: false
    },
    title: {
      display: true,
      text: 'Faith Balance '
    }
  },
  scales: {
    y: {
      beginAtZero: true,
    },
  },
}

const subcatoptions = {
  responsive: true,
  plugins: {
    legend: {
      display: false
    },
    title: {
      display: true,
      text: 'Sub-Category: Faith Style'
    }
  },
  scales: {
    y: {
      beginAtZero: true,
    },
  },
}

const catchartdata = computed(() => {
  const arrayLength = props.catresults.length
  let categoryscores = []
  let categorylabels = []

  for (let i = 0; i < arrayLength; i++) {
    categoryscores.push(props.catresults[i].score)
    categorylabels.push(props.catresults[i].category)
  }

  return {
    labels: categorylabels,
    datasets: [
      {
        label: 'Faith Balance',
        data: categoryscores,
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
        ],
        borderWidth: 1,
      },
    ],
  }
})

const subcatchartdata = computed(() => {
  const arrayLength = props.subcatresults.length
  let categoryscores = []
  let categorylabels = []
  
  for (let i = 0; i < arrayLength; i++) {
    categoryscores.push(props.subcatresults[i].score)
    categorylabels.push(props.subcatresults[i].subcategory)
  }

  return {
    labels: categorylabels,
    datasets: [
      {
        label: 'Sub-Category: Faith Style',
        data: categoryscores,
        backgroundColor: [
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(255, 159, 64, 0.2)',
          'rgba(193, 66, 66, 0.2)',
          'rgba(63, 127, 191, 0.2)',
          'rgba(191, 63, 191, 0.2)',
          'rgba(191, 191, 63, 0.2)',
          'rgba(56, 242, 53, 0.2)',
        ],
        borderColor: [
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)',
          'rgba(193, 66, 66, 1)',
          'rgba(63, 127, 191, 1)',
          'rgba(191, 63, 191, 1)',
          'rgba(191, 191, 63, 1)',
          'rgba(56, 242, 53, 1)',
        ],
        borderWidth: 1,
      },
    ],
  }
})
</script>

<template>
  <app-layout :quizdone="quizdone">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ quiz.quiz_name }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-4">
            <h3 class="font-bold">Results</h3>

            <p>
              The numerical scores in your results have no significance by
              themselves, except to show the relative balance between the three
              elements of faith. Hopefully the results of this quiz will give
              you some insight into your natural thinking process and you assist
              you to develop a personal action plan to get these elements in
              more of a balance, if you desire.
            </p>
          </div>
          <div class="grid grid-cols-3 gap-4">
            <div class="grid grid-rows-1 col-span-2">
              <div class="px-8">
                <category-chart
                  :chartdata="catchartdata"
                  :options="catoptions"
                ></category-chart>
              </div>

              <div class="px-8">
                <category-chart
                  :chartdata="subcatchartdata"
                  :options="subcatoptions"
                ></category-chart>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<style>
tbody tr:nth-child(odd) td {
  --tw-bg-opacity: 1;
  background-color: rgba(249, 250, 251, var(--tw-bg-opacity));
}
</style>