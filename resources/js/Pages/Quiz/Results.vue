<template>
  <app-layout :quizdone="quizdone">
    <template #header>
           <div class="flex justify-between">
               <div>
             <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ quiz.quiz_name }}
            </h2>
               </div>
            <div class="  mr-2 pt-2 pb-6">
                <inertia-link :href="route('quizzes.show', {id: 1})" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150">
                    Take the Quiz Again
                    </inertia-link>
            </div>
          </div>
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
              you some insight into your natural thinking process and will assist
              you in developing a personal action plan to get these elements in
              more of a balance, if you desire.
            </p>
          </div>
          <div class="grid grid-cols-4 gap-3">
            <div class="grid grid-rows-1 col-span-3">
              <div class="px-8 mb-10 border">
                <pie-chart
                  :chartdata="catchartdata"
                  :options="catoptions"
                ></pie-chart>
                <div class="py-4">
                <ul class="list-style-none">
                <li class="mr-10 pb-2 font-bold" v-for="cat in catsorted" :key="cat.category">
                 {{ cat.category}}  {{ cat.score }}
                </li>
                </ul>
                </div>
              </div>

              <div class="px-8">
                <pie-chart
                  :chartdata="subcatchartdata"
                  :options="subcatoptions"
                ></pie-chart>

                  <div class="py-4">
                  <h2>Top 3 Faith Styles</h2>
                <ul class="list-style-none">
                <li class="inline mr-10 font-bold" v-for="cat, index in subcattopthree" :key="cat.subcategory">
                 {{ cat.subcategory}}  {{ cat.score }}
                </li>
                </ul>
                </div>
              </div>
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
import PieChart from "@/Pages/Quiz/PieChart";

export default {
  components: {
    AppLayout,
    PieChart,
  },

  props: {
    userid: Number,
    quiz: Object,

    catresults: Array,
    subcatresults: Array,
    catsorted : Array,
    subcatsorted : Array,
  },
  data() {
    return {
      sending: false,
      pageError: false,
      quizdone: true,
      catoptions: {
            responsive : true,
            legend: {
              display: true
            },
            title: {
            display: true,
            text: "Faith Balance Profile"
         },

      },

      subcatoptions: {
         responsive : true,

         legend: {
              display: true
            },
            title: {
            display: true,
            text: "Sub-Category: Faith Style"
         },

      },
    };
  },

  computed: {
    catchartdata() {
      var arrayLength = this.catresults.length;
      let scores = [];
      let labels = [];
      let bgcolors = [];
      let bordercolors = [];

      for (var i = 0; i < arrayLength; i++) {
        scores.push(this.catresults[i].score);
        labels.push(this.catresults[i].category);
        bgcolors.push(this.catresults[i].bgcolor);
        bordercolors.push(this.catresults[i].bordercolor);
      }

      var chartdata = {
        labels: labels,
        datasets: [
          {
            label : "Faith Balance Profile",
            data: scores,
            backgroundColor: bgcolors,

            borderColor: bordercolors,
            borderWidth: 1,

          },
        ],
      };

      return chartdata;
    },

    /**
     * Draw chart for subcategories - Faith style.
     */
    subcatchartdata() {
      var arrayLength = this.subcatresults.length;
     let scores = [];
      let labels = [];
      let bgcolors = [];
      let bordercolors = [];
      for (var i = 0; i < arrayLength; i++) {
         scores.push(this.subcatresults[i].score);
         labels.push(this.subcatresults[i].subcategory);
          bgcolors.push(this.subcatresults[i].bgcolor);
        bordercolors.push(this.subcatresults[i].bordercolor);
      }

      var chartdata = {
        labels: labels,
        datasets: [
          {
            label: "Sub-Category: Faith Style",

            data: scores,
            backgroundColor : bgcolors,
            borderColor:bordercolors,
            borderWidth: 1,
          },
        ],
      };

      return chartdata;
    },

    subcattopthree() {
      let temp = [];
      for (let i=0; i<3; i++ ) {
        temp[i] = this.subcatsorted[i];
      }
      return temp;
    },
  },
};
</script>
