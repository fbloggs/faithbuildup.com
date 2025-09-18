// composables/useInputValidation.js
import { ref } from 'vue'

export function useInputValidation(min = -100, max = 100) {
  const errorflag = ref(false)
  const error = ref("")
  const inputerrClass = ref("")

  const validate = (value) => {
    const numValue = Number(value)
    
    if (value === '' || value === null) {
      errorflag.value = false
      inputerrClass.value = ""
      return true
    }
    
    if (numValue >= min && numValue <= max && !isNaN(numValue)) {
      errorflag.value = false
      inputerrClass.value = ""
      return true
    }
    
    errorflag.value = true
    error.value = `You must type a value between ${min} and ${max}.`
    inputerrClass.value = "bg-red-200"
    return false
  }

  return { errorflag, error, inputerrClass, validate }
}