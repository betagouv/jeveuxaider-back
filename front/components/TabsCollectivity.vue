<template>
  <el-menu :default-active="index" mode="horizontal" @select="handleSelect">
    <el-menu-item
      v-if="$store.getters.contextRole != 'responsable'"
      :index="`/dashboard/collectivity/${collectivity.id}`"
    >
      Informations
    </el-menu-item>
    <el-menu-item :index="`/dashboard/collectivity/${collectivity.id}/stats`">
      Statistiques
    </el-menu-item>
    <el-menu-item
      :index="`/dashboard/collectivity/${collectivity.id}/missions`"
    >
      Missions
    </el-menu-item>
    <el-menu-item
      :index="`/dashboard/collectivity/${collectivity.id}/participations`"
    >
      Participations
    </el-menu-item>
    <el-menu-item
      v-if="$store.getters.contextRole != 'responsable'"
      :index="`/dashboard/collectivity/${collectivity.id}/history`"
    >
      Historique
    </el-menu-item>
  </el-menu>
</template>

<script>
export default {
  name: 'CollectivityMenu',
  props: {
    index: {
      type: String,
      required: true,
    },
    collectivity: {
      type: Object,
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
