<template>
  <div v-if="structure" class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Mission</div>
        <div class="mb-8 max-w-3xl">
          <div class="font-bold text-2-5xl text-gray-800 mr-2">
            {{ mission.name }}
          </div>
          <div
            v-if="
              !['En attente de validation', 'Signalée'].includes(mission.state)
            "
            class="font-light text-gray-600 flex items-center"
          >
            <div
              :class="
                structure.state == 'Validée' &&
                ['Validée', 'Terminée'].includes(mission.state)
                  ? 'bg-green-500'
                  : 'bg-red-500'
              "
              class="rounded-full h-2 w-2 mr-2"
            ></div>
            <nuxt-link
              target="_blank"
              :to="`/missions-benevolat/${mission.id}/${mission.slug}`"
            >
              <span class="text-sm underline hover:no-underline">
                {{ $config.appUrl }}/missions-benevolat/{{ mission.id }}/{{
                  mission.slug
                }}
              </span>
            </nuxt-link>
          </div>
          <TagModelState class="mt-4" :state="mission.state" />
        </div>
      </div>
      <div>
        <DropdownMissionButton :mission="mission" />
      </div>
    </div>
    <el-menu
      :default-active="$router.history.current.path"
      mode="horizontal"
      class="mb-8"
      @select="$router.push($event)"
    >
      <el-menu-item :index="`/dashboard/mission/${mission.id}`">
        Informations
      </el-menu-item>
      <el-menu-item :index="`/dashboard/mission/${mission.id}/statistics`">
        Statistiques
      </el-menu-item>
      <el-menu-item
        v-if="mission"
        :index="`/dashboard/mission/${mission.id}/participations`"
      >
        Participations
        <span class="text-xs text-gray-600">
          ({{ mission.participations_total }})
        </span>
      </el-menu-item>
      <el-menu-item :index="`/dashboard/mission/${mission.id}/history`">
        Historique
      </el-menu-item>
    </el-menu>
    <div class="px-12">
      <div class="flex flex-col space-y-5">
        <div>
          <div class="font-semibold text-md uppercase text-gray-800 mb-4">
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
          <div class="font-semibold text-md uppercase text-gray-800 mb-4">
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
          <div class="font-semibold text-md uppercase text-gray-800 mb-4">
            Analytics sur la dernière année
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
        'superviseur',
        'responsable',
      ].includes(store.getters.contextRole)
    ) {
      return error({ statusCode: 403 })
    }
    const mission = await $api.getMission(params.id)

    if (store.getters.contextRole == 'responsable') {
      if (store.getters.structure.id != mission.structure_id) {
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

<style scoped lang="sass">
.el-menu--horizontal
  @apply px-12
  > .el-menu-item
    @apply mr-8 p-0 font-medium
      border-bottom: solid 3px #070191
</style>
