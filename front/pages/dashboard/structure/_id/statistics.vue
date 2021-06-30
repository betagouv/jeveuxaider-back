<template>
  <div v-if="structure" class="has-full-table">
    <div
      class="header px-12 flex"
      :class="{ 'mb-8': $store.getters.contextRole == 'responsable' }"
    >
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Organisation</div>
        <div class="flex items-center flex-wrap">
          <div class="font-bold text-2-5xl text-gray-800 mr-2">
            {{ structure.name }}
          </div>
          <TagModelState v-if="structure.state" :state="structure.state" />
          <el-tag
            v-if="structure.is_reseau"
            size="medium"
            class="m-1 ml-0"
            type="danger"
          >
            Tête de réseau
          </el-tag>
          <el-tag v-if="structure.reseau_id" class="m-1 ml-0" size="medium">
            {{ structure.reseau_id | reseauFromValue }}
          </el-tag>
        </div>
        <div
          v-if="structure.statut_juridique == 'Association'"
          class="font-light text-gray-600 flex items-center"
        >
          <div
            :class="
              structure.state == 'Validée' ? 'bg-green-500' : 'bg-red-500'
            "
            class="rounded-full h-2 w-2 mr-2"
          ></div>
          <nuxt-link
            v-if="structure.state == 'Validée'"
            :to="structure.full_url"
            target="_blank"
            class="underline hover:no-underline"
          >
            {{ structure.full_url }}
          </nuxt-link>
          <span v-else class="cursor-default">
            {{ structure.full_url }}
          </span>
        </div>
      </div>
      <div>
        <DropdownStructureButton
          v-if="['responsable', 'admin'].includes($store.getters.contextRole)"
          :structure="structure"
        />
      </div>
    </div>
    <NavTabStructure
      v-if="$store.getters.contextRole != 'responsable'"
      :structure="structure"
    />
    <div class="px-12">
      <div class="flex flex-col space-y-8">
        <div v-if="actions.length" class="max-w-3xl">
          <div class="font-semibold text-md uppercase text-gray-800 mb-4">
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
          <div class="font-semibold text-md uppercase text-gray-800 mb-4">
            JeVeuxAider.gouv.fr
          </div>
          <div class="flex flex-wrap">
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
        'responsable_territoire',
        'responsable',
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
    const { data } = await $api.statisticsBySubject(
      'organisations',
      structure.id
    )

    // Only for Association
    let analytics = null
    if (structure.statut_juridique == 'Association') {
      const { data: plausible } = await $api.plausibleAggregate(
        '12mo',
        'visitors,pageviews,visit_duration',
        `event:page==${structure.full_url}`
      )
      analytics = plausible
    }

    // Actions

    const { data: actions } = await $api.fetchStructureActions(structure.id)

    return {
      plausible: analytics,
      structure,
      statistics: data,
      actions,
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
