<template>
  <div>
    <el-menu-item
      index="/dashboard"
      :class="{ 'is-active': isActive('dashboard') }"
    >
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
    <el-menu-item
      index="/dashboard/collectivities"
      :class="{
        'is-active': isActive('/dashboard/collectivities'),
      }"
    >
      <span v-if="$store.getters.isSidebarExpanded">Collectivités</span>
      <el-tooltip
        v-else
        class="item"
        :open-delay="500"
        effect="dark"
        content="Collectivités"
        placement="right"
      >
        <i class="el-icon-office-building" />
      </el-tooltip>
    </el-menu-item>
    <el-menu-item
      index="/dashboard/structures"
      :class="{
        'is-active': isActive('structure') && !isActive('missions/add'),
      }"
    >
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
    <el-menu-item
      index="/dashboard/missions"
      :class="{
        'is-active':
          isActive('/dashboard/mission') && !isActive('mission-templates'),
      }"
    >
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
    <el-menu-item
      index="/dashboard/participations"
      :class="{ 'is-active': isActive('/dashboard/participation') }"
    >
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
    <el-menu-item
      index="/dashboard/profiles"
      :class="{ 'is-active': isActive('profile') }"
    >
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
    <el-menu-item
      index="/dashboard/contents/faqs"
      :class="{ 'is-active': isActive('content') }"
    >
      <span v-if="$store.getters.isSidebarExpanded">Contenus</span>
      <el-tooltip
        v-else
        class="item"
        :open-delay="500"
        effect="dark"
        content="Contenus"
        placement="right"
      >
        <i class="el-icon-help" />
      </el-tooltip>
    </el-menu-item>
    <el-menu-item index="/messages">
      <el-badge
        v-if="$store.getters.isSidebarExpanded"
        :value="$store.getters.user.nbUnreadConversations"
        :hidden="!$store.getters.user.nbUnreadConversations"
        :max="99"
      >
        <span>Messagerie</span>
      </el-badge>
      <el-tooltip
        v-else
        class="item"
        :open-delay="500"
        effect="dark"
        content="Messagerie"
        placement="right"
      >
        <i class="el-icon-message" />
      </el-tooltip>
    </el-menu-item>
    <el-menu-item
      index="/dashboard/activities"
      :class="{ 'is-active': isActive('/dashboard/activities') }"
    >
      <span v-if="$store.getters.isSidebarExpanded">Activités</span>
    </el-menu-item>
  </div>
</template>

<script>
import MenuActive from '@/mixins/MenuActive'

export default {
  name: 'MenuModerateur',
  mixins: [MenuActive],
}
</script>

<style lang="sass" scoped>
::v-deep .el-badge__content.is-fixed
  top: 13px
  right: -5px
</style>
