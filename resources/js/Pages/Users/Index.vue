<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Accounts List
      </h2>
    </template>
    <div>
      <div class="max-w-7xl mx-auto py-5 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl p-2 sm:p-6 sm:rounded-lg">
          <div class="sm:mb-6 flex justify-between items-center">
          <search-filter
            v-model="form.search"
            class="w-full max-w-md mr-4 text-sm"
            @reset="reset"
          ></search-filter>

          </div>
          <table class="w-full whitespace-nowrap">
            <tr class="text-left font-bold">
              <th class="pt-4 sm:pr-6 sm:pb-2">User Id</th>
              <th class="sm:pr-6 pt-4 sm:pb-2">Email</th>
              <th class="sm:pr-6 pt-4 sm:pb-2">Name</th>
              <th class="sm:pr-6 pt-4 sm:pb-2">Actions</th>

            </tr>
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
                <inertia-link
                  class="py-4 flex items-center focus:text-indigo-500"
                  :href="route('user.showquiz', user.id)"
                >
                  {{ user.name }}
                </inertia-link>
              </td>
              <td class="border-t c">
                  <inertia-link v-if="user.quizDone" class="btn-primary transition duration-300 ease-in-out focus:outline-none focus:shadow-outline bg-indigo-500 hover:bg-indigo-900 text-white font-normal py-2 px-4 mr-1 rounded inline-flex" :href="route('quizzes.anotheruser', { 'quizid' : 1, 'userid' : user.id})">
                      <icon name="eye" class="w-4 h-4 mr-2"></icon>
                      <span>Show Quiz</span>
                      </inertia-link>

                            <inertia-link v-if="user.timeLineDone" class="btn-primary transition duration-300 ease-in-out focus:outline-none focus:shadow-outline bg-indigo-500 hover:bg-indigo-900 text-white font-normal py-2 px-4 mr-1 rounded inline-flex" :href="route('timelines.anotheruser', user.id)">
                      <icon name="eye" class="w-4 h-4 mr-2"></icon>
                      <span>Show Timeline</span>
                      </inertia-link>
              </td>
            </tr>
            <tr v-if="users.data.length === 0">
              <td class="border-t px-6 py-4" colspan="4">No Accounts found.</td>
            </tr>
          </table>

          <pagination :links="users.links" />
        </div>
      </div>
    </div>
  </app-layout>
</template>
     <style>
</style>
    <script>
import AppLayout from "@/Layouts/AppLayout";
import Pagination from "@/Shared/Pagination";
import Icon from "@/Shared/Icon";

import pickBy from "lodash/pickBy";
import throttle from "lodash/throttle";
import mapValues from "lodash/mapValues";
import SearchFilter from "@/Shared/SearchFilter";

export default {
  components: {
    AppLayout,
    Pagination,
    Icon,
    pickBy,
    throttle,
    mapValues,
    SearchFilter,
  },

  props: {
    userid: Number,
    users: Object,
    filters:  [Array, Object],
    errors: Object,
  },

  data() {
    return {
      form: {
        search: this.filters.search,
      },
    };
  },

  watch: {
    form: {
      handler: throttle(function () {
        let query = pickBy(this.form);
        this.$inertia.replace(
          this.route(
            "users.index",
            Object.keys(query).length ? query : { remember: "forget" }
          )
        );
      }, 150),
      deep: true,
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null);
    },
  },
};
</script>

