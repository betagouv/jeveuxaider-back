<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-8 font-bold text-2-5xl text-gray-800">
          Participations
        </div>
      </div>
      <div
        v-if="
          $store.getters.contextRole == 'responsable' &&
          $store.getters.reminders &&
          $store.getters.reminders.participations > 0
        "
      >
        <el-button
          type="primary"
          :loading="loadingButton"
          @click="onMassValidation"
        >
          Valider toutes les participations en attente ({{
            $store.getters.reminders.participations
          }})
        </el-button>
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
          name="state"
          label="Statut"
          multiple
          :value="query['filter[state]']"
          :options="
            $store.getters.taxonomies.participation_workflow_states.terms
          "
          @changed="onFilterChange"
        />
        <!-- <SearchFiltersQuery
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
        /> -->
        <SearchFiltersQueryInput
          name="mission.id"
          label="# Mission"
          placeholder="Numéro"
          :initial-value="query['filter[mission.id]']"
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
        <SearchFiltersQuery
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
        <SearchFiltersQueryAutocompleteCollectivities
          type="select"
          name="collectivity"
          :value="query['filter[collectivity]']"
          label="Collectivité"
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
          name="mission.type"
          label="Type de mission"
          :value="query['filter[mission.type]']"
          :options="$store.getters.taxonomies.mission_types.terms"
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
      <VoletParticipation @updated="onUpdatedRow" @deleted="onDeletedRow" />
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
  middleware({ $api, route, redirect, store }) {
    if (store.getters.contextRole == 'responsable') {
      redirect(
        `/dashboard/${store.getters.contextableType}/${store.state.auth.user.contextable_id}/participations`
      )
    }
  },
  async asyncData({ $api, store, error, params }) {
    if (
      !['admin', 'referent', 'referent_regional', 'superviseur'].includes(
        store.getters.contextRole
      )
    ) {
      return error({ statusCode: 403 })
    }

    const domaines = await $api.fetchTags({ 'filter[type]': 'domaine' })
    const templates = await $api.fetchMissionTemplates({ pagination: 1000 })

    return {
      domaines: domaines.data.data,
      templates: templates.data.data,
    }
  },
  data() {
    return {
      loadingButton: false,
      loadingExport: false,
    }
  },
  async fetch() {
    const { data } = await this.$api.fetchParticipations(this.query)
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
  watch: {
    '$route.query': '$fetch',
  },
  created() {},
  methods: {
    canShowProfileDetails(row) {
      return !!(
        row.mission &&
        (row.mission.state != 'Signalée' ||
          this.$store.getters.contextRole !== 'responsable')
      )
    },
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
    onMassValidation() {
      this.$confirm(
        'Vous êtes sur le point de valider toutes les participations actuellement en attente de validation (' +
          this.$store.getters.reminders.participations +
          ').<br><br>Êtes-vous sûr de vouloir continuer ?',
        'Validation massive',
        {
          confirmButtonText: 'Oui, je confirme',
          cancelButtonText: 'Annuler',
          dangerouslyUseHTMLString: true,
          // center: true,
          // type: 'warning',
        }
      ).then(() => {
        this.loadingButton = true
        this.$api
          .massValidationParticipation()
          .then(() => {
            this.loadingButton = false
            this.$store.dispatch('reminders')
            this.$message.success({
              message: 'Les participations ont été mises à jour',
            })
            this.$fetch()
          })
          .catch(() => {
            this.loadingButton = false
          })
      })
    },
  },
}
</script>
