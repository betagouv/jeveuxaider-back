<template>
  <div class="missions has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">Missions</div>
      </div>
      <div>
        <router-link
          v-if="$store.getters.contextRole === 'responsable'"
          :to="{
            name: 'DashboardMissionFormAdd',
            params: {
              structureId: $store.getters.structure_as_responsable.id,
            },
          }"
        >
          <el-button type="primary"> Créer une mission </el-button>
        </router-link>
      </div>
    </div>
    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <query-main-search-filter
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
        <query-search-filter
          name="lieu"
          label="Lieu"
          placeholder="Ville ou code postal"
          :initial-value="query['filter[lieu]']"
          @changed="onFilterChange"
        />
        <query-filter
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
        <query-filter
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
        <query-filter
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
        <query-filter
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
        <query-filter
          name="type"
          label="Type de mission"
          :value="query['filter[type]']"
          :options="$store.getters.taxonomies.mission_types.terms"
          @changed="onFilterChange"
        />
        <query-filter
          name="format"
          label="Format de mission"
          :value="query['filter[format]']"
          :options="$store.getters.taxonomies.mission_formats.terms"
          @changed="onFilterChange"
        />
        <query-filter
          name="state"
          label="Statut"
          multiple
          :value="query['filter[state]']"
          :options="$store.getters.taxonomies.mission_workflow_states.terms"
          @changed="onFilterChange"
        />
        <query-filter
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
        <el-button icon="el-icon-download" size="small" @click="onExport">
          Export
        </el-button>
      </div>
    </div>
    <portal to="volet">
      <mission-volet @updated="onUpdatedRow" @deleted="onDeletedRow" />
    </portal>
  </div>
</template>

<script>
import { fetchMissions, exportMissions } from '@/api/mission'
import {
  fetchTags,
  fetchMissionTemplates,
  fetchCollectivities,
} from '@/api/app'
import TableWithFilters from '@/mixins/TableWithFilters'
import TableWithVolet from '@/mixins/TableWithVolet'
import QueryFilter from '@/components/QueryFilter.vue'
import QuerySearchFilter from '@/components/QuerySearchFilter.vue'
import QueryMainSearchFilter from '@/components/QueryMainSearchFilter.vue'
import MissionVolet from '@/layouts/components/Volet/MissionVolet.vue'
import fileDownload from 'js-file-download'
import TableMissions from '@/components/TableMissions'

export default {
  name: 'Missions',
  components: {
    QueryFilter,
    QuerySearchFilter,
    QueryMainSearchFilter,
    MissionVolet,
    TableMissions,
  },
  mixins: [TableWithFilters, TableWithVolet],
  data() {
    return {
      loading: true,
      tableData: [],
      domaines: [],
      templates: [],
      collectivities: [],
    }
  },
  created() {
    fetchTags({ 'filter[type]': 'domaine' }).then((res) => {
      this.domaines = res.data.data
    })
    fetchMissionTemplates({ pagination: 1000 }).then((res) => {
      this.templates = res.data.data
    })
    fetchCollectivities({
      'filter[state]': 'validated',
      pagination: 1000,
    }).then((res) => {
      this.collectivities = res.data.data
    })
  },
  methods: {
    fetchRows() {
      return fetchMissions(this.query)
    },
    onExport() {
      this.loading = true
      exportMissions(this.query)
        .then((response) => {
          this.loading = false
          fileDownload(response.data, 'missions.xlsx')
        })
        .catch((error) => {
          console.log(error)
        })
    },
  },
}
</script>
