<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">Missions</div>
      </div>
      <div>
        <!-- <nuxt-link
          v-if="$store.getters.contextRole === 'responsable'"
          :to="`/dashboard/structure/${structureRESPONSABLETODO.id}`"
        >
          <el-button type="primary"> Créer une mission </el-button>
        </nuxt-link> -->
      </div>
    </div>
    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <SearchFiltersQueryMain
          name="search"
          placeholder="Rechercher par mots clés, mission ou structure..."
          :initial-value="query['filter[search]']"
          @changed="onFilterChange"
        />
        <el-badge v-if="activeFilters" :value="activeFilters" type="primary">
          <el-button
            icon="el-icon-s-operation"
            class="ml-4"
            @click="showFilters = !showFilters"
          >
            Filtres avancés
          </el-button>
        </el-badge>
        <el-button
          v-else
          icon="el-icon-s-operation"
          class="ml-4"
          @click="showFilters = !showFilters"
        >
          Filtres avancés
        </el-button>
      </div>
      <div v-if="showFilters" class="flex flex-wrap">
        <SearchFiltersQuery
          v-if="$store.getters.contextRole === 'responsable'"
          type="select"
          name="responsable_id"
          :value="query['filter[responsable_id]']"
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
        <SearchFiltersQueryInput
          name="lieu"
          label="Lieu"
          placeholder="Ville ou code postal"
          :initial-value="query['filter[lieu]']"
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          v-if="$store.getters.contextRole === 'admin'"
          name="department"
          label="Département"
          multiple
          :value="query['filter[department]']"
          :options="
            $store.getters.taxonomies.departments.terms.map((term) => {
              return {
                label: `${term.value} - ${term.label}`,
                value: term.value,
              }
            })
          "
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          type="select"
          name="collectivity"
          :value="query['filter[collectivity]']"
          label="Collectivité"
          :options="
            collectivities.map((collectivity) => {
              return {
                label: collectivity.name,
                value: collectivity.id,
              }
            })
          "
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          type="select"
          name="template_id"
          :value="query['filter[template_id]']"
          label="Missions types"
          :options="
            templates.map((template) => {
              return {
                label: template.title,
                value: template.id,
              }
            })
          "
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          type="select"
          name="domaine"
          :value="query['filter[domaine]']"
          label="Domaines d'action"
          :options="
            domaines.map((domaine) => {
              return {
                label: domaine.name.fr,
                value: domaine.id,
              }
            })
          "
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          name="type"
          label="Type de mission"
          :value="query['filter[type]']"
          :options="$store.getters.taxonomies.mission_types.terms"
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          name="format"
          label="Format de mission"
          :value="query['filter[format]']"
          :options="$store.getters.taxonomies.mission_formats.terms"
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          name="state"
          label="Statut"
          multiple
          :value="query['filter[state]']"
          :options="$store.getters.taxonomies.mission_workflow_states.terms"
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          name="place"
          label="Places restantes"
          :value="query['filter[place]']"
          :options="[
            { label: 'Oui', value: true },
            { label: 'Non', value: false },
          ]"
          @changed="onFilterChange"
        />
      </div>
    </div>

    <TableMissions
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
      <div class="ml-auto">
        <el-button
          :loading="loadingExport"
          icon="el-icon-download"
          size="small"
          @click="onExport"
        >
          Export
        </el-button>
      </div>
    </div>
    <portal to="volet">
      <VoletMission @deleted="onDeletedRow" />
    </portal>
  </div>
</template>

<script>
import TableWithVolet from '@/mixins/table-with-volet'
import TableWithFilters from '@/mixins/table-with-filters'
import fileDownload from 'js-file-download'

export default {
  mixins: [TableWithFilters, TableWithVolet],
  layout: 'dashboard',
  asyncData({ store, error }) {
    if (
      !['admin', 'referent', 'referent_regional', 'superviseur'].includes(
        store.getters.contextRole
      )
    ) {
      return error({ statusCode: 403 })
    }
  },
  data() {
    return {
      loadingExport: false,
      collectivities: [],
      responsables: [],
      domaines: [],
      templates: [],
    }
  },
  async fetch() {
    this.query = this.$route.query
    const { data } = await this.$api.fetchMissions(this.query)
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
  watch: {
    '$route.query': '$fetch',
  },
  async created() {
    // @TODO: Filtre autocomplete
    const collectivities = await this.$api.fetchCollectivities({
      'filter[state]': 'validated',
      pagination: 1000,
    })
    this.collectivities = collectivities.data.data

    const domaines = await this.$api.fetchTags({ 'filter[type]': 'domaine' })
    this.domaines = domaines.data.data

    const templates = await this.$api.fetchMissionTemplates({
      pagination: 1000,
    })
    this.templates = templates.data.data

    // @TODO: Liste des responsables
    // if (
    //     this.$store.getters.contextRole === 'responsable' &&
    //     this.$store.getters.structure
    //   ) {
    //     getStructureMembers(this.$store.getters.structure.id).then(
    //       (res) => {
    //         this.responsables = res.data
    //       }
    //     )
    //   }
  },
  methods: {
    onExport() {
      this.loadingExport = true
      this.$api
        .exportMissions(this.query)
        .then((response) => {
          this.loadingExport = false
          fileDownload(response.data, 'missions.xlsx')
        })
        .catch((error) => {
          console.log('exportMissions', error)
        })
    },
  },
}
</script>
