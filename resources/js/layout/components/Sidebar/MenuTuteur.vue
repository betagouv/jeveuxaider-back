<template>
  <div>
    <el-menu-item
      index="/dashboard"
      :class="{ 'is-active': isActive('dashboard') }"
    >
      <span v-if="$store.getters.sidebar">Tableau de bord</span>
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
    <el-menu-item
      index="/dashboard/missions"
      :class="{ 'is-active': isActive('mission') }"
    >
      <span v-if="$store.getters.sidebar">Missions</span>
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
    <el-menu-item
      index="/dashboard/participations"
      :class="{ 'is-active': isActive('participation') }"
    >
      <span v-if="$store.getters.sidebar">Participations</span>
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
    <el-menu-item v-if="$store.getters.sidebar">
      <a href="tel:0184800189">Aide:&nbsp;01 84 80 01 89&nbsp;</a>
    </el-menu-item>
  </div>
</template>

<script>
import MenuActive from '@/mixins/MenuActive'

export default {
  name: 'MenuTuteur',
  mixins: [MenuActive],
}
</script>
