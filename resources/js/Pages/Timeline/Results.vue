<template>
  <app-layout :quizdone="quizdone">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Faith Timeline Analysis
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="grid grid-cols-4 gap-3">
            <div class="grid grid-rows-1 col-span-6">
              <div class="px-8 mb-10 border">
                <h1 class="font-bold text-3xl text-blue-800 text-center uppercase">
                  My Faith - Life Line
                </h1>
                    <h2 class="font-bold text-2xl text-red-800 text-center uppercase">{{ user.name}}</h2>
                <div class="col-span-11 mt-10">
                  <line-chart
                    :chartLifeEvents="chartLifeEvents"
                    :chartFaithStrengths="chartFaithStrengths"
                    :chartLabels="chartLabels"
                    :options="options"
                  ></line-chart>
                </div>
                <div class="col-span-11 mt-10 w-auto bg-gradient-to-r  from-green-200 to-green-500 ... flex justify-between sm:flex-nowrap">
                  <div class="ml-4 mt-4 text-white uppercase text-2xl">Young</div>
                  <div class="text-3xl text-red-500 font-extrabold">Life Events Over Time</div>
                  <div class="mr-4 mt-4 text-white uppercase text-2xl">Old</div>
                </div>

                  <!-- Bottom grid with personal info and logo in it: -->
          <div class="grid grid-cols-3">
          <div class="col-span-2 pt-5">
              <div class="grid grid-cols-3 bg-gray-300">
             <!-- <div class="pl-2 font-bold">
                  Name:
              </div>
              <div class="col-span-2">
              {{ user.name}}
              </div> -->
                <div class="pl-2 font-bold">
                  Family:
              </div>
              <div  class="col-span-2">
              {{ user.family}}
              </div>
                 <div class="pl-2 font-bold">
                  Profession:
              </div>
              <div  class="col-span-2">
              {{ user.profession}}
              </div>
                 <div class="pl-2 font-bold">
                  Hobbies:
              </div>
              <div  class="col-span-2">
              {{ user.hobbies}}
              </div>
                <div class="pl-2 font-bold">
                  Fav. Scripture:
              </div>
              <div  class="col-span-2">
              {{ user.scripture}}
              </div>
          </div>
          </div>
          <div>
              <img src="/images/faithlaunch2-logo.png" />
          </div>
          </div>
           <!-- End bottom grid -->
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

<script>
import AppLayout from "@/Layouts/AppLayout";
import LineChart from "@/Pages/Timeline/LineChart";

export default {
  components: {
    AppLayout,
    LineChart,
  },

  props: {
    userid: Number,
    user: Object,

    chartLifeEvents: {
      type: Array,
      required: false,
    },

    chartFaithStrengths: {
      type: Array,
      required: false,
    },

    chartLabels: {
      type: Array,
      required: false,
    },
  },

  data() {
    return {
      sending: false,
      pageError: false,
      quizdone: true,
      options: {
        scales: {
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

          xAxes: [
            {
              ticks: {
                autoSkip: false,
                maxRotation: 45,
                minRotation: 45,
                fontColor: "red",
                fontStyle: "bold",
                fontSize: 14,
                labelOffset: 5,
              },
              gridLines: {
                display: true,
              },

              scaleLabel: {
                display: false,
                labelString: "Life Events Over Time",
                color: "#911",
                fontColor: "red",
                fontStyle: "bold",
                fontSize: 24,

                padding: { top: 20, left: 0, right: 0, bottom: 0 },
              },
            },
          ],
        },

        responsive: true,
        maintainAspectRatio: false,
        legend: {
          display: true,
        },
        title: {
          display: false,
          text: "Faith Timeline Chart",
        },
      },
    };
  },
};
</script>
