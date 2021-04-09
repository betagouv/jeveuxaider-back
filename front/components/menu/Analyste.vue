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
    <el-menu-item v-if="$store.getters.isSidebarExpanded">
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
