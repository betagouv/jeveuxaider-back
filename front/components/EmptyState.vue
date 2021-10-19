<template>
  <div class="text-center">
    <slot></slot>
    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ title }}</h3>
    <p v-if="subtitle" class="mt-1 text-sm text-gray-500">
      {{ subtitle }}
    </p>
    <nuxt-link :to="buttonLink" class="mt-6">
      <button
        type="button"
        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
      >
        {{ buttonTitle }}
      </button>
    </nuxt-link>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      required: true,
    },
    subtitle: {
      type: String,
      default: null,
    },
    buttonTitle: {
      type: String,
      default: 'Ajouter',
    },
    buttonLink: {
      type: String,
      required: true,
    },
  },
}
</script>
