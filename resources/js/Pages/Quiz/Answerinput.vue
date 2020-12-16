<template>
  <tr class="hover:bg-gray-200 focus-within:bg-gray-100">
    <td class="border-t p-2 pr-2 mb-2">
      {{ questionnumber }}
    </td>
    <td class="border-t p-2 mt-4">
      {{ questiontext }}
      <div
        v-if="errorflag"
        class="bg-red-300 text-red-700 border-2 border-red-700 rounded p-2 my-2"
      >
        {{ error }}
      </div>
    </td>
    <td class="border-t p-2 mt-4">
      <input
        class="form-input rounded-md shadow-sm m-3 w-24"
        :class="inputerrClass"
        :value="value"
        @input="updateValue($event.target.value)"
        ref="input"
      />
    </td>
  </tr>
</template>

<script>
export default {
  props: {
    questionnumber: Number,
    questiontext: String,
    value: String,
  },
  data() {
    return {
      errorflag: false,
      error: "",
      inputerrClass: "",
    };
  },
  methods: {
    focus() {
      this.$refs.input.focus();
    },

    updateValue(value) {
      // Atttach validation + sanitization here.
      if (value >= 0 && value <= 10) {
        this.errorflag = false;
        this.inputerrClass = "";
      } else {
        this.errorflag = true;
        this.error = "You must type a value between 0 and 10. (A blank answer is not allowed.)";
        this.inputerrClass = "bg-red-200";
      }

      this.$emit("input", value);
      // After we update the value, trigger function in parent to loop thru all answers for validity
      this.$emit("check-all-answers");
    },
  },
};
</script>

