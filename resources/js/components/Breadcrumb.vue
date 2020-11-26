<template>
  <nav class="bg-white border-b border-gray-200 flex" aria-label="Breadcrumb">
    <ol
      class="max-w-screen-xl w-full mx-auto px-4 flex space-x-4 sm:px-6 lg:px-8"
    >
      <li class="flex">
        <div class="flex items-center">
          <router-link to="/" class="text-gray-400 hover:text-gray-500">
            Accueil
          </router-link>
        </div>
      </li>

      <li v-for="item in items" :key="item.link" class="flex">
        <div class="flex items-center">
          <svg
            class="flex-shrink-0 w-6 h-full text-gray-200"
            viewBox="0 0 24 44"
            preserveAspectRatio="none"
            fill="currentColor"
            xmlns="http://www.w3.org/2000/svg"
            aria-hidden="true"
          >
            <path d="M.293 0l22 22-22 22h1.414l22-22-22-22H.293z" />
          </svg>
          <router-link
            v-if="item.link"
            :to="item.link"
            class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"
            >{{ item.label }}</router-link
          >
          <template v-else>
            <h1
              v-if="item.h1"
              class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"
            >
              {{ item.label }}
            </h1>
            <span
              v-else
              class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"
              >{{ item.label }}</span
            >
          </template>
        </div>
      </li>
    </ol>
  </nav>
</template>

<script>
export default {
  props: {
    items: {
      type: Array,
      required: true,
    },
  },
}
</script>
