<template>
  <div id="dashboard-layout" class="h-screen bg-gray-100">
    <client-only>
      <div
        v-if="$store.getters.isAppLoaded"
        class="h-full flex overflow-hidden"
      >
        <Sidebar class="" />

        <div class="main-container bg-white overflow-y-auto w-full">
          <Nuxt :nuxt-child-key="$route.fullPath" class="app-main pt-8" />
        </div>

        <portal-target
          v-if="$store.getters['volet/active'] && $store.getters['volet/row']"
          class="p-4 overflow-y-auto flex-none border-l bg-gray-100"
          name="volet"
          style="width: 420px"
        />
      </div>
    </client-only>
  </div>
</template>

<script>
export default {
  name: 'DashboardLayout',
  middleware: 'dashboard',
  head: {
    bodyAttrs: {
      class: 'full-height-layout overflow-x-hidden',
    },
  },
}
</script>
