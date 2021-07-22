<template>
  <div>
    <div class="flex justify-between items-end mb-1">
      <div class="flex space-x-2 justify-center">
        <div class="text-lg font-medium">{{ label }}</div>
      </div>
      <router-link
        v-if="link"
        :to="link"
        class="text-xs text-primary hover:text-primary"
      >
        {{ linkLabel }}
      </router-link>
    </div>
    <template v-if="cardLink">
      <router-link
        :to="cardLink"
        target="_blank"
        class="rounded bg-white p-4 border-blue-800 border-2 block"
      >
        <div class="flex flex-col space-y-2 text-xs text-gray-500">
          <slot></slot>
        </div>
      </router-link>
    </template>
    <template v-else>
      <div class="rounded bg-white p-4 border border-gray-200">
        <div class="flex flex-col space-y-2 text-xs text-gray-500">
          <slot></slot>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      default: null,
      required: false,
    },
    link: {
      type: String,
      default: null,
      required: false,
    },
    linkLabel: {
      type: String,
      default: 'Accéder à la fiche',
      required: false,
    },
    cardLink: {
      type: [String, Boolean],
      default: false,
    },
  },
}
</script>

<style lang="sass">
.card-link
  @apply border border-blue-800
</style>
