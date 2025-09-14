<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  questionnumber: Number,
  questiontext: String,
  modelValue: String,
})

const emit = defineEmits(['update:modelValue', 'check-all-answers'])

const errorflag = ref(false)
const error = ref('')
const input = ref(null)

const hasInvalidValue = computed(() => {
  const value = props.modelValue
  // Check if there's a value AND it's not between 1 and 5
  return value && value.length > 0 && (value < 1 || value > 5)
})

const inputErrorClass = computed(() => {
  return hasInvalidValue.value ? 'bg-red-200 border-red-400' : ''
})

const rowErrorClass = computed(() => {
  return hasInvalidValue.value ? 'bg-red-50' : 'hover:bg-gray-200 focus-within:bg-gray-100'
})

const focus = () => {
  input.value.focus()
}

const updateValue = (value) => {
  // Attach validation + sanitization here.
  if (value && value.length > 0) {
    if (value >= 1 && value <= 5) {
      errorflag.value = false
      error.value = ''
    } else {
      errorflag.value = true
      error.value = 'You must type a value between 1 and 5. (A blank answer is not allowed.)'
    }
  } else {
    // Reset error for empty values (will be caught on submit)
    errorflag.value = false
    error.value = ''
  }

  emit('update:modelValue', value)
  // After we update the value, trigger function in parent to loop thru all answers for validity
  emit('check-all-answers')
}

defineExpose({
  focus
})
</script>

<template>
  <tr :class="rowErrorClass">
    <td class="border-t p-2 pr-2 mb-2">
      {{ questionnumber }}
    </td>
    <td class="border-t p-2 mt-4 break-words">
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
        class="form-input rounded-md shadow-sm m-3 w-12"
        :class="inputErrorClass"
        :value="modelValue"
        @input="updateValue($event.target.value)"
        ref="input"
      />
    </td>
  </tr>
</template>