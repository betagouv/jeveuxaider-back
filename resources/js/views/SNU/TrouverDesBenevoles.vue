<template>
  <div class="missions">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">Participations</div>
      </div>
    </div>
    <div
      v-if="$store.getters.contextRole === 'responsable'"
      class="px-12 mb-12"
    >
      <participations-menu
        index="/dashboard/participations/trouver-des-benevoles"
      />
    </div>
    <el-card shadow="never" class="mx-12 -mt-6 mb-4 p-3">
      <div>
        Proposer directement vos missions aux bénévoles les plus actifs.
      </div>
      <div class="text-gray-400 mt-3">
        Les bénévoles ci-dessous sont sélectionnés selon les domaines d'action
        de vos missions et leurs zones géographiques.<br />
        Un e-mail proposant votre mission leur sera envoyé.
      </div>
    </el-card>
    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <el-badge v-if="activeFilters" :value="activeFilters" type="primary">
          <el-button
            icon="el-icon-s-operation"
            @click="showFilters = !showFilters"
          >
            Filtres avancés
          </el-button>
        </el-badge>
        <el-button
          v-else
          icon="el-icon-s-operation"
          @click="showFilters = !showFilters"
        >
          Filtres avancés
        </el-button>
      </div>
      <div v-if="showFilters" class="flex flex-wrap">
        <query-filter
          type="select"
          name="domaines"
          :value="query['filter[domaines]']"
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
      <el-table-column prop="name" label="Domaines d'actions" min-width="320">
        <template slot-scope="scope">
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
      <el-table-column prop="name" label="Disponnibilités" min-width="320">
        <template slot-scope="scope">
          <div class="text-secondary text-sm">
            <!-- TODO : Implode with " / "  -->
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
      <el-table-column label="Actions" width="360">
        <template slot-scope="scope">
          <el-dropdown
            v-if="
              notifications.filter(
                (notification) => notification.profile_id == scope.row.id
              ).length == 0
            "
            trigger="click"
          >
            <el-button
              size="small"
              type="primary"
              :disabled="!isBenevoleMatchingOneMission(scope.row)"
            >
              Proposer une mission<i
                class="el-icon-arrow-down el-icon--right"
              ></i>
            </el-button>
            <el-dropdown-menu v-if="missions" slot="dropdown">
              <div
                v-for="mission in missionsMatchingBenevole(scope.row)"
                :key="mission.id"
                @click="handleSendNotfication(scope.row, mission)"
              >
                <el-dropdown-item>
                  {{ mission.name }}
                  <span v-if="mission.city">- {{ mission.city }}</span>
                </el-dropdown-item>
              </div>
            </el-dropdown-menu>
            <div v-if="!isBenevoleMatchingOneMission(scope.row)">
              Aucune missions ne correspond aux critères du bénévole
            </div>
          </el-dropdown>
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
import { fetchMissions } from '@/api/mission'
import {
  addNotificationBenevole,
  fetchNofiticationsBenevoles,
} from '@/api/notification-benevole'
import TableWithFilters from '@/mixins/TableWithFilters'
import TableWithVolet from '@/mixins/TableWithVolet'
import QueryFilter from '@/components/QueryFilter.vue'
import ProfileVolet from '@/layout/components/Volet/ProfileVolet.vue'
import ParticipationsMenu from '@/components/ParticipationsMenu.vue'

export default {
  name: 'Participations',
  components: {
    QueryFilter,
    ProfileVolet,
    ParticipationsMenu,
  },
  mixins: [TableWithFilters, TableWithVolet],
  data() {
    return {
      loading: true,
      domaines: [],
      tableData: [],
      missions: [],
      notifications: [],
    }
  },
  created() {
    fetchMissions({
      'filter[structure_id]': this.$store.getters.structure_as_responsable.id,
      'filter[state]': 'Validée',
      'filter[place]': true,
      append: 'domaines',
    }).then((res) => {
      this.missions = res.data.data
    })
    fetchTags({ 'filter[type]': 'domaine' }).then((res) => {
      this.domaines = res.data.data
    })
    this.fetchNotificationsBenevoles()
  },
  methods: {
    isBenevoleMatchingOneMission(benevole) {
      return this.missionsMatchingBenevole(benevole).length > 0
    },
    missionsMatchingBenevole(benevole) {
      return this.missions.filter((mission) =>
        this.containsAny(
          benevole.domaines.map((domain) => domain.name.fr),
          mission.domaines
        )
      )
    },
    containsAny(source, target) {
      return (
        source.filter(function (item) {
          return target.indexOf(item) > -1
        }).length > 0
      )
    },
    fetchRows() {
      // Un domaine d'action en commun
      return fetchProfiles(
        {
          ...this.query,
        },
        ['roles', 'has_user', 'skills', 'domaines']
      )
    },
    fetchNotificationsBenevoles() {
      fetchNofiticationsBenevoles().then((res) => {
        this.notifications = res.data.data
      })
    },
    handleSendNotfication(benevole, mission) {
      this.$confirm(
        `<span class="font-semibold">${benevole.first_name} ${benevole.last_name[0]}</span> recevra un email pour l'inviter à participer à votre mission <span class="font-semibold">${mission.name}</span>.`,
        'Envoyer une notification email',
        {
          confirmButtonText: `Envoyer un email à ${benevole.first_name} ${benevole.last_name[0]}`,
          confirmButtonClass: 'el-button--primary',
          cancelButtonText: 'Annuler',
          center: true,
          dangerouslyUseHTMLString: true,
        }
      ).then(() => {
        addNotificationBenevole(mission.id, benevole.id).then(() => {
          this.$message({
            type: 'success',
            message: `Un email a été envoyé à ${benevole.first_name} ${benevole.last_name[0]}.`,
          })
        })
        this.notifications.push({ profile_id: benevole.id })
      })
    },
  },
}
</script>
