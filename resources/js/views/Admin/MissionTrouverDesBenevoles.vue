<template>
  <div class="missions">
    <div class="header px-12 flex border-b border-gray-200 pb-4">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Trouver des bénévoles</div>
        <div class="mb-1 font-bold text-2xl text-gray-800">
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
        <query-filter
          name="zips"
          label="Codes postaux"
          multiple
          allow-create
          default-first-option
          :value="query['filter[zips]']"
          :options="[]"
          @changed="onFilterChange"
        />
        <query-filter
          type="select"
          name="disponibilities"
          :value="query['filter[disponibilities]']"
          label="Disponibilités"
          :options="$store.getters.taxonomies.profile_disponibilities.terms"
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
            {{ scope.row.short_name }}
          </el-avatar>
        </template>
      </el-table-column>
      <el-table-column prop="name" label="Bénévole" min-width="120">
        <template slot-scope="scope">
          <div class="text-gray-900">
            {{ scope.row.first_name }} {{ scope.row.last_name[0] }}.
          </div>
          <div class="text-secondary">
            <div class="text-xs">
              {{ scope.row.zip }}
            </div>
          </div>
        </template>
      </el-table-column>
      <el-table-column prop="name" label="Disponibilités" min-width="320">
        <template slot-scope="scope">
          <div v-if="scope.row.disponibilities" class="text-secondary text-sm">
            {{
              scope.row.disponibilities
                .map(
                  (disponibility) =>
                    $store.getters.taxonomies.profile_disponibilities.terms.filter(
                      (dispo) => dispo.value == disponibility
                    )[0].label
                )
                .join(' / ')
            }}
          </div>
          <div
            v-if="scope.row.frequence && scope.row.frequence_granularite"
            class="text-secondary text-sm"
          >
            {{ scope.row.frequence }} par {{ scope.row.frequence_granularite }}
          </div>
        </template>
      </el-table-column>
      <el-table-column prop="name" label="Domaines d'actions" min-width="320">
        <template v-if="scope.row.tags" slot-scope="scope">
          <el-tag type="info" class="m-1">
            {{
              scope.row.tags.filter((tag) => tag.type == 'domaine')[0].name.fr
            }}
          </el-tag>
          <el-tag
            v-if="
              scope.row.tags.filter((tag) => tag.type == 'domaine').length > 1
            "
            type="info"
            class="m-1"
          >
            +
            {{
              scope.row.tags.filter((tag) => tag.type == 'domaine').length - 1
            }}
            domaines
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column label="Actions" width="360">
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
      <profile-volet />
    </portal>
  </div>
</template>

<script>
import { fetchProfiles } from '@/api/user'
import { fetchTags } from '@/api/app'
import { getMission } from '@/api/mission'
import {
  addNotificationBenevole,
  fetchNofiticationsBenevoles,
} from '@/api/notification-benevole'
import TableWithFilters from '@/mixins/TableWithFilters'
import TableWithVolet from '@/mixins/TableWithVolet'
import QueryFilter from '@/components/QueryFilter.vue'
import ProfileVolet from '@/layout/components/Volet/ProfileVolet.vue'

export default {
  name: 'Participations',
  components: {
    QueryFilter,
    ProfileVolet,
  },
  mixins: [TableWithFilters, TableWithVolet],
  props: {
    id: { type: Number, required: true },
  },
  data() {
    return {
      loading: true,
      domaines: [],
      tableData: [],
      notifications: [],
      mission: {},
    }
  },
  created() {
    getMission(this.id).then((res) => {
      this.mission = res.data
    })
    fetchTags({ 'filter[type]': 'domaine' }).then((res) => {
      this.domaines = res.data.data
    })
    this.fetchNotificationsBenevoles()
  },
  methods: {
    fetchRows() {
      return fetchProfiles(
        {
          'filter[match_mission]': this.id,
          ...this.query,
        },
        ['roles', 'has_user', 'skills', 'domaines']
      )
    },
    fetchNotificationsBenevoles() {
      fetchNofiticationsBenevoles({ 'filter[mission.id]': this.id }).then(
        (res) => {
          this.notifications = res.data.data
        }
      )
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
        addNotificationBenevole(this.mission.id, benevole.id).then(() => {
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
