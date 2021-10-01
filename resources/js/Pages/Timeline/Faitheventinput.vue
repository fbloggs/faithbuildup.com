<template>

    <td class="border-t p-2 mt-4">
      <input
        class="form-input rounded-md shadow-sm m-3 w-24"
        :class="inputerrClass"
        :value="value"
        @input="updateValue($event.target.value)"
        ref="input"
      />
    </td>

</template>

<script>
export default {
  props: {
    eventnumber: [Number,String],
    value: [Number, String],
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
      if (value >= -100 && value <= 100) {
        this.errorflag = false;
        this.inputerrClass = "";
      } else {
        this.errorflag = true;
        this.error = "You must type a value between -100 and 100. (A blank answer is not allowed, but zero is ok.)";
        this.inputerrClass = "bg-red-200";
      }

      this.$emit("input", value);

    },
  },
};
</script>

