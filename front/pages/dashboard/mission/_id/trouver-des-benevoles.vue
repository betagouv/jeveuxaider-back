<template>
  <div class="missions">
    <div class="header px-12 flex border-b border-gray-200 pb-4">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Trouver des bénévoles</div>
        <div class="mb-1 font-bold text-2-5xl text-gray-800">
          {{ mission.name }}
        </div>
        <div class="text-xl text-gray-800 flex items-center">
          <div
            v-if="mission.type"
            class="border-r leading-none border-gray-300 mr-3 pr-3"
          >
            {{ mission.type }}
          </div>
          <div
            v-if="mission.format"
            class="border-r leading-none border-gray-300 mr-3 pr-3"
          >
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
      <div class="text-gray-500 mt-1 flex flex-wrap items-center space-x-4">
        <div v-if="mission.domaines">
          <div class="text-secondary text-xs uppercase font-semibold mb-2">
            Domaines
          </div>
          <div class="flex flex-wrap space-x-4">
            <el-tag
              v-for="domaine in mission.domaines"
              :key="domaine.id"
              type="info"
              size="sm"
            >
              {{ domaine.name.fr }}
            </el-tag>
          </div>
        </div>
        <div
          v-if="mission.type == 'Mission en présentiel' && mission.department"
        >
          <div class="text-secondary text-xs uppercase font-semibold mb-2">
            Département
          </div>
          <el-tag type="info" size="sm">
            <span class="font-semibold">Département:</span>
            {{ mission.department | labelFromValue('departments') }}
          </el-tag>
        </div>
      </div>
      <div class="mt-6 flex flex-wrap">
        <SearchFiltersQueryCommitment
          label="Engagement minimum"
          :minimum-commitment="query['filter[minimum_commitment]']"
          class="w-64"
        />
        <SearchFiltersQuery
          v-if="mission.type == 'Mission en présentiel'"
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
        <SearchFiltersQuerySkills
          type="select"
          name="skills"
          multiple
          :value="query['filter[skills]']"
          label="Compétences"
          :options="$store.getters.taxonomies.profile_disponibilities.terms"
          @changed="onFilterChange"
        />
      </div>
    </div>

    <div class="px-12 my-6">
      <ul
        class="
          grid grid-cols-1
          gap-6
          xs:grid-cols-2
          lg:grid-cols-2
          xl:grid-cols-3
        "
      >
        <li
          v-for="item in tableData"
          :key="item.id"
          class="col-span-1 bg-white rounded-lg shadow flex flex-col"
        >
          <div class="p-6 flex-1 flex flex-col space-y-4">
            <div class="w-full flex items-center justify-between space-x-4">
              <Avatar
                :source="item.image ? item.image.thumb : null"
                :fallback="item.short_name"
              />
              <div class="flex-1 truncate">
                <h3 class="text-gray-900 text-sm font-bold truncate">
                  {{ item.first_name }} {{ item.last_name[0] }}.
                </h3>
                <div
                  class="
                    flex-shrink-0
                    inline-block
                    px-2
                    py-0.5
                    text-green-800 text-xs
                    font-medium
                    bg-green-100
                    rounded-full
                  "
                >
                  <span>
                    {{ item.commitment__hours }}
                    {{
                      item.commitment__hours | pluralize(['heure', 'heures'])
                    }}
                  </span>
                  <template v-if="item.commitment__time_period">
                    <span class="font-normal">par</span>
                    <span>
                      {{
                        item.commitment__time_period
                          | labelFromValue('time_period')
                      }}
                    </span>
                  </template>
                </div>
              </div>
              <div v-if="item.zip" class="text-secondary text-sm">
                📍 {{ item.zip }}
              </div>
            </div>

            <div class="h-10 text-gray-500 text-sm">
              {{
                item.disponibilities
                  .map(
                    (disponibility) =>
                      $store.getters.taxonomies.profile_disponibilities.terms.filter(
                        (dispo) => dispo.value == disponibility
                      )[0].label
                  )
                  .join(' • ')
              }}
            </div>
            <div class="border-t border-dashed pt-4 flex flex-col space-y-2">
              <div class="text-xs uppercase text-gray-900 font-bold">
                Compétences
              </div>
              <div class="text-gray-500 text-sm">
                <ReadMore
                  v-if="item.skills.length"
                  more-class="cursor-pointer uppercase font-bold text-xs text-gray-800"
                  more-str="voir plus"
                  :text="
                    item.skills
                      .map(function (item) {
                        return item.name.fr
                      })
                      .join(', ')
                  "
                  :max-chars="80"
                ></ReadMore>
                <template v-else>Non renseignées</template>
              </div>
            </div>
          </div>

          <div class="text-center font-bold border-gray-200 border-t">
            <div
              v-if="
                notifications.filter(
                  (notification) => notification.profile_id == item.id
                ).length == 0
              "
              class="py-4 text-primary hover:bg-gray-50 cursor-pointer"
              @click="handleSendNotfication(item)"
            >
              Proposer la mission
            </div>
            <div v-else class="py-4 text-green-700">E-mail envoyé !</div>
          </div>
        </li>
      </ul>
    </div>

    <div class="px-12 flex items-center">
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
    <!-- <portal to="volet">
      <VoletProfile hide-personal-fields @updated="onUpdatedRow" />
    </portal> -->
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
