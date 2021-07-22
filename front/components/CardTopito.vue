<template>
  <div class="">
    <div class="text-lg font-bold uppercase">{{ title }}</div>
    <div class="border border-gray-200 rounded-lg">
      <template v-if="loading">Loading...</template>
      <template v-else>
        <slot></slot>
      </template>
    </div>
  </div>
</template>

<script>
export default {
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
