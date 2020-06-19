<template>
  <div class="missions has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">
          Participations
        </div>
      </div>
      <div v-if="$store.getters.contextRole == 'responsable'">
        <el-button type="primary" @click="onMassValidation">
          Validation massive
        </el-button>
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
        <query-filter
          name="state"
          label="Statut"
          multiple
          :value="query['filter[state]']"
          :options="
            $store.getters.taxonomies.participation_workflow_states.terms
          "
          @changed="onFilterChange"
        />
        <query-search-filter
          name="mission.id"
          label="# Mission"
          placeholder="Numéro"
          :initial-value="query['filter[mission.id]']"
          @changed="onFilterChange"
        />
        <query-search-filter
          name="lieu"
          label="Lieu"
          placeholder="Ville ou code postal"
          :initial-value="query['filter[lieu]']"
          @changed="onFilterChange"
        />
        <query-filter
          name="mission.department"
          label="Département"
          multiple
          :value="query['filter[mission.department]']"
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
          name="mission.template_id"
          :value="query['filter[mission.template_id]']"
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
          name="mission.type"
          label="Type de mission"
          :value="query['filter[mission.type]']"
          :options="$store.getters.taxonomies.mission_types.terms"
          @changed="onFilterChange"
        />
      </div>
    </div>

    <el-table
      v-loading="loading"
      :data="tableData"
      :highlight-current-row="true"
      @row-click="onClickedRow"
    >
      <el-table-column width="70" align="center">
        <template slot-scope="scope">
          <el-avatar class="bg-primary">
            {{ scope.row.profile.short_name }}
          </el-avatar>
        </template>
      </el-table-column>
      <el-table-column prop="name" label="Volontaire" min-width="320">
        <template slot-scope="scope">
          <template v-if="canShowProfileDetails(scope.row)">
            <div class="text-gray-900">
              {{ scope.row.profile.full_name }}
            </div>
            <div class="font-light text-gray-600">
              <div class="text-xs">
                {{ scope.row.profile.email }}
              </div>
              <div class="text-xs">
                {{ scope.row.profile.mobile }} - {{ scope.row.profile.zip }}
              </div>
            </div>
          </template>
          <template v-else>
            <div class="text-gray-900">
              Anonyme
            </div>
            <div class="font-light text-gray-600 flex items-center">
              <div class="text-xs">
                Coordonnées masquées
              </div>
            </div>
          </template>
        </template>
      </el-table-column>
      <el-table-column prop="name" label="Mission" min-width="320">
        <template slot-scope="scope">
          <div v-if="scope.row.mission" class="text-gray-900">
            <v-clamp :max-lines="1" autoresize
              >#{{ scope.row.mission.id }} -
              {{ scope.row.mission.name }}</v-clamp
            >
          </div>
          <div
            v-if="scope.row.mission && scope.row.mission.structure"
            class="font-light text-gray-600 flex items-center"
          >
            <div class="text-xs">
              <v-clamp :max-lines="1" autoresize>
                {{ scope.row.mission.structure.name }}
              </v-clamp>
            </div>
          </div>
        </template>
      </el-table-column>
      <el-table-column prop="created_at" label="Crée le" min-width="120">
        <template slot-scope="scope">
          <div class="text-sm text-gray-600">
            {{ scope.row.created_at | fromNow }}
          </div>
        </template>
      </el-table-column>
      <el-table-column prop="state" label="Statut" min-width="250">
        <template slot-scope="scope">
          <participation-dropdown-state
            :form="scope.row"
            @updated="onUpdatedRow"
          ></participation-dropdown-state>
        </template>
      </el-table-column>
    </el-table>
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
      <participation-volet @updated="onUpdatedRow" @deleted="onDeletedRow" />
    </portal>
  </div>
</template>

<script>
import {
  fetchParticipations,
  exportParticipations,
  massValidationParticipation,
} from '@/api/participation'
import { fetchTags, fetchMissionTemplates } from '@/api/app'
import TableWithFilters from '@/mixins/TableWithFilters'
import TableWithVolet from '@/mixins/TableWithVolet'
import QueryFilter from '@/components/QueryFilter.vue'
import QuerySearchFilter from '@/components/QuerySearchFilter.vue'
import QueryMainSearchFilter from '@/components/QueryMainSearchFilter.vue'
import ParticipationVolet from '@/layout/components/Volet/ParticipationVolet.vue'
import ParticipationDropdownState from '@/components/ParticipationDropdownState'

import fileDownload from 'js-file-download'

export default {
  name: 'Participations',
  components: {
    ParticipationDropdownState,
    QueryFilter,
    QuerySearchFilter,
    QueryMainSearchFilter,
    ParticipationVolet,
  },
  mixins: [TableWithFilters, TableWithVolet],
  data() {
    return {
      loading: true,
      domaines: [],
      templates: [],
      tableData: [],
    }
  },
  created() {
    fetchTags({ 'filter[type]': 'domaine' }).then((res) => {
      this.domaines = res.data.data
    })
    fetchMissionTemplates().then((res) => {
      this.templates = res.data.data
    })
  },
  methods: {
    canShowProfileDetails(row) {
      return row.mission &&
        (row.mission.state != 'Signalée' ||
          this.$store.getters.contextRole !== 'responsable')
        ? true
        : false
    },
    fetchRows() {
      return fetchParticipations(this.query)
    },
    onExport() {
      this.loading = true
      exportParticipations(this.query)
        .then((response) => {
          this.loading = false
          fileDownload(response.data, 'participations.xlsx')
        })
        .catch((error) => {
          console.log(error)
        })
    },
    onMassValidation() {
      this.$confirm(
        'Vous êtes sur le point de valider toutes les participations actuellement en attente de validation.<br>Êtes-vous sûr de vouloir continuer ?',
        'Confirmation',
        {
          confirmButtonText: 'Je confirme',
          cancelButtonText: 'Annuler',
          dangerouslyUseHTMLString: true,
          center: true,
          type: 'warning',
        }
      ).then(() => {
        this.loading = true
        massValidationParticipation()
          .then(() => {
            this.loading = false
            this.$message({
              type: 'success',
              message: 'Les participations ont été mises à jour',
            })
            this.fetchDatas()
          })
          .catch(() => {
            this.loading = false
          })
      })
    },
  },
}
</script>
