<template>
  <el-menu
    v-if="['admin'].includes($store.getters.contextRole)"
    :default-active="index"
    mode="horizontal"
    @select="handleSelect"
  >
    <el-menu-item index="/dashboard/temoignages"> Témoignages </el-menu-item>
    <el-menu-item index="/dashboard/temoignages/relances">
      Relances
    </el-menu-item>
  </el-menu>
</template>

<script>
export default {
  props: {
    index: {
      type: String,
      required: true,
    },
  },
  methods: {
    handleSelect(index) {
      this.$router.push(`${index}`)
    },
  },
}
</script>
