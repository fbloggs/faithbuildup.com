<template>
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Users List
      </h2>
    </template>
    <div>
      <div class="max-w-7xl mx-auto py-5 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl p-2 sm:p-6 sm:rounded-lg">
          <div class="sm:mb-6 flex justify-between items-center">
            <SearchFilter
              v-model="form.search"
              class="w-full max-w-md mr-4 text-sm"
              @reset="reset"
            />
          </div>
          
          <table class="w-full whitespace-nowrap">
            <thead>
              <tr class="text-left font-bold">
                <th class="pt-4 sm:pr-6 sm:pb-2">User Id</th>
                <th class="sm:pr-6 pt-4 sm:pb-2">Email</th>
                <th class="sm:pr-6 pt-4 sm:pb-2">Name</th>
                <th class="sm:pr-6 pt-4 sm:pb-2">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="user in users.data"
                :key="user.id"
                class="hover:bg-gray-100 focus-within:bg-gray-100"
              >
                <td class="border-t c">
                  {{ user.id }}
                </td>
                <td class="border-t c">
                  {{ user.email }}
                </td>
                <td class="border-t leading-none sm:leading-tight text-sm">
                  <Link
                    class="py-4 flex items-center focus:text-indigo-500"
                    :href="route('user.showquiz', user.id)"
                  >
                    {{ user.name }}
                  </Link>
                </td>
                <td class="border-t c">
                  <Link 
                    v-if="user.quizDone" 
                    class="btn-primary transition duration-300 ease-in-out focus:outline-none focus:shadow-outline bg-indigo-500 hover:bg-indigo-900 text-white font-normal py-2 px-4 mr-1 rounded inline-flex" 
                    :href="route('quizzes.anotheruser', { 'quizid': 1, 'userid': user.id })"
                  >
                    <Icon name="eye" class="w-4 h-4 mr-2" />
                    <span>Show Quiz</span>
                  </Link>

                  <Link 
                    v-if="user.timeLineDone" 
                    class="btn-primary transition duration-300 ease-in-out focus:outline-none focus:shadow-outline bg-indigo-500 hover:bg-indigo-900 text-white font-normal py-2 px-4 mr-1 rounded inline-flex" 
                    :href="route('timelines.anotheruser', user.id)"
                  >
                    <Icon name="eye" class="w-4 h-4 mr-2" />
                    <span>Show Timeline</span>
                  </Link>
                </td>
              </tr>
              <tr v-if="users.data.length === 0">
                <td class="border-t px-6 py-4" colspan="4">No Accounts found.</td>
              </tr>
            </tbody>
          </table>

          <Pagination :links="users.links" />
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { reactive, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'

import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Shared/Pagination.vue'
import Icon from '@/Shared/Icon.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'

import pickBy from 'lodash/pickBy'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'

// Define props using defineProps
const props = defineProps({
  userid: Number,
  users: Object,
  filters: [Array, Object],
  errors: Object,
})

// Reactive form data
const form = reactive({
  search: props.filters?.search || null,
})

// Watch for form changes with throttling
watch(
  form,
  throttle(() => {
    const query = pickBy(form)
    router.visit(
      route(
        'users.index',
        Object.keys(query).length ? query : { remember: 'forget' }
      ),
      {
        preserveState: true,
        replace: true,
      }
    )
  }, 150),
  { deep: true }
)

// Methods
const reset = () => {
  Object.assign(form, mapValues(form, () => null))
}
</script>

<style>
.c {
  @apply px-6 py-4;
}
</style>