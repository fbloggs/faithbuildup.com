<template>
  <app-layout :quizdone="false">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ quiz.quiz_name }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-4">
            <h3 class="font-bold">Instructions</h3>
            <p>
              Score these statements on a scale of 1 to 5, where 5 is very true
              and 1 is not true at all. Record your score in the box provided.</p>
              <p>
              Hint: try to avoid scoring a statement with a 3 – push yourself to
              either a 4 or a 2 instead.
            </p>

            Score these statements on a scale, ZERO to ten , with ten being very true and Zero  being not true at all. Record your score in the box provided.
            Hint: try to avoid scoring a statement with a 5 – push yourself one side, higher or lower. There are no right or wrong answers to these questions. 
            <div
              v-if="pageError"
              class="bg-red-300 text-red-700 border-2 border-red-700 rounded p-2 m-2"
            >
              One or more of your answers have incorrect answers. Please correct
              them. (You must choose a value from 1 to 5)
            </div>
          </div>
          <form @submit.prevent="submit">
            <table class="w-full whitespace-nowrap table-auto">
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
                  v-model="question.answer"
                >
                </answer-input>
              </tbody>
            </table>
            <div
              v-if="pageError"
              class="bg-red-300 text-red-700 border-2 border-red-700 rounded p-2 m-2"
            >
              One or more of your answers is incorrect or missing. Please
              correct them. (You must type in a value from 1 to 5)
            </div>
            <div class="flex justify-end mr-2 pt-2 pb-6">
              <jet-button> Save Your Answers </jet-button>
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
import AnswerInput from "@/Pages/Quiz/Answerinput";
import JetInputError from "@/Jetstream/InputError";
import JetLabel from "@/Jetstream/Label";

export default {
  components: {
    AppLayout,
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetInput,
    AnswerInput,
    JetInputError,
    JetLabel,
  },

  props: {
    userid: Number,
    quiz: Object,
    questions: Array,
    errors: Object,
  },
  data() {
    return {
      sending: false,
      pageError: false,
      clienterrors: [],
      form: this.$inertia.form(
        {
          userid: this.userid,
          answers: Array,
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
      let error = this.checkForm(this.questions);

      if (!error) {
        this.form.answers = Array.from(this.questions, function (element) {
          return {
            questionnumber: element.questionnumber,
            answer: element.answer,
          };
        });

        this.$inertia.put(
          this.route("quizzes.update", this.quiz.id),
          this.form,
          {
            onStart: () => (this.sending = true),
            onFinish: () => (this.sending = false),
          }
        );
      }
    },

    checkForm(questions) {
      this.pageError = false;

      var arrayLength = questions.length;

      for (var i = 0; i < arrayLength; i++) {
        let item = questions[i];
        if (!item.answer || item.answer < 1 || item.answer > 5) {
          this.pageError = true;
        }
      }

      let pageError = this.pageError;
      return pageError;
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
        if (item.answer != null && item.answer.length > 0) {
          if (item.answer < 1 || item.answer > 5) {
            this.pageError = true;
          }
        }
      }
    },
  },
};
</script>
