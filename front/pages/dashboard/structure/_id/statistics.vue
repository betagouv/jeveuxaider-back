<template>
  <div v-if="structure">
    <HeaderOrganisation :structure="structure" />

    <!-- <BannerPageOrga
      v-if="
        $store.getters.contextRole === 'responsable' &&
        $store.getters.contextStructure.statut_juridique == 'Association' &&
        $store.getters.contextStructure.state == 'Validée'
      "
      class="mb-6"
    /> -->

    <NavTabStructure
      v-if="$store.getters.contextRole != 'responsable'"
      :structure="structure"
    />
    <div class="px-12">
      <div class="flex flex-col space-y-8">
        <div v-if="actions && actions.length" class="max-w-3xl">
          <div class="font-semibold text-md uppercase text-[#242526] mb-4">
            Actions en attente
            <span>({{ actions.length }})</span>
          </div>
          <div class="bg-white border border-gray-200 sm:rounded-md">
            <ul class="divide-y divide-gray-200">
              <li v-for="(action, index) in actions" :key="index">
                <template v-if="action.type == 'waiting_participations'">
                  <ActionWaitingParticipations :action="action" />
                </template>
                <template v-if="action.type == 'outdated_missions'">
                  <ActionOutdatedMissions :action="action" />
                </template>
              </li>
            </ul>
          </div>
        </div>
        <div>
          <div class="font-semibold text-md uppercase text-[#242526] mb-4">
            JeVeuxAider.gouv.fr
          </div>
          <div v-if="statistics" class="flex flex-wrap">
            <CardStatisticsDefaultCount
              label="Missions"
              :value="statistics.missions.total"
            >
              <div class="my-1">
                <span class
                  >+{{ statistics.missions.month | formatNumber }}</span
                >
                <span class="text-xs text-gray-500">les 30 derniers jours</span>
              </div>
              <div class="my-1">
                <span class
                  >+{{ statistics.missions.week | formatNumber }}</span
                >
                <span class="text-xs text-gray-500">les 7 derniers jours</span>
              </div>
            </CardStatisticsDefaultCount>
            <CardStatisticsDefaultCount
              label="Participations"
              :value="statistics.participations.total"
            >
              <div class="my-1">
                <span class
                  >+{{ statistics.participations.month | formatNumber }}</span
                >
                <span class="text-xs text-gray-500">les 30 derniers jours</span>
              </div>
              <div class="my-1">
                <span class
                  >+{{ statistics.participations.week | formatNumber }}</span
                >
                <span class="text-xs text-gray-500">les 7 derniers jours</span>
              </div>
            </CardStatisticsDefaultCount>
          </div>
        </div>
        <div v-if="plausible">
          <div class="font-semibold text-md uppercase text-[#242526] mb-4">
            Visiteurs sur la dernière année
          </div>
          <div class="flex flex-wrap">
            <CardStatisticsDefaultCount
              label="Nombre de vues"
              :value="plausible.results.pageviews.value"
            >
              <div class="my-1">
                <span class>{{
                  plausible.results.visitors.value | formatNumber
                }}</span>
                <span class="text-xs text-gray-500">visiteurs uniques</span>
              </div>
              <div class="my-1">
                <span class>{{
                  plausible.results.visit_duration.value | formatNumber
                }}</span>
                <span class="text-xs text-gray-500"
                  >secondes de durée moyenne</span
                >
              </div>
            </CardStatisticsDefaultCount>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params, store, error }) {
    if (
      ![
        'admin',
        'responsable_territoire',
        'responsable',
        'referent',
        'referent_regional',
        'superviseur',
      ].includes(store.getters.contextRole)
    ) {
      return error({ statusCode: 403 })
    }

    if (store.getters.contextRole == 'responsable') {
      if (
        !store.getters.user.profile.structures.filter(
          (item) => item.id == params.id
        ).length
      ) {
        return error({ statusCode: 403 })
      }
    }

    const structure = await $api.getStructure(params.id)

    return {
      structure,
    }
  },
  data() {
    return {
      actions: null,
      plausible: null,
      statistics: null,
    }
  },
  created() {
    // Statistics
    this.$api
      .statisticsBySubject('organisations', this.structure.id)
      .then(({ data }) => (this.statistics = data))
    // Actions
    this.$api
      .fetchStructureActions(this.structure.id)
      .then(({ data }) => (this.actions = data))
    // Only for Association
    if (this.structure.statut_juridique == 'Association') {
      this.$api
        .plausibleAggregate(
          '12mo',
          'visitors,pageviews,visit_duration',
          `event:page==${this.structure.full_url}`
        )
        .then(({ data }) => (this.plausible = data))
    }
  },
}
</script>

<style scoped lang="postcss">
.el-menu--horizontal {
  @apply px-12;
  > .el-menu-item {
    @apply mr-8 p-0 font-medium;
    border-bottom: solid 3px #070191;
  }
}
</style>
