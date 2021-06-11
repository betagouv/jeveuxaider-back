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
      index="/dashboard/territoires"
      :class="{
        'is-active': isActive('territoires'),
      }"
    >
      <span v-if="$store.getters.isSidebarExpanded">Territoires</span>

      <i
        v-else
        v-tooltip.right="{
          content: `Territoires`,
          classes: 'bo-style',
        }"
        class="el-icon-office-building"
      />
    </el-menu-item>

    <el-menu-item
      index="/dashboard/structures"
      :class="{
        'is-active': isActive('structures'),
      }"
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
      :class="{
        'is-active': isActive('missions'),
      }"
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
      index="/dashboard/contents/releases"
      :class="{ 'is-active': isActive('contents') }"
    >
      <span v-if="$store.getters.isSidebarExpanded">Contenus</span>

      <i
        v-else
        v-tooltip.right="{
          content: `Contenus`,
          classes: 'bo-style',
        }"
        class="el-icon-help"
      />
    </el-menu-item>

    <el-menu-item index="/messages">
      <el-badge
        v-if="$store.getters.isSidebarExpanded"
        :value="$store.getters.user.unreadConversations.length"
        :hidden="$store.getters.user.unreadConversations.length == 0"
        :max="99"
      >
        <span>Messagerie</span>
      </el-badge>

      <i
        v-else
        v-tooltip.right="{
          content: `Messagerie`,
          classes: 'bo-style',
        }"
        class="el-icon-message"
      />
    </el-menu-item>

    <el-menu-item
      index="/dashboard/activities"
      :class="{ 'is-active': isActive('activities') }"
    >
      <span v-if="$store.getters.isSidebarExpanded">Activités</span>

      <i
        v-else
        v-tooltip.right="{
          content: `Activités`,
          classes: 'bo-style',
        }"
        class="el-icon-document"
      />
    </el-menu-item>
  </div>
</template>

<script>
import MenuActive from '@/mixins/menu-active'

export default {
  mixins: [MenuActive],
}
</script>

<style lang="sass" scoped>
::v-deep .el-badge__content.is-fixed
  top: 13px
  right: -5px
</style>
