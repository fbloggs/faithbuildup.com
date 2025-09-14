<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ActionMessage from '@/Components/ActionMessage.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import FormSection from '@/Components/FormSection.vue'
import TextInput from '@/Components/TextInput.vue'
import AnswerInput from '@/Pages/Quiz/Answerinput.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'

const props = defineProps({
  userid: Number,
  quiz: Object,
  questions: Array,
  errors: Object,
})

const sending = ref(false)
const pageError = ref(false)
const clienterrors = ref([])

const form = useForm({
  userid: props.userid,
  answers: [],
})

const submit = () => {
  let error = checkForm(props.questions)

  if (!error) {
    form.answers = Array.from(props.questions, function (element) {
      return {
        questionnumber: element.questionnumber,
        answer: element.answer,
      }
    })

    form.put(route('quizzes.update', props.quiz.id), {
      onStart: () => (sending.value = true),
      onFinish: () => (sending.value = false),
    })
  }
}

// DJK 20211202 - changed scale from 0 - 10 to 1 - 5 as per Paul's request.
const checkForm = (questions) => {
  pageError.value = false

  const arrayLength = questions.length

  for (let i = 0; i < arrayLength; i++) {
    let item = questions[i]
    if (!item.answer || item.answer < 1 || item.answer > 5) {
      pageError.value = true
    }
  }

  return pageError.value
}

/**
 *  Loop through all answers. For purposes of real-time validation,
 *  ignore the null or empty values - just do them when we submit the page.
 *  Everything else- test for a value from 1 to 5.
 */
const checkForPageError = () => {
  pageError.value = false
  const arrayLength = props.questions.length

  for (let i = 0; i < arrayLength; i++) {
    let item = props.questions[i]
    // Only flag as error if there's a value AND it's invalid
    if (item.answer != null && item.answer.length > 0) {
      if (item.answer < 1 || item.answer > 5) {
        pageError.value = true
        break // Exit early since we found an error
      }
    }
  }
}

// Count how many questions have invalid values (for display purposes)
const invalidAnswersCount = computed(() => {
  return props.questions.filter(question => {
    const value = question.answer
    return value && value.length > 0 && (value < 1 || value > 5)
  }).length
})
</script>

<template>
  <app-layout :quizdone="false">
    <template #header>
      <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ quiz.quiz_name }}
        </h2>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-4">
            <h3 class="font-bold">Instructions</h3>
            <p>Score these statements on a scale of 1 to 5, where 5 is very true and 1 is not true at all. Record your score in the box provided.</p>
            <p>try to avoid scoring a statement with a 3 – push yourself to either a 4 or a 2 instead.</p>
            <div
              v-if="pageError"
              class="bg-red-300 text-red-700 border-2 border-red-700 rounded p-2 m-2"
            >
              <span v-if="invalidAnswersCount > 0">
                {{ invalidAnswersCount }} answer(s) have invalid values. Please correct them. (Valid values are 1-5)
              </span>
              <span v-else>
                One or more of your answers have incorrect values. Please correct them. (You must choose a value from 1 to 5)
              </span>
            </div>
          </div>
          <form @submit.prevent="submit">
            <table class="w-full  table-auto">
              <thead>
                <tr class="text-left bold">
                  <th class="pl-2 pr-2 pt-6 pb-4">#</th>
                  <th class="pl-2 pt-6 pb-4">Question</th>
                  <th class="pl-6 pt-6 pb-4 pr-2">Answer</th>
                </tr>
              </thead>
              <tbody>
                <answer-input
                  @check-all-answers="checkForPageError"
                  v-for="(question, index) in questions"
                  :key="question.id"
                  class="hover:bg-gray-200 focus-within:bg-gray-100"
                  :questionnumber="question.questionnumber"
                  :questiontext="question.questiontext"
                  :model-value="question.answer"
                  @update:model-value="(value) => question.answer = value"
                >
                </answer-input>
              </tbody>
            </table>
            <div
              v-if="pageError"
              class="bg-red-300 text-red-700 border-2 border-red-700 rounded p-2 m-2"
            >
              <span v-if="invalidAnswersCount > 0">
                {{ invalidAnswersCount }} answer(s) have invalid values. Please correct the highlighted rows. (Valid values are 1-5)
              </span>
              <span v-else>
                One or more of your answers is incorrect or missing. Please correct them. (You must type in a value from 1 to 5 - blanks are not allowed)
              </span>
            </div>
            <div class="flex justify-end mr-2 pt-2 pb-6">
              <PrimaryButton> Save Your Answers </PrimaryButton>
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