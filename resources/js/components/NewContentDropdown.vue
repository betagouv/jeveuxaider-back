<template>
  <el-dropdown>
    <el-button type="primary">
      <i class="el-icon-plus mr-1"></i> Ajouter un contenu
    </el-button>
    <el-dropdown-menu type="primary">
      <router-link :to="{ name: 'FaqFormAdd' }">
        <el-dropdown-item>Nouvelle question</el-dropdown-item>
      </router-link>
      <router-link :to="{ name: 'ReleaseFormAdd' }">
        <el-dropdown-item>Nouvelle release</el-dropdown-item>
      </router-link>
      <router-link :to="{ name: 'PageFormAdd' }">
        <el-dropdown-item>Nouvelle page</el-dropdown-item>
      </router-link>
      <router-link :to="{ name: 'CollectivityFormAdd' }">
        <el-dropdown-item>Nouvelle collectivité</el-dropdown-item>
      </router-link>
      <router-link :to="{ name: 'DocumentFormAdd' }">
        <el-dropdown-item>Nouveau document</el-dropdown-item>
      </router-link>
      <router-link :to="{ name: 'ThematiqueFormAdd' }">
        <el-dropdown-item>Nouvelle thématique</el-dropdown-item>
      </router-link>
      <router-link :to="{ name: 'MissionTemplateFormAdd' }">
        <el-dropdown-item>Nouveau modèle</el-dropdown-item>
      </router-link>
      <router-link :to="{ name: 'TagFormAdd' }">
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
