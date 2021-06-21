<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Organisation</div>
        <div class="flex items-center flex-wrap">
          <div class="font-bold text-2-5xl text-gray-800 mr-2">
            {{ structure.name }}
          </div>
          <TagModelState v-if="structure.state" :state="structure.state" />
          <el-tag
            v-if="structure.is_reseau"
            size="medium"
            class="m-1 ml-0"
            type="danger"
          >
            Tête de réseau
          </el-tag>
          <el-tag v-if="structure.reseau_id" class="m-1 ml-0" size="medium">
            {{ structure.reseau_id | reseauFromValue }}
          </el-tag>
        </div>
        <div class="font-light text-gray-600 flex items-center">
          <div
            :class="
              structure.state == 'Validée' ? 'bg-green-500' : 'bg-red-500'
            "
            class="rounded-full h-2 w-2 mr-2"
          ></div>
          <nuxt-link
            v-if="structure.state == 'Validée'"
            :to="structure.full_url"
            target="_blank"
            class="underline hover:no-underline"
          >
            {{ structure.full_url }}
          </nuxt-link>
          <span v-else class="cursor-default">
            {{ structure.full_url }}
          </span>
        </div>
      </div>
      <div>
        <nuxt-link
          v-if="$store.getters.contextRole === 'responsable'"
          :to="`/dashboard/structure/${structure.id}/missions/add`"
        >
          <el-button type="primary"> Créer une mission </el-button>
        </nuxt-link>
      </div>
    </div>
    <el-menu
      :default-active="$router.history.current.path"
      mode="horizontal"
      class="my-8"
      @select="$router.push($event)"
    >
      <el-menu-item :index="`/dashboard/structure/${structure.id}`">
        Informations
      </el-menu-item>
      <el-menu-item :index="`/dashboard/structure/${structure.id}/statistics`">
        Statistiques
      </el-menu-item>
      <el-menu-item :index="`/dashboard/structure/${structure.id}/missions`">
        Missions
        <span class="text-xs text-gray-600"
          >({{ structure.missions_count }})</span
        >
      </el-menu-item>
      <el-menu-item
        :index="`/dashboard/structure/${structure.id}/participations`"
      >
        Participations
        <span class="text-xs text-gray-600"
          >({{ structure.participations_count }})</span
        >
      </el-menu-item>
      <el-menu-item :index="`/dashboard/structure/${structure.id}/history`">
        Historique
      </el-menu-item>
    </el-menu>
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
        <SearchFiltersQueryAutocompleteCollectivities
          type="select"
          name="collectivity"
          :value="query['filter[collectivity]']"
          label="Collectivité"
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
  watch: {
    '$route.query': '$fetch',
  },
  methods: {
    handleCommand(command) {
      if (command.action == 'delete') {
        this.handleDeleteStructure()
      }
    },
    handleDeleteStructure() {
      if (this.structure.missions_count > 0) {
        this.$alert(
          'Il est impossible de supprimer une organisation qui contient des missions.',
          "Supprimer l'organisation",
          {
            confirmButtonText: 'Retour',
            type: 'warning',
            center: true,
          }
        )
      } else {
        this.$confirm(
          `L'organisation ${this.structure.name} sera définitivement supprimée de la plateforme.<br><br> Voulez-vous continuer ?<br>`,
          "Supprimer l'organisation",
          {
            confirmButtonText: 'Supprimer',
            confirmButtonClass: 'el-button--danger',
            cancelButtonText: 'Annuler',
            center: true,
            dangerouslyUseHTMLString: true,
            type: 'error',
          }
        ).then(() => {
          this.$api.deleteStructure(this.structure.id).then(() => {
            this.$message({
              type: 'success',
              message: `L'organisation ${this.structure.name} a été supprimée.`,
            })
            this.$router.push('/dashboard/structures')
          })
        })
      }
    },
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

<style scoped lang="sass">
.el-menu--horizontal
  @apply px-12
  > .el-menu-item
    @apply mr-8 p-0 font-medium
      border-bottom: solid 3px #070191
</style>
