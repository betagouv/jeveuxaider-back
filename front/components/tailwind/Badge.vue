<template>
  <div>
    <span
      class="inline-flex items-center rounded-full font-medium"
      :class="[
        {
          'px-2.5 py-0.5 text-xs': size == 'xs',
          'px-3 py-0.5 text-sm': size == 'sm',
        },
        `bg-${color}-100 text-${color}-800`,
      ]"
    >
      <template v-if="dot">
        <svg
          class="-ml-0.5 mr-1.5 h-2 w-2"
          :class="`text-${color}-400`"
          fill="currentColor"
          viewBox="0 0 8 8"
        >
          <circle cx="4" cy="4" r="3" />
        </svg>
      </template>
      <slot></slot>
    </span>
  </div>
</template>

<script>
export default {
  props: {
    size: {
      type: String,
      default: 'sm',
    },
    dot: {
      type: Boolean,
      default: false,
    },
    color: {
      type: String,
      default: 'gray',
    },
  },
  computed: {},
}
</script>
