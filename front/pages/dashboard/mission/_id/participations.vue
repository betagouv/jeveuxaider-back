<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Mission</div>
        <div class="flex flex-wrap mb-8 max-w-3xl">
          <div class="font-bold text-2-5xl text-gray-800 mr-2">
            {{ mission.name }}
          </div>
          <TagModelState :state="mission.state" />
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

    <TableParticipations
      :loading="$fetchState.pending"
      :table-data="tableData"
      :on-updated-row="onUpdatedRow"
      :on-clicked-row="onClickedRow"
    />
    <div class="m-3 flex items-center">
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
      <VoletParticipation @updated="onUpdatedRow" @deleted="onDeletedRow" />
    </portal>
  </div>
</template>

<script>
import TableWithVolet from '@/mixins/table-with-volet'
import TableWithFilters from '@/mixins/table-with-filters'

export default {
  mixins: [TableWithFilters, TableWithVolet],
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
    return {
      structure,
      mission,
    }
  },
  async fetch() {
    const { data } = await this.$api.fetchParticipations({
      'filter[mission.id]': this.$route.params.id,
      page: this.$route.query.page || 1,
    })
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
  watch: {
    '$route.query': '$fetch',
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
