// resources/js/composables/useEventBus.js
import { ref, reactive } from 'vue'

// Global reactive state
const state = reactive({
  quizDone: false,
  userId: null,
  timelineDone: false
})

// Event handlers storage
const eventHandlers = reactive({})

export function useEventBus() {
  // Emit an event
  const emit = (event, payload) => {
    console.log(`Event emitted: ${event}`, payload)
    if (eventHandlers[event]) {
      eventHandlers[event].forEach(handler => {
        try {
          handler(payload)
        } catch (error) {
          console.error(`Error in event handler for ${event}:`, error)
        }
      })
    }
  }

  // Listen to an event
  const on = (event, handler) => {
    if (!eventHandlers[event]) {
      eventHandlers[event] = []
    }
    eventHandlers[event].push(handler)
    
    // Return unsubscribe function
    return () => off(event, handler)
  }

  // Remove event listener
  const off = (event, handler) => {
    if (eventHandlers[event]) {
      const index = eventHandlers[event].indexOf(handler)
      if (index > -1) {
        eventHandlers[event].splice(index, 1)
      }
    }
  }

  // Clear all handlers for an event
  const clear = (event) => {
    if (eventHandlers[event]) {
      eventHandlers[event] = []
    }
  }

  // Convenience methods for your specific use cases
  const setQuizDone = (value) => {
    state.quizDone = value
    emit('quiz-done', { quizDone: value })
  }

  const setUserId = (id) => {
    state.userId = id
    emit('user-id-changed', { userId: id })
  }

  const setTimelineDone = (value) => {
    state.timelineDone = value
    emit('timeline-done', { timelineDone: value })
  }

  return {
    // State (read-only access)
    state: readonly(state),
    // Event methods
    emit,
    on,
    off,
    clear,
    // Convenience setters
    setQuizDone,
    setUserId,
    setTimelineDone
  }
}

// For backward compatibility during migration
export default useEventBus