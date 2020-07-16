<template>
  <el-menu :default-active="index" mode="horizontal" @select="handleSelect">
    <el-menu-item index="/dashboard/trash/structures"
      >Organisations</el-menu-item
    >
    <el-menu-item index="/dashboard/trash/missions">Missions</el-menu-item>
    <el-menu-item index="/dashboard/trash/participations"
      >Participations</el-menu-item
    >
  </el-menu>
</template>

<script>
export default {
  name: 'TrashMenu',
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
