<template>
  <div>
    <el-submenu v-if="$store.getters.structure.collectivity" index="1">
      <template slot="title"
        ><i class="el-icon-school"></i
        >{{ $store.getters.structure.collectivity.name }}</template
      >
      <el-menu-item
        v-if="$store.getters.profile.roles.responsable_collectivity == true"
        index="/dashboard/collectivity"
        :class="{
          'is-active': isActive('dashboard/collectivity'),
        }"
        >Statistiques de la page
      </el-menu-item>
      <el-tooltip
        v-else
        class="item"
        effect="dark"
        content="Votre collectivité est en cours de validation."
        placement="top"
      >
        <el-menu-item disabled>Statistiques de la page </el-menu-item>
      </el-tooltip>
      <el-menu-item
        :index="`/dashboard/collectivity/${$store.getters.structure.collectivity.id}/edit`"
        :class="{
          'is-active': isActive(
            `dashboard/collectivity/${$store.getters.structure.collectivity.id}/edit`
          ),
        }"
        >Éditer la page
      </el-menu-item>
    </el-submenu>

    <el-menu-item
      index="/dashboard"
      :class="{ 'is-active': isActive('dashboard') }"
    >
      <div v-if="$store.getters.isSidebarExpanded">Tableau de bord</div>
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
      :index="`/dashboard/structure/${$store.getters.structure.id}/edit`"
      :class="{
        'is-active':
          isActive('dashboard/structure') && !isActive('missions/add'),
      }"
    >
      <span v-if="$store.getters.isSidebarExpanded">Mon organisation</span>
      <el-tooltip
        v-else
        class="item"
        :open-delay="500"
        effect="dark"
        content="Mon organisation"
        placement="right"
      >
        <i class="el-icon-school" />
      </el-tooltip>
    </el-menu-item>
    <el-menu-item
      index="/dashboard/missions"
      :class="{ 'is-active': isActive('/dashboard/mission') }"
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
      index="/dashboard/ressources"
      :class="{ 'is-active': isActive('/dashboard/ressources') }"
    >
      <span v-if="$store.getters.isSidebarExpanded">Ressources</span>
      <el-tooltip
        v-else
        class="item"
        :open-delay="500"
        effect="dark"
        content="Ressources"
        placement="right"
      >
        <i class="el-icon-help" />
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
import MenuActive from '@/mixins/menu-active'

export default {
  mixins: [MenuActive],
}
</script>
