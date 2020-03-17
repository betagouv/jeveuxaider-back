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
        <i class="el-icon-data-analysis"></i>
      </el-tooltip>
    </el-menu-item>
    <el-menu-item index="/missions">
      <span v-if="!isCollapsed">Missions</span>
      <el-tooltip
        v-else
        class="item"
        :open-delay="500"
        effect="dark"
        content="Missions"
        placement="right"
      >
        <i class="el-icon-collection"></i>
      </el-tooltip>
    </el-menu-item>
    <el-menu-item index="/youngs">
      <span v-if="!isCollapsed">Volontaires</span>
      <el-tooltip
        v-else
        class="item"
        :open-delay="500"
        effect="dark"
        content="Volontaires"
        placement="right"
      >
        <i class="el-icon-finished"></i>
      </el-tooltip>
    </el-menu-item>
  </div>
</template>

<script>
export default {
  name: "MenuResponsable",
  props: {
    isCollapsed: {
      type: Boolean,
      required: true
    }
  }
};
</script>
