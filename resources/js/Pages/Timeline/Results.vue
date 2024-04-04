<template>
  <app-layout :quizdone="quizdone" :sameuser="sameuser">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Faith Timeline Analysis
      </h2>
    </template>
    <div class="py-12 my-life-line">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="grid grid-cols-4 gap-3">
            <div class="grid grid-rows-1 col-span-6">
              <div class=" mb-00 border">
               
                <div class="title">
                    <label>My Faith <span>—</span> Life Line</label>
                    <span>{{ anotherUser.name}}</span>
                </div>    
                <div class="px-8 col-span-11 mt-20">
                 <div class="gradient-line">
                    <div class="start-icon">
                      <i class="fas fa-play"></i>
                      <div class="icon-text">Young</div>
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="rgb(112, 179, 120)">
                        <path d="M8.5 5.14v14.72c0 .42.51.64.81.34l10.09-7.36c.2-.15.2-.45 0-.6L9.31 4.8c-.3-.23-.81-.08-.81.34z"/>
                      </svg>
                    </div>
                    <div class="middle-text">
                      Life Events Over Time
                    </div>
                    <div class="end-icon">
                      <div class="icon-text">Old</div>
                      <i class="fas fa-bars"></i>
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="rgb(0, 78, 103)">
                        <rect x="11" y="4" width="2" height="16"/>
                      </svg>
                    </div>
                  </div>
                  <line-chart
                    :chartLifeEvents="chartLifeEvents"
                    :chartFaithStrengths="chartFaithStrengths"
                    :chartLabels="chartLabels"
                    :options="options"
                  ></line-chart>
                </div>
                <div class="legends">
                  <div class="legend life">Life Events</div>
                  <div class="legend faith">Faith Strenght</div>
                </div>


                <!-- Bottom grid with personal info and logo in it: -->
                <div class="layout-container mt-5 p-8">
                  <div class="left-column">
                    <div class="column-content">
                      <img src="/images/Family.png">
                      <div class="content">
                        <div class="icon-label">Family</div>
                        <div class="icon-description">{{ anotherUser.family}}</div>
                      </div>
                    </div>
                    <div class="column-content">
                      <img src="/images/Profession.png">
                      <div class="content">
                        <div class="icon-label">Profession</div>
                        <div class="icon-description">{{ anotherUser.profession}}</div>
                      </div>
                    </div>
                    <div class="column-content">
                      <img src="/images/Hobbies.png">
                      <div class="content">
                        <div class="icon-label">Hobbies</div>
                        <div class="icon-description">{{ anotherUser.hobbies}}</div>
                      </div>
                    </div>
                    <div class="column-content">
                      <img src="/images/Bible.png">
                      <div class="content">
                        <div class="icon-label">Favorite Scripture</div>
                        <div class="icon-description">{{ anotherUser.scripture}}</div>
                      </div>
                    </div>
                  </div>
                  <div class="right-column">
                    <img src="/images/WCF_Faith-Mentorship_Logo.png" />
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
 .my-life-line {
            /*border: 5px solid red;*/
            padding: 10px;
            text-align: center;
  }

  .my-life-line .title {
      font-size: 40px;
      line-height: 44px;
      font-weight: 900;
      display: inline-block;
      background-color: rgb(247, 247, 247);
      padding: 60px 40px 10px 40px;
      margin: auto;
      color: rgb(0, 78, 103);
  }
  .my-life-line .title label{
        font-family: 'GothamBlack', sans-serif;
  }
  .my-life-line .title label span{
      color:rgb(112, 179, 120);
      font-weight: 800;
      display: inline;
  }
  canvas{padding-right:5px;}
  .my-life-line .title span {
      font-size: 36px;
      line-height: 44px;
      font-weight: 300;
      display: block;
      color: #000;
  }

  .my-life-line .chart-container {
      position: relative;
      width: 100%;
  }

  .my-life-line .chart-container canvas {

      height: 400px;
  }

  .my-life-line .legends {
      display: flex;
      flex-direction: column;
      align-items: end;

  }

  .my-life-line .legends .legend {
      position: relative;
      width: 120px;
      text-align: left;
  }

  .my-life-line .legends .legend:before {
      content: "";
      width: 40px;
      position: absolute;
      left: -52px;
      top: 8px;
  }

  .my-life-line .legends .legend.life:before {
      border-top: 3px solid rgb(0, 146, 142);
  }

  .my-life-line .legends .legend.faith:before {
      border-top: 3px dashed rgb(112, 179, 120);
  }



.gradient-line {
  height: 3px; 
  background: linear-gradient(to right, rgb(112, 179, 120), rgb(0, 78, 103) );
  display: flex;
  align-items: center;
  position: relative;
  margin-left: 80px;
  margin-bottom: 20px;
  margin-right: 12px;
}

.start-icon,
.end-icon {
  width: 20px; 
  height: 20px; 
}

.start-icon i,
.end-icon i {
  color: black; 
}

.start-icon {
  position: absolute;
  left: 0;
}
.start-icon svg{
    position: absolute;
    top: -3px;
    left: -10px;
}

.end-icon svg{
    position: absolute;
    top: -3px;
    right: -11px;
}
.end-icon {
  position: absolute;
  right: 0;
}
.icon-text {
  margin-top: -20px; 
}

.start-icon {
  position: absolute;
  left: 0;
  text-align: center; 
}

.end-icon {
  position: absolute;
  right: 0;
  text-align: center; 
}
.end-icon .icon-text{    
  position: absolute;
  right: 0px;
}
.start-icon .icon-text{    
  position: absolute;
  left: -3px;
}
.middle-text {
  position: absolute;
  left: 50%;
  top: -30px; 
  transform: translateX(-50%);
  color: black;
}

.layout-container {
  display: flex;
  padding-left: 60px;
  background-color: rgb(247,247,247);
}

.left-column {
  display: flex;
  flex-direction: rows;
  width: 60%; 
}

.right-column {
  width: 40%; 
  text-align: left;
}

.column-content {
  display: flex;
  width: 23%;
  padding: 10px;
  align-items: center;
  flex-direction: column;
  margin-bottom: 10px;
}
.column-content img{
  max-width:85px;
  padding-left: 10px;
}
.content {
  display: flex;
  flex-direction: column;
  margin-left: 10px;
}

.icon-label {
  margin-bottom: 5px;
  color:rgb(0, 146, 142);
  line-height: 16px;
  padding-top: 12px;
  font-weight: 700;
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
    anotherUser: Object,

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
          responsive: true,
          maintainAspectRatio: false,
          scales: {
              y: {
                  suggestedMin: -100,
                  suggestedMax: 100,
              },
              x: {
                  ticks: {
                      color: 'rgb(0, 78, 103)',
                      font: {
                          weight: 'bold'
                      }
                  }
              }
          },
          plugins: {
              legend: {
                  display: false,
              }
          }
      }
    };
  },

  computed: {
      sameuser : function () {
      return (this.user.id == this.anotherUser.id) ? true : false;
  }
  }
};
</script>
