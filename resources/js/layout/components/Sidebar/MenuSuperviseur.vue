<template>
  <div>
    <el-menu-item index="/dashboard">
      <span v-if="!isCollapsed">Tableau de bord</span>
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
      <span v-if="!isCollapsed">Structures</span>
      <el-tooltip
        v-else
        class="item"
        :open-delay="500"
        effect="dark"
        content="Structures"
        placement="right"
      >
        <i class="el-icon-school" />
      </el-tooltip>
    </el-menu-item>
    <el-menu-item index="/dashboard/missions">
      <span v-if="!isCollapsed">Missions</span>
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
      <span v-if="!isCollapsed">Participations</span>
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
      <span v-if="!isCollapsed">Utilisateurs</span>
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
    <el-menu-item v-if="!isCollapsed">
      <a href="tel:0184800189">Aide:&nbsp;01 84 80 01 89&nbsp;</a>
    </el-menu-item>
  </div>
</template>

<script>
export default {
  name: "MenuSuperviseur",
  props: {
    isCollapsed: {
      type: Boolean,
      required: true
    }
  }
};
</script>
