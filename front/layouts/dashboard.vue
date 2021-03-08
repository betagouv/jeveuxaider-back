<template>
  <div class="h-screen bg-gray-100">
    <el-container
      v-if="$store.getters.isAppLoaded"
      class="h-full flex overflow-hidden"
    >
      <DashboardSidebar class="bg-gray-100" />
      <div class="main-container bg-white overflow-y-auto w-full">
        <Nuxt class="app-main pt-8" />
      </div>
      <portal-target
        v-if="$store.getters['volet/active'] && $store.getters['volet/row']"
        class="p-4 overflow-y-auto flex-none border-l bg-gray-100"
        name="volet"
        style="width: 420px"
      />
    </el-container>
  </div>
</template>

<script>
export default {
  middleware: 'admin',
}
</script>

<style lang="sass">
div#__nuxt,
#__layout,
#__layout > div,
#app
  min-height: 100vh
</style>
