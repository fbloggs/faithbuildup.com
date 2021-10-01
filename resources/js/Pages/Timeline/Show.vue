<template>
  <app-layout :timelinedone="false">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Faith Timeline Chart
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <form @submit.prevent="submit">
            <div class="p-4">


        <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
              <div class="pt-8 space-y-6 sm:pt-10 sm:space-y-5">
                <div>
                  <h3 class="font-bold">Profile Information</h3>
                </div>
                <div class="space-y-6 sm:space-y-5">
                  <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                    <label for="family" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                      Family
                    </label>
                    <div class="mt-1 sm:mt-0 sm:col-span-2">
                      <input type="text" name="family" id="family" autocomplete="Family"  v-model="form.family"  class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm form-input rounded-md"   />
                    </div>
                  </div>

                  <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                    <label for="profession" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                      Profession
                    </label>
                    <div class="mt-1 sm:mt-0 sm:col-span-2">
                      <input type="text" name="profession" id="profession" autocomplete="Profession"  v-model="form.profession" class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm  form-input rounded-md" />
                    </div>
                  </div>

                  <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                    <label for="hobbies" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                      Hobbies
                    </label>
                    <div class="mt-1 sm:mt-0 sm:col-span-2">
                      <input id="hobbies" name="hobbies" type="text" autocomplete="Hobbies"  v-model="form.hobbies" class="block max-w-lg w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm form-input rounded-md" />
                    </div>
                  </div>

                <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                    <label for="scripture" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                      Favorite scripture
                     </label>
                    <div class="mt-1 sm:mt-0 sm:col-span-2">
                      <input type="text" name="scripture" id="scripture" autocomplete="Favorite scripture"  v-model="form.scripture" class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm  form-input rounded-md" />
                    </div>
                  </div>

                </div>
              </div>
                 </div>
              <h3 class="font-bold sm:border-t sm:border-gray-200 mt-5">Instructions</h3>
              <p>
                Describe 5 - 20 life events and rate how they have affected the strength of your faith.
                Rate the life events in terms of positive/negative experience- type a value from -100 (totally negative) to +100 (totally positive).
                Also, rate your corresponding Faith strength- type a value from -100 (totally weakened) to +100 (totally strengthened) , with any value in between.
              </p>

              <div v-if="pageError" class="bg-red-300 text-red-700 border-2 border-red-700 rounded p-2 m-2">
                One or more of your answers have incorrect answers. Please correct them. (You must choose a value from -100 to +100)
              </div>
                  <div v-if="pageError2" class="bg-red-300 text-red-700 border-2 border-red-700 rounded p-2 m-2">
                You must provide answers for at least 5 life events.
              </div>
            </div>

            <table class="w-full whitespace-nowrap table-auto">
              <thead>
                <tr class="text-left bold">
                  <th class="pl-2 pr-2 pt-6 pb-4">#</th>
                  <th class="pl-2 pt-6 pb-4">Life Event Description</th>
                  <th class="pl-6 pt-6 pb-4 pr-2">Life Event Rating</th>
                  <th class="pl-6 pt-6 pb-4 pr-2">Faith Strength Rating</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="faithevent in faithevents" :key="faithevent.index" class="hover:bg-gray-100 focus-within:bg-gray-100">
                  <td class="border-t c ml-2">
                    {{ faithevent.index }}
                  </td>
                  <td class="border-t leading-none sm:leading-tight text-sm">
                    <input v-model="faithevent.description" class="form-input rounded-md shadow-sm m-3 w-1/2" />
                  </td>

                  <faith-event-input @check-all-answers="checkForPageError" class="hover:bg-gray-200 focus-within:bg-gray-100" :eventnumber="faithevent.index" v-model="faithevent.response"> </faith-event-input>

                  <faith-strength-input @check-all-answers="checkForPageError" class="hover:bg-gray-200 focus-within:bg-gray-100" :eventnumber="faithevent.index" v-model="faithevent.faithstrength"> </faith-strength-input>
                </tr>
              </tbody>
            </table>
            <div v-if="pageError" class="bg-red-300 text-red-700 border-2 border-red-700 rounded p-2 m-2">
              One or more of your answers is incorrect or missing. Please correct them. (You must type in a value from -100 to 100)
            </div>
                <div v-if="pageError2" class="bg-red-300 text-red-700 border-2 border-red-700 rounded p-2 m-2">
                You must provide answers for at least 5 life events.
              </div>
            <div class="flex justify-end mr-2 pt-2 pb-6">
              <jet-button> Save Your Answers and Show Results</jet-button>
            </div>
          </form>


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
import JetActionMessage from "@/Jetstream/ActionMessage";
import JetButton from "@/Jetstream/Button";
import JetFormSection from "@/Jetstream/FormSection";
import JetInput from "@/Jetstream/Input";
import FaithEventInput from "@/Pages/Timeline/Faitheventinput";
import FaithStrengthInput from "@/Pages/Timeline/Faithstrengthinput";
import JetInputError from "@/Jetstream/InputError";
import JetLabel from "@/Jetstream/Label";

export default {
  components: {
    AppLayout,
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetInput,
    FaithEventInput,
    FaithStrengthInput,
    JetInputError,
    JetLabel,
  },

  props: {
    userid: Number,
    user: Object ,
    faithevents: Array,
    errors: Object,
  },
  data() {
    return {
      sending: false,
      pageError: false,
      pageError2: false,
      clienterrors: [],
      form: this.$inertia.form(
        {
          userid: this.userid,
          answers: Array,
          family : this.user.family,
          profession: this.user.profession,
          hobbies : this.user.hobbies,
          scripture: this.user.scripture,

        },
        {
          bag: "saveAnswers",
          resetOnSuccess: false,
        }
      ),
    };
  },

  methods: {
    submit() {
      let error = this.checkForm(this.faithevents);

      if (!error) {
        this.form.answers = Array.from(this.faithevents, function(element) {
          return {
            index: element.index,
            description: element.description,
            response: element.response,
            faithstrength: element.faithstrength,
          };
        });



        this.$inertia.put(this.route("timelines.update"), this.form, {
          onStart: () => (this.sending = true),
          onFinish: () => (this.sending = false),
        });
      }
    },

    checkForm(questions) {
      this.pageError = false;
      this.pageError2 = false;
      var arrayLength = questions.length;
      var responseCount = 0;

      for (var i = 0; i < arrayLength; i++) {
        let item = questions[i];
        if (!item.response || item.response == 0)
        {
            // Ignore - no response.
        }
        else {
            responseCount++;

        if ( item.response < -100 || item.response > 100) {
          this.pageError = true;
        }

        if (!item.faithstrength || item.faithgstrength < -100 || item.faithstrength > 100) {
          this.pageError = true;
        }
        }

      }

      if ( responseCount < 5) { this.pageError2 = true}


      if (this.pageError ||  this.pageError2) { return true }
      else { return false; }

    },

    /**
     *  Loop through all answers. For purposes of real-time validation,
     *  ignore the null or empty values - just do them when we submit the page.
     *  Everything else- test for a value from 1 to 5.
     */
    checkForPageError() {
      this.pageError = false;
      var arrayLength = this.questions.length;

      for (var i = 0; i < arrayLength; i++) {
        let item = this.questions[i];
        if (item.response != null && item.response.length > 0) {
          if (item.response < 0 || item.response > 10) {
            this.pageError = true;
          }
        }
      }
    },
  },
};
</script>
