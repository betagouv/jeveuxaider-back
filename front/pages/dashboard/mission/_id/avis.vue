<template>
  <div class="has-full-table">
    <DashboardMissionHeader :mission="mission" :structure="structure" />
    <DashboardMissionTabs :mission="mission" />

    <!-- TODO filtres -->

    <TableAvis
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
      <VoletAvis />
    </portal>
  </div>
</template>

<script>
import fileDownload from 'js-file-download'
import TableWithVolet from '@/mixins/table-with-volet'
import TableWithFilters from '@/mixins/table-with-filters'

export default {
  mixins: [TableWithFilters, TableWithVolet],
  layout: 'dashboard',
  async asyncData({ $api, params, store, error }) {
    if (
      ![
        'admin',
        'superviseur',
        'responsable',
        'referent',
        'referent_regional',
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
      loadingExport: false,
    }
  },
  async fetch() {
    this.query['filter[mission.id]'] = this.mission.id

    const { data } = await this.$axios.get(`/avis`, this.query)
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
  watch: {
    '$route.query': '$fetch',
  },
  methods: {
    onExport() {
      this.loadingExport = true
      this.$api
        .exportParticipations(this.query)
        .then((response) => {
          this.loadingExport = false
          fileDownload(response.data, 'participations.xlsx')
        })
        .catch((error) => {
          console.log('exportParticipations', error)
        })
    },
  },
}
</script>
