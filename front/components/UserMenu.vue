<template>
  <el-menu mode="vertical" @select="handleSelect">
    <el-menu-item
      index="/user/infos"
      :class="{
        'is-active': isActive('user/infos'),
      }"
      >Informations personnelles</el-menu-item
    >
    <el-menu-item
      index="/user/preferences"
      :class="{
        'is-active': isActive('user/preferences'),
      }"
      >Préférences</el-menu-item
    >
    <el-menu-item
      index="/user/competences"
      :class="{
        'is-active': isActive('user/competences'),
      }"
      >Compétences</el-menu-item
    >
    <el-menu-item
      index="/user/settings"
      :class="{
        'is-active': isActive('user/settings'),
      }"
      >Paramètres de compte</el-menu-item
    >
  </el-menu>
</template>

<script>
import MenuActive from '@/mixins/menu-active'

export default {
  name: 'ContentsMenu',
  mixins: [MenuActive],
  methods: {
    handleSelect(index) {
      this.$router.push(`${index}`)
    },
  },
}
</script>

<style lang="postcss" scoped>
.el-menu {
  @apply border-0;
  .el-menu-item {
    background: none;
    &:hover {
      background: none;
      @apply text-primary;
    }
  }
}
</style>
