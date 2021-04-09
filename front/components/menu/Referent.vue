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
        'is-active': isActive('collectivities'),
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
      :class="{ 'is-active': isActive('structures') }"
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
      :class="{ 'is-active': isActive('missions') }"
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
      :class="{ 'is-active': isActive('participations') }"
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
      :class="{ 'is-active': isActive('profiles') }"
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

    <el-menu-item v-if="$store.getters.isSidebarExpanded" index="#">
      <a
        target="_blank"
        href="https://go.crisp.chat/chat/embed/?website_id=4b843a95-8a0b-4274-bfd5-e81cbdc188ac"
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
