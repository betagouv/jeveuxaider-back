<template>
  <div>
    <el-menu-item index="/dashboard">
      <span v-if="$store.getters.isSidebarExpanded">Tableau de bord</span>

      <i
        v-else
        v-tooltip.right="{
          content: `Tableau de bord`,
          classes: 'bo-style',
        }"
        class="el-icon-data-analysis"
      />
    </el-menu-item>
    <el-menu-item index="/dashboard/structures">
      <span v-if="$store.getters.isSidebarExpanded">Organisations</span>

      <i
        v-else
        v-tooltip.right="{
          content: `Organisations`,
          classes: 'bo-style',
        }"
        class="el-icon-school"
      />
    </el-menu-item>
    <el-menu-item index="/dashboard/missions">
      <span v-if="$store.getters.isSidebarExpanded">Missions</span>

      <i
        v-else
        v-tooltip.right="{
          content: `Missions`,
          classes: 'bo-style',
        }"
        class="el-icon-collection"
      />
    </el-menu-item>
    <el-menu-item index="/dashboard/participations">
      <span v-if="$store.getters.isSidebarExpanded">Participations</span>

      <i
        v-else
        v-tooltip.right="{
          content: `Participations`,
          classes: 'bo-style',
        }"
        class="el-icon-finished"
      />
    </el-menu-item>
    <el-menu-item v-if="$store.getters.isSidebarExpanded">
      <a target="_blank" href="mailto:contact@reserve-civique.on.crisp.email"
        >Contacter le support</a
      >
    </el-menu-item>
  </div>
</template>

<script>
import MenuActive from '@/mixins/menu-active'

export default {
  mixins: [MenuActive],
}
</script>
