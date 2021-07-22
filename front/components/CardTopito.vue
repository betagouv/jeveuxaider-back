<template>
  <div class="">
    <div class="text-lg font-bold uppercase">{{ title }}</div>
    <div class="border border-gray-200 rounded-lg">
      <template v-if="loading">
        <div class="py-24 flex items-center justify-center">
          <IconSpin class="animate-spin h-10 w-10 text-gray-400" />
        </div>
      </template>
      <template v-else>
        <slot></slot>
      </template>
    </div>
  </div>
</template>

<script>
import IconSpin from '@/components/icons/IconSpin'

export default {
  components: {
    IconSpin,
  },
  props: {
    title: {
      type: String,
      required: true,
    },
    loading: {
      type: Boolean,
      required: true,
    },
  },
}
</script>

<style></style>
