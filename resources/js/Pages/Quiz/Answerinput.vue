<script setup>
import { ref, computed, nextTick } from 'vue'

const props = defineProps({
  questionnumber: {
    type: Number,
    required: true
  },
  questiontext: {
    type: String,
    required: true
  },
  modelValue: {
    type: [String, Number],
    default: ''
  },
})

const emit = defineEmits(['update:modelValue', 'check-all-answers'])

const errorflag = ref(false)
const error = ref('')
const input = ref(null)

const hasInvalidValue = computed(() => {
  const value = props.modelValue
  // Check if there's a value AND it's not between 1 and 5
  return value && value.toString().length > 0 && (value < 1 || value > 5)
})

const inputErrorClass = computed(() => {
  return hasInvalidValue.value ? 'bg-red-100 border-red-400 focus:border-red-500' : 'border-gray-300 focus:border-blue-500'
})

const rowErrorClass = computed(() => {
  return hasInvalidValue.value 
    ? 'bg-red-50' 
    : 'hover:bg-gray-100 focus-within:bg-blue-50'
})

const isAnswered = computed(() => {
  const value = props.modelValue
  return value && value >= 1 && value <= 5
})

const focus = async () => {
  await nextTick()
  input.value?.focus()
}

const updateValue = (value) => {
  // Convert to number if it's a valid number, otherwise keep as string for validation
  const numericValue = value === '' ? '' : Number(value)
  
  // Validate the input
  if (value && value.length > 0) {
    if (numericValue >= 1 && numericValue <= 5) {
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

  emit('update:modelValue', numericValue)
  
  // Trigger parent validation check
  nextTick(() => {
    emit('check-all-answers')
  })
}

const handleKeydown = (event) => {
  // Allow: backspace, delete, tab, escape, enter
  if ([8, 9, 27, 13, 46].indexOf(event.keyCode) !== -1 ||
      // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
      (event.keyCode === 65 && event.ctrlKey === true) ||
      (event.keyCode === 67 && event.ctrlKey === true) ||
      (event.keyCode === 86 && event.ctrlKey === true) ||
      (event.keyCode === 88 && event.ctrlKey === true)) {
    return
  }
  
  // Allow only numbers 1-5
  if ((event.shiftKey || (event.keyCode < 49 || event.keyCode > 53)) && 
      (event.keyCode < 97 || event.keyCode > 101)) {
    event.preventDefault()
  }
}

// Expose focus method for parent component
defineExpose({
  focus
})
</script>

<template>
  <tr :class="rowErrorClass" class="transition-colors duration-150">
    <td class="border-t border-gray-200 p-3 text-center font-medium">
      {{ questionnumber }}
    </td>
    
    <td class="border-t border-gray-200 p-3">
      <div class="break-words">
        {{ questiontext }}
      </div>
      
      <!-- Error message -->
      <div
        v-if="errorflag"
        class="mt-2 p-2 bg-red-100 border border-red-400 text-red-700 rounded text-sm"
        role="alert"
      >
        {{ error }}
      </div>
    </td>
    
    <td class="border-t border-gray-200 p-3">
      <div class="relative">
        <input
          ref="input"
          type="number"
          min="1"
          max="5"
          :value="modelValue"
          @input="updateValue($event.target.value)"
          @keydown="handleKeydown"
          class="w-16 px-3 py-2 text-center border rounded-md shadow-sm transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
          :class="inputErrorClass"
          placeholder="1-5"
          aria-label="Answer (1-5)"
          :aria-describedby="errorflag ? `error-${questionnumber}` : null"
        />
        
        <!-- Visual indicator for answered questions -->
        <div 
          v-if="isAnswered" 
          class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full flex items-center justify-center"
          title="Answered"
        >
          <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
          </svg>
        </div>
      </div>
    </td>
  </tr>
</template>

<style scoped>
/* Remove number input spinners */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type="number"] {
  -moz-appearance: textfield;
}

/* Enhanced focus styles */
input:focus {
  transform: scale(1.02);
}

/* Smooth transitions */
tr {
  transition: background-color 0.15s ease-in-out;
}

input {
  transition: all 0.15s ease-in-out;
}
</style>