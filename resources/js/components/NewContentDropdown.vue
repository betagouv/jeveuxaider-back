<template>
  <el-dropdown>
    <el-button type="primary">
      <i class="el-icon-plus mr-1"></i> Ajouter un contenu
    </el-button>
    <el-dropdown-menu type="primary">
      <router-link :to="{ name: 'DashboardFaqFormAdd' }">
        <el-dropdown-item>Nouvelle question</el-dropdown-item>
      </router-link>
      <router-link :to="{ name: 'DashboardReleaseFormAdd' }">
        <el-dropdown-item>Nouvelle release</el-dropdown-item>
      </router-link>
      <router-link :to="{ name: 'DashboardPageFormAdd' }">
        <el-dropdown-item>Nouvelle page</el-dropdown-item>
      </router-link>
      <router-link :to="{ name: 'DashboardCollectivityFormAdd' }">
        <el-dropdown-item>Nouveau département</el-dropdown-item>
      </router-link>
      <router-link :to="{ name: 'DashboardDocumentFormAdd' }">
        <el-dropdown-item>Nouveau document</el-dropdown-item>
      </router-link>
      <router-link :to="{ name: 'DashboardThematiqueFormAdd' }">
        <el-dropdown-item>Nouvelle thématique</el-dropdown-item>
      </router-link>
      <router-link :to="{ name: 'DashboardMissionTemplateFormAdd' }">
        <el-dropdown-item>Nouveau modèle</el-dropdown-item>
      </router-link>
      <router-link :to="{ name: 'DashboardTagFormAdd' }">
        <el-dropdown-item>Nouveau tag</el-dropdown-item>
      </router-link>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
export default {
  name: 'NewContentDropdown',
}
</script>
