<template>
  <el-menu :default-active="index" mode="horizontal" @select="handleSelect">
    <el-menu-item index="/dashboard/contents/faqs">Faqs</el-menu-item>
    <el-menu-item index="/dashboard/contents/releases">Releases</el-menu-item>
    <el-menu-item index="/dashboard/contents/pages">Pages</el-menu-item>
    <el-menu-item index="/dashboard/contents/departments"
      >Départements</el-menu-item
    >
    <el-menu-item index="/dashboard/contents/documents">Documents</el-menu-item>
    <el-menu-item index="/dashboard/contents/thematiques"
      >Thématiques</el-menu-item
    >
    <el-menu-item index="/dashboard/contents/mission-templates"
      >Modèles</el-menu-item
    >
    <el-menu-item index="/dashboard/contents/tags">Tags</el-menu-item>
  </el-menu>
</template>

<script>
export default {
  name: 'ContentsMenu',
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
