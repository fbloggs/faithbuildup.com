<template>
  <div class="flex items-center">
    <div class="flex w-full bg-white shadow rounded">
      
      <input class="relative w-full px-6 py-3 rounded-r focus:shadow-outline bg-cool-gray-100" autocomplete="off" type="text" name="search" placeholder="Search…" :value="value" @input="$emit('input', $event.target.value)">
    </div>
    <button class="ml-3 text-sm text-gray-700 hover:text-gray-900 focus:text-indigo-500" type="button" @click="$emit('reset')">Reset</button>
  </div>
</template>

<script>

export default {
  components: {
    
  },
  props: {
    value: String,
    maxWidth: {
      type: Number,
      default: 300,
    },
  },
}
</script>
