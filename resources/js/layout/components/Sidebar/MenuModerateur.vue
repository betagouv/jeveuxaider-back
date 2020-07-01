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
      index="/dashboard/structures"
      :class="{
        'is-active': isActive('structure') && !isActive('missions/add'),
      }"
    >
      <span v-if="$store.getters.sidebar">Structures</span>
      <el-tooltip
        v-else
        class="item"
        :open-delay="500"
        effect="dark"
        content="Structures"
        placement="right"
      >
        <i class="el-icon-school" />
      </el-tooltip>
    </el-menu-item>
    <el-menu-item
      index="/dashboard/missions"
      :class="{
        'is-active': isActive('mission') && !isActive('mission-templates'),
      }"
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
    <el-menu-item
      index="/dashboard/profiles"
      :class="{ 'is-active': isActive('profile') }"
    >
      <span v-if="$store.getters.sidebar">Utilisateurs</span>
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
      <span v-if="$store.getters.sidebar">Contenus</span>
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
    <el-menu-item
      index="/dashboard/activities"
      :class="{ 'is-active': isActive('activities') }"
    >
      <span v-if="$store.getters.sidebar">Activité</span>
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
