<template>
  <el-menu :default-active="index" mode="vertical" @select="handleSelect">
    <el-menu-item index="/user/infos">Informations personnelles</el-menu-item>
    <el-menu-item index="/user/preferences">Préférences</el-menu-item>
    <el-menu-item index="/user/settings">Paramètres de compte</el-menu-item>
  </el-menu>
</template>

<script>
export default {
  name: 'ContentsMenu',
  computed: {
    index() {
      return 'TODO'
    },
  },
  methods: {
    handleSelect(index) {
      this.$router.push(`${index}`)
    },
  },
}
</script>

<style lang="sass" scoped>
.el-menu
  @apply border-0
  .el-menu-item
    &:hover
      background: none
      @apply text-primary
</style>
