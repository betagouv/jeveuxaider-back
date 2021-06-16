<template>
  <div v-if="structure" class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Organisation</div>
        <div class="flex items-center flex-wrap mb-8">
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
      </div>
      <div>
        <DropdownStructureButton
          v-if="['responsable', 'admin'].includes($store.getters.contextRole)"
          :structure="structure"
        />
      </div>
    </div>
    <el-menu
      :default-active="$router.history.current.path"
      mode="horizontal"
      class="mb-8"
      @select="$router.push($event)"
    >
      <el-menu-item :index="`/dashboard/structure/${structure.id}`">
        Informations
      </el-menu-item>
      <el-menu-item :index="`/dashboard/structure/${structure.id}/statistics`">
        Statistiques
      </el-menu-item>
      <el-menu-item :index="`/dashboard/structure/${structure.id}/missions`">
        Missions
        <span class="text-xs text-gray-600"
          >({{ structure.missions_count }})</span
        >
      </el-menu-item>
      <el-menu-item
        :index="`/dashboard/structure/${structure.id}/participations`"
      >
        Participations
        <span class="text-xs text-gray-600"
          >({{ structure.participations_count }})</span
        >
      </el-menu-item>
      <el-menu-item :index="`/dashboard/structure/${structure.id}/history`">
        Historique
      </el-menu-item>
    </el-menu>
    <div class="px-12">
      <div class="flex flex-col space-y-5">
        <div>
          <div class="font-bold text-2xl text-gray-800 mb-4">
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
        <div>
          <div class="font-bold text-2xl text-gray-800 mb-4">
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
      !['admin', 'responsable_territoire', 'responsable'].includes(
        store.getters.contextRole
      )
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

    const { data: plausible } = await $api.plausibleAggregate(
      '12mo',
      'visitors,pageviews,visit_duration',
      `event:page==${structure.full_url}`
    )

    return {
      plausible,
      structure,
      statistics: data,
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
