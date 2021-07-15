<template>
  <div>
    <div class="flex justify-between items-start mb-6">
      <div v-if="title" class="text-xl font-semibold">{{ title }}</div>
      <i
        class="el-icon-close cursor-pointer text-xl"
        style="right: 32px; top: 16px"
        @click="handleCloseVolet"
      />
    </div>
    <slot> </slot>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      required: false,
      default: null,
    },
  },
  methods: {
    handleCloseVolet() {
      this.$store.commit('volet/hide')
    },
  },
}
</script>
