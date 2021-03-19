<template>
  <div>
    <el-menu-item
      index="/dashboard"
      :class="{ 'is-active': isActive('dashboard') }"
    >
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

    <el-menu-item
      index="/dashboard/collectivities"
      :class="{
        'is-active': isActive('/dashboard/collectivities'),
      }"
    >
      <span v-if="$store.getters.isSidebarExpanded">Collectivités</span>

      <i
        v-else
        v-tooltip.right="{
          content: `Collectivités`,
          classes: 'bo-style',
        }"
        class="el-icon-office-building"
      />
    </el-menu-item>

    <el-menu-item
      index="/dashboard/structures"
      :class="{ 'is-active': isActive('/dashboard/structure') }"
    >
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

    <el-menu-item
      index="/dashboard/missions"
      :class="{ 'is-active': isActive('/dashboard/mission') }"
    >
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

    <el-menu-item
      index="/dashboard/participations"
      :class="{ 'is-active': isActive('/dashboard/participation') }"
    >
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

    <el-menu-item
      index="/dashboard/profiles"
      :class="{ 'is-active': isActive('/dashboard/profile') }"
    >
      <span v-if="$store.getters.isSidebarExpanded">Utilisateurs</span>

      <i
        v-else
        v-tooltip.right="{
          content: `Utilisateurs`,
          classes: 'bo-style',
        }"
        class="el-icon-user"
      />
    </el-menu-item>

    <el-menu-item
      index="/dashboard/ressources"
      :class="{ 'is-active': isActive('ressources') }"
    >
      <span v-if="$store.getters.isSidebarExpanded">Ressources</span>

      <i
        v-else
        v-tooltip.right="{
          content: `Ressources`,
          classes: 'bo-style',
        }"
        class="el-icon-help"
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
import MenuActive from '@/mixins/MenuActive'

export default {
  mixins: [MenuActive],
}
</script>
