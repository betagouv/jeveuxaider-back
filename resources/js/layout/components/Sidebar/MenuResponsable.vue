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
      :index="`/dashboard/structure/${$store.getters.structure_as_responsable.id}/edit`"
      :class="{
        'is-active':
          isActive('dashboard/structure') && !isActive('missions/add'),
      }"
    >
      <span v-if="$store.getters.sidebar">Mon organisation</span>
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
      :class="{ 'is-active': isActive('/dashboard/participation') }"
    >
      <span v-if="$store.getters.sidebar">Participations</span>
    </el-menu-item>
    <el-menu-item index="/messages">
      <el-badge
        v-if="$store.getters.sidebar"
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
      <span v-if="$store.getters.sidebar">Ressources</span>
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
    <el-menu-item v-if="$store.getters.sidebar">
      <a target="_blank" href="mailto:contact@reserve-civique.on.crisp.email"
        >Contacter le support</a
      >
    </el-menu-item>
  </div>
</template>

<script>
import MenuActive from '@/mixins/MenuActive'

export default {
  name: 'MenuResponsable',
  mixins: [MenuActive],
}
</script>
