<template>
  <el-menu :default-active="index" mode="horizontal" @select="handleSelect">
    <el-menu-item index="/dashboard/participations">
      Participations
    </el-menu-item>
    <el-menu-item index="/dashboard/participations/trouver-des-benevoles">
      Trouver des bénévoles
    </el-menu-item>
  </el-menu>
</template>

<script>
export default {
  name: 'ParticipationsMenu',
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
