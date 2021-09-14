<template>
  <div class="border border-gray-200 rounded-lg">
    <div class="p-4 flex justify-between items-center">
      <div>
        <div class="text-md font-bold uppercase">
          {{ title }}
        </div>
        <div class="text-sm font-bold text-gray-400">
          {{ subtitle }}
        </div>
      </div>
      <div>
        <slot name="actions"></slot>
      </div>
    </div>
    <div class="border-t border-gray-200">
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
    subtitle: {
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
