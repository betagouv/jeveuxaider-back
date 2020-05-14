<template>
  <div>
    <el-menu-item index="/dashboard" :class="{'is-active': isActive('dashboard')}">
      <span v-if="$store.getters.sidebar">Tableau de bord</span>
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
    <el-menu-item :index="`/dashboard/structure/${$store.getters.structure_as_responsable.id}/edit`" :class="{'is-active': isActive('structure')}">
      <span v-if="$store.getters.sidebar">Ma structure</span>
      <el-tooltip
        v-else
        class="item"
        :open-delay="500"
        effect="dark"
        content="Structures"
        placement="right"
      >
        <i class="el-icon-school"></i>
      </el-tooltip>
    </el-menu-item>
    <el-menu-item index="/dashboard/missions" :class="{'is-active': isActive('mission')}">
      <span v-if="$store.getters.sidebar">Missions</span>
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
    <el-menu-item index="/dashboard/participations" :class="{'is-active': isActive('participation')}">
      <span v-if="$store.getters.sidebar">Participations</span>
      <el-tooltip
        v-else
        class="item"
        :open-delay="500"
        effect="dark"
        content="Participations"
        placement="right"
      >
        <i class="el-icon-finished"></i>
      </el-tooltip>
    </el-menu-item>
    <el-menu-item index="/dashboard/ressources" :class="{'is-active': isActive('ressources')}">
      <span v-if="$store.getters.sidebar">Ressources</span>
      <el-tooltip
        v-else
        class="item"
        :open-delay="500"
        effect="dark"
        content="Ressources"
        placement="right"
      >
        <i class="el-icon-help"></i>
      </el-tooltip>
    </el-menu-item>
    <el-menu-item v-if="$store.getters.sidebar">
      <a href="tel:0184800189">Aide:&nbsp;01 84 80 01 89&nbsp;</a>
    </el-menu-item>
  </div>
</template>

<script>
import MenuActive from "@/mixins/MenuActive"

export default {
  name: "MenuResponsable",
  mixins: [MenuActive]
};
</script>
