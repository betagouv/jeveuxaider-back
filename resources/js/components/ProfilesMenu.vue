<template>
  <el-menu :default-active="index" mode="horizontal" @select="handleSelect">
    <el-menu-item index="/dashboard/profiles">Tous</el-menu-item>
    <el-menu-item index="/dashboard/profiles/referents">Référents</el-menu-item>
    <el-menu-item index="/dashboard/profiles/responsables"
      >Responsables</el-menu-item
    >
  </el-menu>
</template>

<script>
export default {
  name: 'ProfilesMenu',
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
