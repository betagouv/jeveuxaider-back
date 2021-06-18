<template>
  <div
    v-click-outside="() => (isOpen = false)"
    class="relative inline-block text-left"
  >
    <div>
      <button
        id="options-menu"
        type="button"
        class="
          px-2
          py-2
          bg-white
          rounded
          flex
          items-center
          text-gray-400
          hover:text-gray-600
          focus:ring-2
          focus:ring-offset-2
          focus:ring-offset-gray-100
          focus:ring-indigo-500
        "
        aria-haspopup="true"
        aria-expanded="true"
        @click="isOpen = !isOpen"
      >
        <span class="sr-only">Open options</span>
        <!-- Heroicon name: dots-vertical -->
        <svg
          class="h-5 w-5"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 20 20"
          fill="currentColor"
          aria-hidden="true"
        >
          <path
            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"
          />
        </svg>
      </button>
    </div>

    <div
      v-show="isOpen"
      class="
        origin-top-right
        absolute
        z-10
        right-0
        mt-2
        w-64
        rounded-md
        shadow-lg
        bg-white
        ring-1 ring-black ring-opacity-5
      "
      @click="isOpen = !isOpen"
    >
      <div
        class="py-1 cursor-pointer"
        role="menu"
        aria-orientation="vertical"
        aria-labelledby="options-menu"
      >
        <slot name="menu"></slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isOpen: false,
    }
  },
}
</script>
