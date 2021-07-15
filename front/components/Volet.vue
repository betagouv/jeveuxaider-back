<template>
  <div>
    <div class="flex justify-between items-start mb-6">
      <div v-if="title" class="text-xl font-semibold">{{ title }}</div>
      <i
        class="
          el-icon-close
          cursor-pointer
          text-xl
          font-semibold
          mt-1
          text-gray-400
          hover:text-gray-900
        "
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
