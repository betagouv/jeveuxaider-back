<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-12 font-bold text-2-5xl text-[#242526]">
          Tableau de bord
        </div>
      </div>
    </div>

    <template v-if="$store.getters.contextRole === 'responsable'">
      <!-- <DashboardResponsable /> -->
    </template>

    <template v-else>
      <DashboardOther />
    </template>
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  middleware({ $api, route, redirect, store }) {
    if (store.getters.contextRole == 'responsable') {
      redirect(
        `/dashboard/${store.getters.contextableType}/${store.state.auth.user.contextable_id}/statistics`
      )
    }
  },
}
</script>
