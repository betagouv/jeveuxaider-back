<template>
  <div v-if="structure" class="has-full-table">
    <DashboardMissionHeader :mission="mission" :structure="structure" />
    <DashboardMissionTabs :mission="mission" />

    <div class="px-12">
      <div class="flex flex-col space-y-5">
        <div>
          <div class="font-semibold text-md uppercase text-[#242526] mb-4">
            JeVeuxAider.gouv.fr
          </div>
          <div class="flex flex-wrap">
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
        <div
          v-if="
            apiEngagementMyMission && apiEngagementMyMission.stats.clicks.length
          "
        >
          <div class="font-semibold text-md uppercase text-[#242526] mb-4">
            API Engagement
          </div>
          <div class="text-gray-500 mb-6">
            Toutes les missions validées sont diffusées sur nos plateformes
            partenaires pour vous amener plus de visibilité.
          </div>
          <div class="flex flex-wrap">
            <CardStatisticsDefaultCount
              v-for="click in apiEngagementMyMission.stats.clicks"
              :key="click.key"
              :label="click.name"
              :value="click.doc_count"
            >
              <div
                v-if="
                  apiEngagementMyMission.stats.applications.filter(
                    (item) => item.key == click.key
                  ).length
                "
                class="my-1"
              >
                <span class>{{
                  apiEngagementMyMission.stats.applications.find(
                    (item) => item.key == click.key
                  ).doc_count | formatNumber
                }}</span>
                <span class="text-xs text-gray-500">clicks sur CTA</span>
              </div>
            </CardStatisticsDefaultCount>
          </div>
        </div>
        <div>
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
        'referent',
        'referent_regional',
        // 'superviseur',
        'tete_de_reseau',
        'responsable',
      ].includes(store.getters.contextRole)
    ) {
      return error({ statusCode: 403 })
    }
    const mission = await $api.getMission(params.id)

    if (store.getters.contextRole == 'responsable') {
      if (store.getters.contextStructure.id != mission.structure_id) {
        return error({ statusCode: 403 })
      }
    }

    const structure = await $api.getStructure(mission.structure.id)
    const { data: statistics } = await $api.statisticsBySubject(
      'missions',
      params.id
    )

    const { data: plausible } = await $api.plausibleAggregate(
      '12mo',
      'visitors,pageviews,visit_duration',
      `event:page==${mission.full_url}`
    )

    const apiEngagementMyMission = await $api.apiEngagementMyMission(params.id)

    return {
      structure,
      mission,
      plausible,
      statistics,
      apiEngagementMyMission: apiEngagementMyMission.data,
    }
  },
  methods: {},
}
</script>
