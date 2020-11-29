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
                    <welcome :quizdone="quizdone" />
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Welcome from '@/Pages/DjkWelcome'
    import eventBus from "@/Utilities/Eventbus";

    

    export default {
        components: {
            AppLayout,
            Welcome,
            eventBus,
        },

        props : ['quizdone'], 

        created () {
            if (this.quizdone) { 
                eventBus.$emit("quiz-done");
              
            } 
             
        }, 
    } 
</script>
