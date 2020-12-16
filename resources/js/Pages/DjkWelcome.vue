<template>
    <div>
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <div>
                <!-- image could go here <img src="images/wcflogo.png" class="block h-12 w-auto" /> -->
            </div>

            <div class="mt-8 text-2xl">
                Welcome to your Faith Balance Dashboard!
            </div>

            <div v-if="!quizdone" class="mt-6 text-gray-500">
                Click <inertia-link :href="route('quizzes.show', {id: 1})" class="underline font-semibold">
                                here
                            </inertia-link> or on the 'Quiz' link at the top of the page to take the Faith Balance Assessment.
            </div>

            <div v-if="quizdone" class="mt-6 text-gray-500">
               View Your Faith Balance Assessment results  <inertia-link :href="route('quizzes.results', {id: 1})" class="underline font-semibold">here.</inertia-link>
            </div>

        </div>

      
    </div>
</template>

<script>
    import JetApplicationLogo from '@/Jetstream/ApplicationLogo'
    import eventBus from "@/Utilities/Eventbus";

    export default {
        components: {
            JetApplicationLogo,
            eventBus, 
        },
        
      props : { 
          quizdone: Boolean,
      },

        data() {
            return {
               
            };
        }, 
     }
</script>
