<template>
  <div class="flex">
    <div class="card-label">{{ label }}</div>
    <div class="text-gray-900 flex-1">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      required: true,
    },
  },
}
</script>

<style></style>
