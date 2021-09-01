<template>
  <div class="">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Mission</div>
        <div class="mb-8 max-w-3xl">
          <div class="font-bold text-2-5xl text-gray-800 mr-2">
            {{ mission.name }}
          </div>
          <div
            v-if="!['Signalée'].includes(mission.state)"
            class="font-light text-gray-600 flex items-center"
          >
            <div
              :class="
                structure.state == 'Validée' &&
                ['Validée', 'Terminée'].includes(mission.state)
                  ? 'bg-green-500'
                  : 'bg-red-500'
              "
              class="rounded-full h-2 w-2 mr-2 flex-none"
            ></div>
            <nuxt-link
              target="_blank"
              :to="`/missions-benevolat/${mission.id}/${mission.slug}`"
            >
              <span class="text-sm underline hover:no-underline break-all">
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
      <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between">
            <div class="mb-6 text-xl font-semibold">Informations</div>
          </div>
          <ModelMissionInfos :mission="mission" />
        </el-card>
        <div>
          <el-card shadow="never" class="p-4 mb-4">
            <div class="flex justify-between">
              <div class="mb-6 text-xl font-semibold">Responsable</div>
            </div>
            <ModelMemberTeaser
              class="member py-2"
              :member="mission.responsable"
            />
          </el-card>
          <el-card shadow="never" class="p-4">
            <div class="flex justify-between">
              <nuxt-link
                :to="`/dashboard/structure/${structure.id}`"
                class="mb-6 text-xl font-semibold hover:text-blue-800"
                >{{ structure.name }}</nuxt-link
              >
            </div>
            <ModelStructureInfos :structure="structure" />
          </el-card>
        </div>
      </div>
    </div>
    <!-- <div v-else-if="tab == 'history'">
      <TableActivities :table-data="tableData" />
    </div>
    <div v-else-if="tab == 'participations'">
      <TableParticipations
        :table-data="tableData"
        :on-updated-row="onUpdatedRow"
        :on-clicked-row="onClickedRow"
      />
    </div>
    <div v-if="tab" class="m-3 flex items-center">
      <el-pagination
        background
        layout="prev, pager, next"
        :total="totalRows"
        :page-size="15"
        :current-page="Number(query.page)"
        @current-change="onPageChange"
      />
      <div class="text-secondary text-xs ml-3">
        Affiche {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats
      </div>
    </div>
    <portal to="volet">
      <participation-volet @updated="onUpdatedRow" @deleted="onDeletedRow" />
    </portal> -->
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
      if (store.getters.contextStructure.id != mission.structure_id) {
        return error({ statusCode: 403 })
      }
    }

    const structure = await $api.getStructure(mission.structure.id)
    return {
      structure,
      mission,
    }
  },
  data() {
    return {
      loading: true,
      mission: null,
      structure: null,
      tableData: [],
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
