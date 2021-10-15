<template>
  <div class="has-full-table">
    <HeaderOrganisation :structure="structure">
      <template #action>
        <nuxt-link :to="`/dashboard/structure/${structure.id}/missions/add`">
          <el-button type="primary">Créer une mission</el-button>
        </nuxt-link>
      </template>
    </HeaderOrganisation>
    <NavTabStructure
      v-if="$store.getters.contextRole != 'responsable'"
      :structure="structure"
    />
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
            class="!ml-4"
            @click="showFilters = !showFilters"
          >
            Filtres avancés
          </el-button>
        </el-badge>
        <el-button
          v-else
          icon="el-icon-s-operation"
          class="!ml-4"
          @click="showFilters = !showFilters"
        >
          Filtres avancés
        </el-button>
      </div>
      <div v-if="showFilters" class="flex flex-wrap gap-4 mb-4">
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
        <SearchFiltersQuery
          name="dates"
          label="Dates"
          :value="query['filter[dates]']"
          :options="[
            { label: 'À venir', value: 'incoming' },
            { label: 'En cours', value: 'current' },
            { label: 'Date de fin passée', value: 'outdated' },
          ]"
          @changed="onFilterChange"
        />
        <SearchFiltersQueryCommitment
          label="Engagement minimum"
          :value="query['filter[minimum_commitment]']"
          name="minimum_commitment"
          @changed="onFilterChange"
        />
        <SearchFiltersQueryInput
          name="id"
          label="# Mission"
          placeholder="Numéro"
          :initial-value="query['filter[id]']"
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
      <VoletMission @deleted="onDeletedRow" @updated="onUpdatedRow" />
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
  async asyncData({ $api, params, store }) {
    const structure = await $api.getStructure(params.id)

    const { data: domaines } = await $api.fetchTags({
      'filter[type]': 'domaine',
    })

    const { data: templates } = await $api.fetchMissionTemplates({
      pagination: 1000,
    })

    const responsables = await $api.getStructureMembers(params.id)

    return {
      structure,
      domaines: domaines.data,
      templates: templates.data,
      responsables: responsables.data,
    }
  },
  data() {
    return {
      loadingExport: false,
    }
  },
  async fetch() {
    this.query['filter[structure_id]'] = this.structure.id
    const { data } = await this.$api.fetchMissions(this.query)
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
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
          this.loadingExport = false
          console.log('exportMissions', error)
        })
    },
  },
}
</script>

<style scoped lang="postcss">
.el-menu--horizontal {
  @apply px-12;
  > .el-menu-item {
    @apply mr-8 p-0 font-medium;
    border-bottom: solid 3px #070191;
  }
}
</style>
