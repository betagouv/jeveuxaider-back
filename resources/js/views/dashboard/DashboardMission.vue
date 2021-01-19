<template>
  <div class="mission-view">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Mission</div>
        <div v-if="mission" class="flex flex-wrap mb-8 max-w-xl">
          <div class="font-bold text-2xl text-gray-800 mr-2">
            {{ mission.name }}
          </div>
          <state-tag :state="mission.state" />
        </div>
      </div>
      <div v-if="mission">
        <el-dropdown split-button type="primary" @command="handleCommand">
          <router-link
            :to="{
              name: 'DashboardMissionFormEdit',
              params: { id: mission.id },
            }"
          >
            Modifier la mission
          </router-link>
          <el-dropdown-menu slot="dropdown">
            <router-link
              :to="{
                name: 'Mission',
                params: { id: mission.id, slug: mission.slug },
              }"
              target="_blank"
            >
              <el-dropdown-item command="">
                Visualiser la mission</el-dropdown-item
              >
            </router-link>
            <el-dropdown-item :command="{ action: 'clone' }"
              >Dupliquer la mission</el-dropdown-item
            >
            <el-dropdown-item
              v-if="$store.getters.contextRole != 'responsable'"
              divided
              :command="{ action: 'delete' }"
            >
              Supprimer la mission
            </el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </div>
    </div>
    <el-menu
      :default-active="$router.history.current.path"
      mode="horizontal"
      class="mb-8"
      @select="$router.push($event)"
    >
      <el-menu-item :index="`/dashboard/mission/${id}`">
        Informations
      </el-menu-item>
      <el-menu-item
        v-if="mission"
        :index="`/dashboard/mission/${id}/participations`"
      >
        Participations
        <span class="text-xs text-gray-600">
          ({{ mission.participations_total }})
        </span>
      </el-menu-item>
      <el-menu-item :index="`/dashboard/mission/${id}/history`">
        Historique
      </el-menu-item>
    </el-menu>

    <div v-if="!tab" class="px-12">
      <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between">
            <div class="mb-6 text-xl">Informations</div>
          </div>
          <mission-infos v-if="mission" :mission="mission" />
        </el-card>
        <div v-if="mission">
          <el-card v-if="mission.responsable" shadow="never" class="p-4 mb-4">
            <div class="flex justify-between">
              <div class="mb-6 text-xl">Responsable</div>
            </div>
            <member-teaser class="member py-2" :member="mission.responsable" />
          </el-card>
          <el-card v-if="structure" shadow="never" class="p-4">
            <div class="flex justify-between">
              <router-link
                :to="{
                  name: 'DashboardStructure',
                  params: { id: structure.id },
                }"
                class="mb-6 text-xl hover:text-blue-800"
                >{{ structure.name }}</router-link
              >
            </div>
            <structure-infos :structure="structure" />
          </el-card>
        </div>
      </div>
    </div>
    <div v-else-if="tab == 'history'">
      <TableActivities :table-data="tableData" />
    </div>
    <div v-else-if="tab == 'participations'">
      <TableParticipations
        :table-data="tableData"
        :on-updated-row="onUpdatedRow"
        :on-clicked-row="onClickedRow"
      />
    </div>
    <div v-if="tab" class="m-3 flex items-center">
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
      <participation-volet @updated="onUpdatedRow" @deleted="onDeletedRow" />
    </portal>
  </div>
</template>

<script>
import { fetchActivities } from '@/api/app'
import {
  getMission,
  getMissionStructure,
  cloneMission,
  deleteMission,
} from '@/api/mission'
import StructureInfos from '@/components/infos/StructureInfos'
import { fetchParticipations } from '@/api/participation'
import TableWithFilters from '@/mixins/TableWithFilters'
import TableWithVolet from '@/mixins/TableWithVolet'
import TableActivities from '@/components/TableActivities'
import StateTag from '@/components/StateTag'
import MissionInfos from '@/components/infos/MissionInfos'
import TableParticipations from '@/components/TableParticipations'
import MemberTeaser from '@/components/MemberTeaser'
import ParticipationVolet from '@/layouts/components/Volet/ParticipationVolet.vue'

export default {
  name: 'DashboardMission',
  components: {
    StateTag,
    TableActivities,
    TableParticipations,
    MissionInfos,
    StructureInfos,
    MemberTeaser,
    ParticipationVolet,
  },
  mixins: [TableWithFilters, TableWithVolet],
  props: {
    id: {
      type: Number,
      default: null,
    },
    tab: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      loading: true,
      mission: null,
      structure: null,
      tableData: [],
    }
  },
  async created() {
    this.loading = false
  },
  methods: {
    async fetchRows() {
      const response = await getMission(this.id)
      this.mission = response.data

      const responseStructure = await getMissionStructure(this.mission.id)
      this.structure = responseStructure.data

      if (this.tab == 'history') {
        return fetchActivities({
          'filter[subject_id]': this.id,
          'filter[subject_type]': 'Mission',
          page: this.$route.query.page || 1,
        })
      }

      if (this.tab == 'participations') {
        return fetchParticipations({
          'filter[mission.id]': this.id,
          page: this.$route.query.page || 1,
        })
      }
    },
    handleCommand(command) {
      if (command.action == 'delete') {
        this.handleDelete()
      } else if (command.action == 'clone') {
        this.hanldleClone()
      } else {
        this.$router.push(command)
      }
    },
    hanldleClone() {
      this.loading = true
      cloneMission(this.mission.id).then((response) => {
        this.$router
          .push({
            path: `/dashboard/mission/${response.data.id}/edit`,
          })
          .then(() => {
            this.$message({
              message: 'La mission a été dupliquée !',
              type: 'success',
            })
          })
      })
    },
    handleDelete() {
      if (this.mission.participations_count > 0) {
        this.$alert(
          'Il est impossible de supprimer une mission déjà assigner à un ou plusieurs bénévoles.',
          'Supprimer la mission',
          {
            confirmButtonText: 'Retour',
            type: 'warning',
            center: true,
          }
        )
      } else {
        this.$confirm(
          `La mission ${this.mission.name} sera définitivement supprimée de la plateforme. Voulez-vous continuer ?`,
          'Supprimer la mission',
          {
            confirmButtonText: 'Supprimer',
            confirmButtonClass: 'el-button--danger',
            cancelButtonText: 'Annuler',
            center: true,
            type: 'error',
          }
        ).then(() => {
          deleteMission(this.mission.id).then(() => {
            this.$message({
              type: 'success',
              message: `La mission ${this.mission.name} a été supprimée.`,
            })
            this.$router.push('/dashboard/missions')
          })
        })
      }
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
