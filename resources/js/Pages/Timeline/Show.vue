<template>
  <app-layout :timelinedone="false" :quizdone="quizdone">
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
                  <div class="flex justify-between">
                    <div><h3 class="font-bold">Profile Information</h3></div>

                    <div class="mr-2 pt-2 pb-6">
                      <jet-button> Save Your Answers and Show Results</jet-button>
                    </div>
                  </div>
                  <div class="space-y-6 sm:space-y-5">
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                      <label for="family" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                        Family
                      </label>
                      <div class="mt-1 sm:mt-0 sm:col-span-2">
                        <input 
                          type="text" 
                          name="family" 
                          id="family" 
                          autocomplete="Family" 
                          v-model="form.family" 
                          class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm form-input rounded-md" 
                        />
                      </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                      <label for="profession" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                        Profession
                      </label>
                      <div class="mt-1 sm:mt-0 sm:col-span-2">
                        <input 
                          type="text" 
                          name="profession" 
                          id="profession" 
                          autocomplete="Profession" 
                          v-model="form.profession" 
                          class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm form-input rounded-md" 
                        />
                      </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                      <label for="hobbies" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                        Hobbies
                      </label>
                      <div class="mt-1 sm:mt-0 sm:col-span-2">
                        <input 
                          id="hobbies" 
                          name="hobbies" 
                          type="text" 
                          autocomplete="Hobbies" 
                          v-model="form.hobbies" 
                          class="block max-w-lg w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm form-input rounded-md" 
                        />
                      </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                      <label for="scripture" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                        Favorite scripture
                      </label>
                      <div class="mt-1 sm:mt-0 sm:col-span-2">
                        <input 
                          type="text" 
                          name="scripture" 
                          id="scripture" 
                          autocomplete="Favorite scripture" 
                          v-model="form.scripture" 
                          class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm form-input rounded-md" 
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <h3 class="font-bold sm:border-t sm:border-gray-200 mt-5 pt-5">Instructions For Faith Time Line</h3>
              <p>Describe 5 - 20 life events that affected your faith throughout your life and rate both the event and the relative strength of your faith at the time of the event.</p>
              <p>The strength of your faith does not necessarily follow the rating of a life event. Sometimes a negative event can give a boost to your faith and on occasion a positive event can damage your faith. Please assess where your faith was at the time or how it changed with the event.</p>
              <p>Rate both the life events and your relative faith at the time on a scale from "- 100" to "+ 100", with "0" being neutral.</p>
              <p>Life events should be rated as to whether it was a good or bad event for you.</p>
              <p>For example, if the best thing that ever happened to you was getting married, it may deserve a "+100" rating. On the other hand, if a near death car accident was the worst thing that happened to you it may desire a "-100"</p>
              <p>Faith Strength should be rated relative to what you think your faith could be. Here's some examples:</p>
              <ul class="list-disc pl-6">
                <li>A "+100" Faith Rating during and event would be an indication you think your faith was strong as ever and you were at your closest to God and our Lord.</li>
                <li>A "0" Faith Rating during an event may indicate you feel you had no faith or had not even thought about God.</li>
                <li>A "-100" Faith Rating may indicate you were sure that there is no God or you were very angry with God and and totally rejecting Him in your life.</li>
              </ul>
              <p class="italic">Please know we consider your Faith Life Line very confidential and while FaithLaunch administrators can view it to assist with your presentation preparation, it will not be shared with anybody without your permission.</p>

              <div v-if="validationErrors.hasInvalidValues" class="bg-red-300 text-red-700 border-2 border-red-700 rounded p-2 m-2">
                One or more of your answers have incorrect answers. Please correct them. (You must choose a value from -100 to +100)
              </div>
              <div v-if="validationErrors.insufficientResponses" class="bg-red-300 text-red-700 border-2 border-red-700 rounded p-2 m-2">
                You must provide answers for at least 5 life events.
              </div>
            </div>

            <table class="w-full whitespace-nowrap table-auto m-6">
              <thead>
                <tr class="text-left bold">
                  <th class="pl-2 pr-2 pt-6 pb-4">#</th>
                  <th class="pl-2 pt-6 pb-4">Life Event Description</th>
                  <th class="pl-6 pt-6 pb-4 pr-2">Life Event Rating</th>
                  <th class="pl-6 pt-6 pb-4 pr-2">Faith Strength Rating</th>
                </tr>
              </thead>
              <tbody>
                <tr 
                  v-for="faithevent in localFaithEvents" 
                  :key="faithevent.index" 
                  class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                  <td class="border-t c ml-2">
                    {{ faithevent.index }}
                  </td>
                  <td class="border-t leading-none sm:leading-tight text-sm">
                    <input 
                      v-model="faithevent.description" 
                      class="form-input rounded-md shadow-sm m-3 w-1/2" 
                    />
                  </td>

                  <faith-event-input 
                    @check-all-answers="checkForPageError" 
                    class="hover:bg-gray-200 focus-within:bg-gray-100" 
                    :eventnumber="faithevent.index" 
                    v-model="faithevent.response"
                  />

                  <faith-strength-input 
                    @check-all-answers="checkForPageError" 
                    class="hover:bg-gray-200 focus-within:bg-gray-100" 
                    :eventnumber="faithevent.index" 
                    v-model="faithevent.faithstrength"
                  />
                </tr>
              </tbody>
            </table>
            
            <div v-if="validationErrors.hasInvalidValues" class="bg-red-300 text-red-700 border-2 border-red-700 rounded p-2 m-2">
              One or more of your answers is incorrect or missing. Please correct them. (You must type in a value from -100 to 100)
            </div>
            <div v-if="validationErrors.insufficientResponses" class="bg-red-300 text-red-700 border-2 border-red-700 rounded p-2 m-2">
              You must provide answers for at least 5 life events.
            </div>
            
            <div class="flex justify-end mr-2 pt-2 pb-6">
              <jet-button :disabled="sending"> 
                {{ sending ? 'Saving...' : 'Save Your Answers and Show Results' }}
              </jet-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<style scoped>
tbody tr:nth-child(odd) td {
  --tw-bg-opacity: 1;
  background-color: rgba(249, 250, 251, var(--tw-bg-opacity));
}
</style>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import AppLayout from "@/Layouts/AppLayout.vue"
import JetActionMessage from "@/Components/ActionMessage.vue"
import JetButton from "@/Components/PrimaryButton.vue"
import JetFormSection from "@/Components/FormSection.vue"
import JetInput from "@/Components/InputError.vue"
import FaithEventInput from "@/Pages/Timeline/Faitheventinput.vue"
import FaithStrengthInput from "@/Pages/Timeline/Faithstrengthinput.vue"
import JetInputError from "@/Components/InputError.vue"
import JetLabel from "@/Components/InputLabel.vue"

// Props
const props = defineProps({
  userid: Number,
  user: Object,
  faithevents: Array,
  answers: Array,
  errors: Object,
  quizdone: Boolean,
})

// Reactive state
const sending = ref(false)
const localFaithEvents = ref([])

// Form using Inertia
const form = useForm({
  userid: props.userid,
  answers: props.answers,
  family: props.user.family,
  profession: props.user.profession,
  hobbies: props.user.hobbies,
  scripture: props.user.scripture,
})

// Computed validation errors
const validationErrors = computed(() => {
  const hasInvalidValues = ref(false)
  const insufficientResponses = ref(false)
  let responseCount = 0

  localFaithEvents.value.forEach(item => {
    const hasResponse = item.response !== null && item.response !== undefined
    const hasFaithStrength = item.faithstrength !== null && item.faithstrength !== undefined
    
    if (!hasResponse && !hasFaithStrength) {
      return // Both are empty - ignore
    }
    
    if (hasResponse && hasFaithStrength &&
        item.response >= -100 && item.response <= 100 && 
        item.faithstrength >= -100 && item.faithstrength <= 100) {
      responseCount++
    } else {
      hasInvalidValues.value = true
    }
  })

  if (responseCount < 5) {
    insufficientResponses.value = true
  }

  return {
    hasInvalidValues: hasInvalidValues.value,
    insufficientResponses: insufficientResponses.value,
    hasErrors: hasInvalidValues.value || insufficientResponses.value
  }
})

// Methods
const initializeFaithEvents = () => {
  localFaithEvents.value = props.faithevents.map(event => ({
    index: event.index,
    description: event.description || '',
    response: event.response || null,
    faithstrength: event.faithstrength || null,
  }))
}

const submit = () => {
  if (!validationErrors.value.hasErrors) {
    form.answers = localFaithEvents.value.map(element => ({
      index: element.index,
      description: element.description,
      response: element.response,
      faithstrength: element.faithstrength,
    }))

    form.put(route("timelines.update"), {
      onStart: () => sending.value = true,
      onFinish: () => sending.value = false,
    })
  }
}

const checkForPageError = () => {
  // This method is called by child components for real-time validation
  // The actual validation is handled by the computed property
}

// Watchers
watch(() => props.faithevents, () => {
  initializeFaithEvents()
}, { immediate: true })

// Lifecycle
onMounted(() => {
  initializeFaithEvents()
})
</script>