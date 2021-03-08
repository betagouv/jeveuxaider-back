<template>
  <div>
    <i
      class="el-icon-close cursor-pointer absolute text-xl"
      style="right: 32px; top: 16px"
      @click="handleCloseVolet"
    />
    <slot> VOLET CONTENT </slot>
    <!-- <slot name="content" :row="row"> VOLET CONTENT </slot> -->
  </div>
</template>

<script>
// import { mapGetters } from 'vuex'

export default {
  // computed: {
  //   ...mapGetters({
  //     row: 'volet/row',
  //   }),
  // },
  methods: {
    handleCloseVolet() {
      this.$store.commit('volet/hide')
    },
  },
}
</script>
