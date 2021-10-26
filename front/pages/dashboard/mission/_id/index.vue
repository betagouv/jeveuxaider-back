<template>
  <div class="">
    <DashboardMissionHeader :mission="mission" :structure="structure" />
    <DashboardMissionTabs :mission="mission" />

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
                class="mb-6 text-xl font-semibold hover:text-[#070191]"
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
