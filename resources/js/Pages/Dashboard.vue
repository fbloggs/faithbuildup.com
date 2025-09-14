<script setup>
import { onMounted } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import Welcome from '@/Pages/DjkWelcome.vue'
import eventBus from "@/Utilities/Eventbus.vue"

// Define props
const props = defineProps({
    quizdone: Boolean,
    userid: Number
})

// Lifecycle hook equivalent to created()
onMounted(() => {
    if (props.quizdone) { 
        eventBus.$emit("quiz-done")
    } 
})
</script>

<template>
    <app-layout :quizdone="quizdone">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <welcome :quizdone="quizdone" :userid="userid"></welcome>
                </div>
            </div>
        </div>
    </app-layout>
</template>