<script setup>
import { ref, computed, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import AnswerInput from '@/Pages/Quiz/Answerinput.vue'

const props = defineProps({
  userid: Number,
  quiz: Object,
  questions: Array,
  answers: Array, // Added to receive existing answers
  errors: Object,
})

const sending = ref(false)
const pageError = ref(false)

const form = useForm({
  userid: props.userid,
  answers: [],
})

// Initialize questions with existing answers
onMounted(() => {
  // Questions should already have answers merged in from the controller
  // This ensures reactivity is properly set up
  props.questions.forEach(question => {
    if (!question.hasOwnProperty('answer')) {
      question.answer = ''
    }
  })
})

const submit = () => {
  const hasErrors = checkForm(props.questions)

  if (!hasErrors) {
    form.answers = props.questions.map(question => ({
      questionnumber: question.questionnumber,
      answer: question.answer,
    }))

    form.put(route('quizzes.update', props.quiz.id), {
      onStart: () => (sending.value = true),
      onFinish: () => (sending.value = false),
    })
  }
}

/**
 * Validate all answers on form submission
 * Returns true if there are errors, false if valid
 */
const checkForm = (questions) => {
  pageError.value = false

  for (const question of questions) {
    if (!question.answer || question.answer < 1 || question.answer > 5) {
      pageError.value = true
      break
    }
  }

  return pageError.value
}

/**
 * Real-time validation for display purposes
 * Only flags errors for non-empty invalid values
 */
const checkForPageError = () => {
  pageError.value = false

  for (const question of props.questions) {
    if (question.answer != null && question.answer.length > 0) {
      if (question.answer < 1 || question.answer > 5) {
        pageError.value = true
        break
      }
    }
  }
}

/**
 * Count questions with invalid values for error display
 */
const invalidAnswersCount = computed(() => {
  return props.questions.filter(question => {
    const value = question.answer
    return value && value.length > 0 && (value < 1 || value > 5)
  }).length
})

/**
 * Check if all questions have been answered
 */
const allQuestionsAnswered = computed(() => {
  return props.questions.every(question => 
    question.answer && question.answer >= 1 && question.answer <= 5
  )
})

/**
 * Progress indicator
 */
const answeredCount = computed(() => {
  return props.questions.filter(question => 
    question.answer && question.answer >= 1 && question.answer <= 5
  ).length
})

const totalQuestions = computed(() => props.questions.length)
</script>

<template>
  <AppLayout :quizdone="false">
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
            <p class="mb-2">
              Score these statements on a scale of 1 to 5, where 5 is very true and 1 is not true at all. 
              Record your score in the box provided.
            </p>
            <p class="mb-4">
              Try to avoid scoring a statement with a 3 — push yourself to either a 4 or a 2 instead.
            </p>
            
            <!-- Progress indicator -->
            <div class="mb-4 p-3 bg-gray-100 rounded">
              <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium">Progress</span>
                <span class="text-sm text-gray-600">
                  {{ answeredCount }} of {{ totalQuestions }} answered
                </span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2">
                <div 
                  class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                  :style="{ width: `${(answeredCount / totalQuestions) * 100}%` }"
                ></div>
              </div>
            </div>

            <!-- Error messages -->
            <div
              v-if="pageError"
              class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"
              role="alert"
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
            <table class="w-full table-auto">
              <thead>
                <tr class="text-left font-bold bg-gray-50">
                  <th class="pl-2 pr-2 pt-6 pb-4 w-16">#</th>
                  <th class="pl-2 pt-6 pb-4">Question</th>
                  <th class="pl-6 pt-6 pb-4 pr-2 w-24">Answer</th>
                </tr>
              </thead>
              <tbody>
                <AnswerInput
                  v-for="question in questions"
                  :key="question.id"
                  :questionnumber="question.questionnumber"
                  :questiontext="question.questiontext"
                  :model-value="question.answer"
                  @update:model-value="(value) => question.answer = value"
                  @check-all-answers="checkForPageError"
                />
              </tbody>
            </table>

            <!-- Bottom error message -->
            <div
              v-if="pageError"
              class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded m-4"
              role="alert"
            >
              <span v-if="invalidAnswersCount > 0">
                {{ invalidAnswersCount }} answer(s) have invalid values. Please correct the highlighted rows. (Valid values are 1-5)
              </span>
              <span v-else>
                One or more of your answers is incorrect or missing. Please correct them. 
                (You must type in a value from 1 to 5 - blanks are not allowed)
              </span>
            </div>

            <div class="flex justify-between items-center p-4">
              <div class="text-sm text-gray-600">
                <span v-if="allQuestionsAnswered" class="text-green-600 font-medium">
                  ✓ All questions answered
                </span>
                <span v-else>
                  {{ totalQuestions - answeredCount }} question(s) remaining
                </span>
              </div>
              
              <PrimaryButton 
                :disabled="sending || !allQuestionsAnswered"
                :class="{ 'opacity-50 cursor-not-allowed': sending || !allQuestionsAnswered }"
              >
                <span v-if="sending">Saving...</span>
                <span v-else>Save Your Answers</span>
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
tbody tr:nth-child(odd) td {
  --tw-bg-opacity: 1;
  background-color: rgba(249, 250, 251, var(--tw-bg-opacity));
}

/* Enhanced hover states */
tbody tr:hover td {
  --tw-bg-opacity: 1;
  background-color: rgba(243, 244, 246, var(--tw-bg-opacity));
}

/* Focus states */
tbody tr:focus-within td {
  --tw-bg-opacity: 1;
  background-color: rgba(239, 246, 255, var(--tw-bg-opacity));
}
</style>