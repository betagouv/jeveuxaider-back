<template>
  <div class="has-full-table">
    <DashboardMissionHeader :mission="mission" :structure="structure" />
    <DashboardMissionTabs :mission="mission" />

    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <SearchFiltersQueryMain
          name="search"
          placeholder="Rechercher par mots clés, mission ou structure..."
          :initial-value="query['filter[search]']"
          @changed="onFilterChange"
        />
        <el-badge :value="activeFilters || null" type="primary">
          <el-button
            icon="el-icon-s-operation"
            class="!ml-4"
            @click="showFilters = !showFilters"
          >
            Filtres avancés
          </el-button>
        </el-badge>
      </div>
      <div v-if="showFilters" class="flex flex-wrap gap-4 mb-4">
        <SearchFiltersQuery
          name="state"
          label="Statut"
          multiple
          :value="query['filter[state]']"
          :options="
            $store.getters.taxonomies.participation_workflow_states.terms
          "
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          v-if="$store.getters.contextRole === 'responsable'"
          type="select"
          name="mission.responsable_id"
          :value="query['filter[mission.responsable_id]']"
          label="Responsable"
          :options="
            responsables.map((responsable) => {
              return {
                label: responsable.full_name,
                value: responsable.id,
              }
            })
          "
          @changed="onFilterChange"
        />
      </div>
    </div>

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
        // 'superviseur',
        'tete_de_reseau',
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

    const domaines = await $api.fetchTags({ 'filter[type]': 'domaine' })
    const templates = await $api.fetchMissionTemplates({ pagination: 1000 })
    const responsables = await $api.getStructureMembers(structure.id)

    return {
      structure,
      mission,
      domaines: domaines.data.data,
      templates: templates.data.data,
      responsables: responsables.data,
    }
  },

  data() {
    return {
      loadingExport: false,
    }
  },
  async fetch() {
    const { data } = await this.$api.fetchParticipations(this.fullQuery)
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
  computed: {
    fullQuery() {
      // exposed + forced filters
      return { ...this.query, 'filter[mission.id]': this.mission.id }
    },
  },
  methods: {
    onExport() {
      this.loadingExport = true
      this.$api
        .exportParticipations(this.fullQuery)
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
