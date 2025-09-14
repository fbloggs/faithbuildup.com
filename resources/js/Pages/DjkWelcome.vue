<script setup>
import { Link } from '@inertiajs/vue3'
//import JetApplicationLogo from '@/Jetstream/ApplicationLogo'
import eventBus from "@/Utilities/Eventbus.vue"

// Define props
const props = defineProps({
    quizdone: Boolean,
    userid: Number
})
</script>

<template>
    <div>
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <div>
                <!-- image could go here <img src="images/wcflogo.png" class="block h-12 w-auto" /> -->
            </div>

            <div class="mt-8 text-2xl">
                Welcome to your Faith Balance and Faith Time Line Dashboard!
            </div>

            <div v-if="!quizdone" class="mt-6 text-gray-500">
                Click <Link :href="route('quizzes.show', {quizid: 1})" class="underline font-semibold">
                    here
                </Link> or on the 'Quiz' link at the top of the page to determine your Faith Balance Profile.
            </div>

            <div v-if="quizdone" class="mt-6 text-gray-500">
               View Your Faith Balance Profile <Link :href="route('quizzes.results', { quizid: 1})" class="underline font-semibold">here.</Link>
            </div>

            <div class="mt-6 text-gray-500">
                Click <Link :href="route('timelines.show')" class="underline font-semibold">
                    here
                </Link> or on the 'Faith Time Line' link at the top of the page to complete or review your Faith Time Line.
            </div>
        </div>
    </div>
</template>