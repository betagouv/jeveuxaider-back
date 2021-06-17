<template>
  <div v-if="territoire" class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1 mb-8">
        <div class="text-m text-gray-600 uppercase">Territoire</div>
        <div class="flex items-center flex-wrap">
          <div class="font-bold text-2-5xl text-gray-800 mr-2">
            {{ territoire.name }}
          </div>
          <TagModelState v-if="territoire.state" :state="territoire.state" />
        </div>

        <div class="font-light text-gray-600 flex items-center">
          <div
            :class="territoire.is_published ? 'bg-green-500' : 'bg-red-500'"
            class="rounded-full h-2 w-2 mr-2"
          ></div>
          <nuxt-link
            v-if="territoire.is_published"
            :to="territoire.full_url"
            target="_blank"
            class="underline hover:no-underline"
          >
            {{ territoire.full_url }}
          </nuxt-link>
          <span v-else class="cursor-default">
            {{ territoire.full_url }}
          </span>
        </div>
      </div>
      <div>
        <DropdownTerritoireButton :territoire="territoire" />
      </div>
    </div>
    <el-menu
      :default-active="$router.history.current.path"
      mode="horizontal"
      class="mb-8"
      @select="$router.push($event)"
    >
      <el-menu-item :index="`/dashboard/territoire/${territoire.id}`">
        Informations
      </el-menu-item>
      <el-menu-item
        :index="`/dashboard/territoire/${territoire.id}/statistics`"
      >
        Statistiques
      </el-menu-item>
      <el-menu-item
        v-if="$store.getters.contextRole == 'admin'"
        :index="`/dashboard/territoire/${territoire.id}/history`"
      >
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
              label="Organisations"
              :value="statistics.organisations.total"
            >
              <div class="my-1">
                <span class
                  >+{{ statistics.organisations.month | formatNumber }}</span
                >
                <span class="text-xs text-gray-500">les 30 derniers jours</span>
              </div>
              <div class="my-1">
                <span class
                  >+{{ statistics.organisations.week | formatNumber }}</span
                >
                <span class="text-xs text-gray-500">les 7 derniers jours</span>
              </div>
            </CardStatisticsDefaultCount>
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
    if (!['admin', 'responsable'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }

    if (store.getters.contextRole == 'responsable') {
      if (
        !store.getters.user.profile.territoires.filter(
          (item) => item.id == params.id
        ).length
      ) {
        return error({ statusCode: 403 })
      }
    }

    const territoire = await $api.getTerritoire(params.id)
    const { data } = await $api.statisticsBySubject(
      'territoires',
      territoire.id
    )

    const { data: plausible } = await $api.plausibleAggregate(
      '12mo',
      'visitors,pageviews,visit_duration',
      `event:page==${territoire.full_url}`
    )

    return {
      plausible,
      territoire,
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
