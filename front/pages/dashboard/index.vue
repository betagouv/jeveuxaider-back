<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-12 font-bold text-2-5xl text-gray-800">
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
  created() {
    if (
      this.$store.getters.contextRole == 'responsable' &&
      this.$store.getters.contextableType == 'territoire'
    ) {
      this.$router.push(
        `/dashboard/territoire/${this.$store.state.auth.user.contextable_id}/statistics`
      )
    } else if (
      this.$store.getters.contextRole == 'responsable' &&
      this.$store.getters.contextableType == 'structure'
    ) {
      this.$router.push(
        `/dashboard/structure/${this.$store.state.auth.user.contextable_id}/statistics`
      )
    }
  },
}
</script>
