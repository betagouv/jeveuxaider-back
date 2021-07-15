<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Mission</div>
        <div class="mb-8 max-w-3xl">
          <div class="font-bold text-2-5xl text-gray-800 mr-2">
            {{ mission.name }}
          </div>
          <div
            v-if="!['Signalée'].includes(mission.state)"
            class="font-light text-gray-600 flex items-center"
          >
            <div
              :class="
                structure.state == 'Validée' &&
                ['Validée', 'Terminée'].includes(mission.state)
                  ? 'bg-green-500'
                  : 'bg-red-500'
              "
              class="rounded-full h-2 w-2 mr-2"
            ></div>
            <nuxt-link
              target="_blank"
              :to="`/missions-benevolat/${mission.id}/${mission.slug}`"
            >
              <span class="text-sm underline hover:no-underline">
                {{ $config.appUrl }}/missions-benevolat/{{ mission.id }}/{{
                  mission.slug
                }}
              </span>
            </nuxt-link>
          </div>
          <TagModelState class="mt-4" :state="mission.state" />
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
      <el-menu-item :index="`/dashboard/mission/${mission.id}/statistics`">
        Statistiques
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
import TableWithVolet from '@/mixins/table-with-volet'
import TableWithFilters from '@/mixins/table-with-filters'
import fileDownload from 'js-file-download'

export default {
  mixins: [TableWithFilters, TableWithVolet],
  layout: 'dashboard',
  async asyncData({ $api, params, store, error }) {
    if (
      !['admin', 'superviseur', 'responsable'].includes(
        store.getters.contextRole
      )
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
    this.query['filter[mission.id]'] = this.mission.id
    const { data } = await this.$api.fetchParticipations(this.query)
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
    // onMassValidation() {
    //   this.$confirm(
    //     'Vous êtes sur le point de valider toutes les participations actuellement en attente de validation (' +
    //       this.$store.getters.reminders.participations +
    //       ').<br><br>Êtes-vous sûr de vouloir continuer ?',
    //     'Validation massive',
    //     {
    //       confirmButtonText: 'Oui, je confirme',
    //       cancelButtonText: 'Annuler',
    //       dangerouslyUseHTMLString: true,
    //       // center: true,
    //       // type: 'warning',
    //     }
    //   ).then(() => {
    //     this.loadingButton = true
    //     this.$api
    //       .massValidationParticipation()
    //       .then(() => {
    //         this.loadingButton = false
    //         this.$store.dispatch('reminders')
    //         this.$message.success({
    //           message: 'Les participations ont été mises à jour',
    //         })
    //         this.$fetch()
    //       })
    //       .catch(() => {
    //         this.loadingButton = false
    //       })
    //   })
    // },
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
