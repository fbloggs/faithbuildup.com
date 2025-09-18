<template>
  <td class="border-t p-2 mt-4">
    <input
      class="form-input rounded-md shadow-sm m-3 w-24"
      :class="inputerrClass"
      :value="modelValue"
      @input="updateValue($event.target.value)"
      ref="inputRef"
    />
    <div v-if="errorflag" class="text-red-600 text-xs mt-1 mx-3">
      {{ error }}
    </div>
  </td>
</template>

<script setup>
import { ref } from 'vue'
import { useInputValidation } from '@/composables/useInputValidation'

// Props
const props = defineProps({
  eventnumber: [Number, String],
  modelValue: [Number, String],
})

// Emits
const emit = defineEmits(['update:modelValue', 'check-all-answers'])

// Use the validation composable with default range (-100 to 100)
const { errorflag, error, inputerrClass, validate, clearErrors } = useInputValidation()

// Component-specific reactive state
const inputRef = ref(null)

// Methods
const focus = () => {
  inputRef.value?.focus()
}

const updateValue = (value) => {
  // Use the composable's validate function
  const validationResult = validate(value)
  
  // Emit the update event for v-model
  emit("update:modelValue", validationResult.numericValue)
  
  // Emit validation check event
  emit("check-all-answers")
}

// Expose methods for parent component access
defineExpose({
  focus,
  clearErrors
})
</script>