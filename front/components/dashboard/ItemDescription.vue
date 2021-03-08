<template>
  <div
    :class="[containerClass]"
    class="text-xs leading-snug text-gray-500 flex items-baseline"
  >
    <i class="el-icon-info text-primary mr-2 flex-none" />
    <div class="flex-1">
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  name: 'ItemDescription',
  props: {
    containerClass: {
      type: String,
      default: 'mt-2',
    },
  },
}
</script>
