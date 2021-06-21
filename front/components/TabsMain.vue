<template>
  <el-menu :default-active="index" mode="horizontal" @select="handleSelect">
    <el-menu-item index="main" active> Général </el-menu-item>
    <el-menu-item
      v-if="$store.getters.contextRole != 'responsable'"
      index="structures"
    >
      Organisations
    </el-menu-item>
    <el-menu-item index="missions"> Missions </el-menu-item>
    <el-menu-item index="participations"> Participations </el-menu-item>
    <el-menu-item
      v-if="
        ['admin', 'analyste', 'referent', 'referent_regional'].includes(
          $store.getters.contextRole
        )
      "
      index="profiles"
    >
      Utilisateurs
    </el-menu-item>
    <el-menu-item
      v-if="
        ['admin', 'analyste', 'referent_regional'].includes(
          $store.getters.contextRole
        )
      "
      index="departments"
    >
      Départements
    </el-menu-item>
    <el-menu-item
      v-if="
        ['admin', 'analyste', 'referent', 'referent_regional'].includes(
          $store.getters.contextRole
        )
      "
      index="collectivities"
    >
      Collectivités
    </el-menu-item>
    <el-menu-item
      v-if="['admin', 'analyste'].includes($store.getters.contextRole)"
      index="domaines"
    >
      Domaines
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
      if (
        this.$router.history.current.name == 'DashboardCollectivityMain' ||
        this.$router.history.current.name ==
          'DashboardCollectivityStatsMissions' ||
        this.$router.history.current.name ==
          'DashboardCollectivityStatsParticipations'
      ) {
        index == 'main'
          ? this.$router.push('/dashboard/collectivity')
          : this.$router.push(`/dashboard/collectivity/stats/${index}`)
      } else {
        index == 'main'
          ? this.$router.push('/dashboard')
          : this.$router.push(`/dashboard/stats/${index}`)
      }
    },
  },
}
</script>
