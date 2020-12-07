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
  name: 'MenuAnalyste',
  mixins: [MenuActive],
}
</script>
