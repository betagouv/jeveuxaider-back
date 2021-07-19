<template>
  <div class="missions">
    <div class="header px-12 flex border-b border-gray-200 pb-4">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Trouver des bénévoles</div>
        <div class="mb-1 font-bold text-2-5xl text-gray-800">
          {{ mission.name }}
        </div>
        <div class="text-xl text-gray-800 flex items-center">
          <div class="border-r leading-none border-gray-300 mr-3 pr-3">
            {{ mission.format }}
          </div>
          <div class="border-r leading-none border-gray-300 mr-3 pr-3">
            {{ mission.places_left }}
            {{
              mission.places_left
                | pluralize(['place disponible', 'places disponibles'])
            }}
          </div>
          <div>
            {{ mission.participations_max - mission.places_left }}/{{
              mission.participations_max
            }}
          </div>
        </div>
      </div>
    </div>
    <div class="px-12 mb-4 mt-4">
      <div class="text-lg font-semibold">
        Proposez directement vos missions aux bénévoles les plus actifs.
      </div>
      <div class="text-gray-500 mt-1">
        Les bénévoles ci-dessous sont sélectionnés en fonction des domaines
        d'action et codes postaux de la mission. <br />En cliquant sur "Proposer
        une mission", un e-mail leur sera envoyé.
      </div>
      <div class="mt-6 flex flex-wrap">
        <SearchFiltersQuery
          name="department"
          label="Département"
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
          name="zips"
          label="Codes postaux"
          multiple
          allow-create
          default-first-option
          :value="query['filter[zips]']"
          :options="[]"
          placeholder="Entrer les codes post."
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          type="select"
          name="disponibilities"
          :value="query['filter[disponibilities]']"
          label="Disponibilités"
          :options="$store.getters.taxonomies.profile_disponibilities.terms"
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
        <SearchFiltersQuerySkills
          type="select"
          name="skills"
          multiple
          :value="query['filter[skills]']"
          label="Compétences"
          :options="$store.getters.taxonomies.profile_disponibilities.terms"
          @changed="onFilterChange"
        />

        <SearchFiltersQueryCommitment
          :minimum-commitment="query['filter[minimum_commitment]']"
        />
      </div>
    </div>

    <el-table
      v-loading="$fetchState.pending"
      :data="tableData"
      :highlight-current-row="true"
      @row-click="onClickedRow"
    >
      <el-table-column width="70" align="center">
        <template slot-scope="scope">
          <Avatar
            :source="scope.row.image ? scope.row.image.thumb : null"
            :fallback="scope.row.short_name"
          />
        </template>
      </el-table-column>
      <el-table-column prop="name" label="Bénévole" min-width="120">
        <template slot-scope="scope">
          <div class="text-gray-900">
            {{ scope.row.first_name }} {{ scope.row.last_name[0] }}.
          </div>
          <div class="text-secondary">
            <div class="text-xs">Habite dans le {{ scope.row.zip }}</div>
          </div>
          <div class="text-secondary">
            <div class="text-xs">
              <template v-if="scope.row.participations_count">
                {{ scope.row.participations_count }} participations déjà
                effectuées
              </template>
              <template v-else> Aucune participation </template>
            </div>
          </div>
        </template>
      </el-table-column>
      <el-table-column prop="name" label="Informations" min-width="320">
        <template slot-scope="scope">
          <div class="text-xs text-secondary">
            <div v-if="scope.row.disponibilities">
              <span class="font-semibold mr-2">Disponibilités:</span>
              <span
                >{{
                  scope.row.disponibilities
                    .map(
                      (disponibility) =>
                        $store.getters.taxonomies.profile_disponibilities.terms.filter(
                          (dispo) => dispo.value == disponibility
                        )[0].label
                    )
                    .join(' / ')
                }}
              </span>
            </div>

            <div v-if="scope.row.commitment__hours">
              <span class="font-semibold mr-2">Fréquence:</span>
              <span>
                {{ scope.row.commitment__hours }}
                {{
                  scope.row.commitment__hours | pluralize(['heure', 'heures'])
                }}
              </span>
              <template v-if="scope.row.commitment__time_period">
                <span class="font-normal">par</span>
                <span>
                  {{
                    scope.row.commitment__time_period
                      | labelFromValue('time_period')
                  }}
                </span>
              </template>
            </div>

            <div v-if="scope.row.skills && scope.row.skills.length > 0">
              <span class="font-semibold mr-2">Compétences:</span>
              <span>{{
                scope.row.skills
                  .map(function (item) {
                    return item.name.fr
                  })
                  .join(', ')
              }}</span>
            </div>

            <div v-if="scope.row.domaines && scope.row.domaines.length > 0">
              <span class="font-semibold mr-2">Domaines:</span>
              <span>{{
                scope.row.domaines
                  .map(function (item) {
                    return item.name.fr
                  })
                  .join(', ')
              }}</span>
            </div>
          </div>
        </template>
      </el-table-column>

      <el-table-column label="Actions">
        <template slot-scope="scope">
          <el-button
            v-if="
              notifications.filter(
                (notification) => notification.profile_id == scope.row.id
              ).length == 0
            "
            size="small"
            type="success"
            round
            @click="handleSendNotfication(scope.row)"
          >
            Proposer la mission
          </el-button>
          <div v-else class="text-sm font-semibold text-green-500">
            E-mail envoyé !
          </div>
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
    </div>
    <portal to="volet">
      <VoletProfile hide-personal-fields @updated="onUpdatedRow" />
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

  data() {
    return {
      domaines: [],
      tableData: [],
      notifications: [],
    }
  },
  async fetch() {
    const { data } = await this.$api.fetchMatchingBenevoles(
      this.mission.id,
      {
        ...this.query,
      },
      ['skills', 'domaines']
    )
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
  watch: {
    '$route.query': '$fetch',
  },
  created() {
    this.$api.fetchTags({ 'filter[type]': 'domaine' }).then((res) => {
      this.domaines = res.data.data
    })
    this.fetchNotificationsBenevoles()
  },
  methods: {
    fetchNotificationsBenevoles() {
      this.$api
        .fetchNofiticationsBenevoles({ 'filter[mission.id]': this.mission.id })
        .then((res) => {
          this.notifications = res.data.data
        })
    },
    handleSendNotfication(benevole) {
      this.$confirm(
        `<span class="font-semibold">${benevole.first_name} ${benevole.last_name[0]}</span> recevra un e-mail pour l'inviter à participer à votre mission <span class="font-semibold">${this.mission.name}</span>.`,
        'Envoyer une notification e-mail',
        {
          confirmButtonText: `Envoyer un e-mail à ${benevole.first_name} ${benevole.last_name[0]}`,
          confirmButtonClass: 'el-button--primary',
          cancelButtonText: 'Annuler',
          center: true,
          dangerouslyUseHTMLString: true,
        }
      ).then(() => {
        this.$api
          .addNotificationBenevole(this.mission.id, benevole.id)
          .then(() => {
            this.$message({
              type: 'success',
              message: `Un e-mail a été envoyé à ${benevole.first_name} ${benevole.last_name[0]}.`,
            })
          })
        this.notifications.push({ profile_id: benevole.id })
      })
    },
  },
}
</script>
