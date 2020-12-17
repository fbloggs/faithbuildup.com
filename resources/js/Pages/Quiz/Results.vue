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
                <pie-chart
                  :chartdata="catchartdata"
                  :options="catoptions"
                ></pie-chart>
              </div>

              <div class="px-8">
                <pie-chart
                  :chartdata="subcatchartdata"
                  :options="subcatoptions"
                ></pie-chart>
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
      let categoryscores = [];
      let categorylabels = [];
     

      for (var i = 0; i < arrayLength; i++) {
        categoryscores.push(this.catresults[i].score);
        categorylabels.push(this.catresults[i].category);
      }

      var chartdata = {
        labels: categorylabels, 
        datasets: [
          {
            label : "Faith Balance Profile",
            data: categoryscores,
            backgroundColor: [
              "rgba(255, 99, 132, 0.8)",
              "rgba(54, 162, 235, 0.8)",
              "rgba(255, 206, 86, 0.8)",
             
            ],
            borderColor: [
              "rgba(255, 99, 132, 1)",
              "rgba(54, 162, 235, 1)",
              "rgba(255, 206, 86, 1)",
               
            ],
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
      let categoryscores = [];
      let categorylabels = [];
      for (var i = 0; i < arrayLength; i++) {
        categoryscores.push(this.subcatresults[i].score);
        categorylabels.push(this.subcatresults[i].subcategory);
      }

      var chartdata = {
        labels: categorylabels,
        datasets: [
          {
            label: "Sub-Category: Faith Style",
          
            data: categoryscores,
            backgroundColor: [
              "rgba(255, 99, 132, 0.3)",
              "rgba(255, 99, 132, 0.5)",
              "rgba(54, 162, 235, 0.3)",
              "rgba(54, 162, 235, 0.5)",
              "rgba(255, 206, 86, 0.3)",
              "rgba(255, 206, 86, 0.5)",
              "rgba(191, 191, 63, 0.2)",
              "rgba(56, 242, 53, 0.2)",
            ],
            borderColor: [
              "rgba(255, 99, 132, 1)",
              "rgba(255, 99, 132, 1)",
              "rgba(54, 162, 235, 1)",
              "rgba(54, 162, 235, 1)",
              "rgba(255, 206, 86, 1)",
              "rgba(255, 206, 86, 1)",
              "rgba(191, 191, 63, 1)",
              "rgba(56, 242, 53, 1)",
            ],
            borderWidth: 1,
          },
        ],
      };

      return chartdata;
    },
  },
};
</script>
