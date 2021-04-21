<template>
  <div>
    <el-submenu v-if="$store.getters.structure.collectivity" index="1" open>
      <template slot="title">
        <div class="truncate pr-4">
          <i class="el-icon-school"></i
          >{{ $store.getters.structure.collectivity.name }}
        </div></template
      >
      <el-menu-item
        v-if="$store.getters.profile.roles.responsable_collectivity == true"
        :index="`/dashboard/collectivity/${$store.getters.structure.collectivity.id}/stats`"
        :class="{
          'is-active': isActive('collectivity-stats'),
        }"
        >Statistiques de la page
      </el-menu-item>

      <el-menu-item
        v-else
        v-tooltip="{
          content: `Votre collectivité est en cours de validation.`,
          classes: 'bo-style',
        }"
        index="#"
        disabled
        >Statistiques de la page
      </el-menu-item>

      <el-menu-item
        :index="`/dashboard/collectivity/${$store.getters.structure.collectivity.id}/edit`"
        :class="{
          'is-active': isActive('collectivity-edit'),
        }"
        >Éditer la page
      </el-menu-item>
    </el-submenu>

    <el-menu-item
      index="/dashboard"
      :class="{ 'is-active': isActive('dashboard') }"
    >
      <div v-if="$store.getters.isSidebarExpanded">Tableau de bord</div>

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
      :index="`/dashboard/structure/${$store.getters.structure.id}/edit`"
      :class="{
        'is-active': isActive('structures'),
      }"
    >
      <span v-if="$store.getters.isSidebarExpanded">Mon organisation</span>

      <i
        v-else
        v-tooltip.right="{
          content: `Mon organisation`,
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
    <el-menu-item index="/messagess">
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

<style lang="sass" scoped>
::v-deep .el-badge__content.is-fixed
  top: 13px
  right: -5px
</style>
