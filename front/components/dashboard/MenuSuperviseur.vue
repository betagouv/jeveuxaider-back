<template>
  <div>
    <el-menu-item index="/dashboard">
      <span v-if="$store.getters.isSidebarExpanded">Tableau de bord</span>
      <el-tooltip
        v-else
        class="item"
        :open-delay="500"
        effect="dark"
        content="Tableau de bord"
        placement="right"
      >
        <i class="el-icon-data-analysis" />
      </el-tooltip>
    </el-menu-item>
    <el-menu-item index="/dashboard/structures">
      <span v-if="$store.getters.isSidebarExpanded">Organisations</span>
      <el-tooltip
        v-else
        class="item"
        :open-delay="500"
        effect="dark"
        content="Organisations"
        placement="right"
      >
        <i class="el-icon-school" />
      </el-tooltip>
    </el-menu-item>
    <el-menu-item index="/dashboard/missions">
      <span v-if="$store.getters.isSidebarExpanded">Missions</span>
      <el-tooltip
        v-else
        class="item"
        :open-delay="500"
        effect="dark"
        content="Missions"
        placement="right"
      >
        <i class="el-icon-collection" />
      </el-tooltip>
    </el-menu-item>
    <el-menu-item index="/dashboard/participations">
      <span v-if="$store.getters.isSidebarExpanded">Participations</span>
      <el-tooltip
        v-else
        class="item"
        :open-delay="500"
        effect="dark"
        content="Participations"
        placement="right"
      >
        <i class="el-icon-finished" />
      </el-tooltip>
    </el-menu-item>
    <el-menu-item index="/dashboard/profiles">
      <span v-if="$store.getters.isSidebarExpanded">Utilisateurs</span>
      <el-tooltip
        v-else
        class="item"
        :open-delay="500"
        effect="dark"
        content="Utilisateurs"
        placement="right"
      >
        <i class="el-icon-user" />
      </el-tooltip>
    </el-menu-item>
    <el-menu-item v-if="$store.getters.isSidebarExpanded">
      <a target="_blank" href="mailto:contact@reserve-civique.on.crisp.email"
        >Contacter le support</a
      >
    </el-menu-item>
  </div>
</template>

<script>
import MenuActive from '@/mixins/MenuActive'

export default {
  mixins: [MenuActive],
}
</script>
