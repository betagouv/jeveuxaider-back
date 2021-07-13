<template>
  <div class="">
    <i
      class="el-icon-close cursor-pointer absolute text-xl"
      style="right: 32px; top: 16px"
      @click="handleCloseVolet"
    />
    <slot> </slot>
  </div>
</template>

<script>
export default {
  methods: {
    handleCloseVolet() {
      this.$store.commit('volet/hide')
    },
  },
}
</script>
