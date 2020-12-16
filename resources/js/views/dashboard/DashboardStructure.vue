<template>
  <div class="structure-view">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Organisation</div>
        <div class="flex flex-wrap mb-8">
          <div class="font-bold text-2xl text-gray-800 mr-2">
            {{ structure.name }}
          </div>
          <state-tag v-if="structure.state" :state="structure.state" />
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
      </div>
      <div>
        <el-dropdown
          v-if="$store.getters.contextRole == 'admin'"
          split-button
          type="primary"
          @command="handleCommand"
        >
          <router-link
            :to="{
              name: 'DashboardStructureFormEdit',
              params: { id: structure.id },
            }"
          >
            Modifier l'organisation
          </router-link>
          <el-dropdown-menu slot="dropdown">
            <router-link
              :to="{
                name: 'DashboardMissionFormAdd',
                params: { structureId: structure.id },
              }"
            >
              <el-dropdown-item>Ajouter une mission</el-dropdown-item>
            </router-link>
            <router-link
              :to="{
                name: 'DashboardStructureMembers',
                params: { id: structure.id },
              }"
            >
              <el-dropdown-item> Gérer les membres </el-dropdown-item>
            </router-link>
            <router-link
              :to="{
                name: 'DashboardStructureMembersAdd',
                params: {
                  id: structure.id,
                },
              }"
            >
              <el-dropdown-item> Ajouter un membre </el-dropdown-item>
            </router-link>
            <el-dropdown-item :command="{ action: 'delete' }" divided>
              Supprimer l'organisation
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
      <el-menu-item :index="`/dashboard/structure/${id}`">
        Informations
      </el-menu-item>
      <el-menu-item :index="`/dashboard/structure/${id}/missions`">
        Missions
        <span class="text-xs text-gray-600"
          >({{ structure.missions_count }})</span
        >
      </el-menu-item>
      <el-menu-item :index="`/dashboard/structure/${id}/history`">
        Historique
      </el-menu-item>
    </el-menu>

    <div v-if="!tab" class="px-12">
      <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between">
            <div class="mb-6 text-xl">Organisation</div>
          </div>
          <structure-infos :structure="structure" />
        </el-card>
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between">
            <div v-if="structure.members" class="mb-6 text-xl">Membres</div>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <member-teaser
              v-for="member in structure.members"
              :key="member.id"
              class="member py-2"
              :member="member"
            />
          </div>
        </el-card>
      </div>
    </div>
    <div v-else-if="tab == 'history'">
      <TableActivities :table-data="tableData" />
    </div>
    <div v-else-if="tab == 'missions'">
      <TableMissions
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
      <mission-volet @updated="onUpdatedRow" @deleted="onDeletedRow" />
    </portal>
  </div>
</template>

<script>
import { fetchActivities } from '@/api/app'
import { getStructure, deleteStructure } from '@/api/structure'
import { fetchMissions } from '@/api/mission'
import StructureInfos from '@/components/infos/StructureInfos'
import TableActivities from '@/components/TableActivities'
import TableWithFilters from '@/mixins/TableWithFilters'
import TableWithVolet from '@/mixins/TableWithVolet'
import TableMissions from '@/components/TableMissions'
import StateTag from '@/components/StateTag'
import MemberTeaser from '@/components/MemberTeaser'
import MissionVolet from '@/layouts/components/Volet/MissionVolet.vue'

export default {
  name: 'DashboardStructure',
  components: {
    StructureInfos,
    StateTag,
    MemberTeaser,
    TableActivities,
    TableMissions,
    MissionVolet,
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
      loading: false,
      structure: {},
      tableData: [],
    }
  },
  methods: {
    async fetchRows() {
      const response = await getStructure(this.id)
      this.structure = response.data

      if (this.tab == 'history') {
        return fetchActivities({
          'filter[subject_id]': this.id,
          'filter[subject_type]': 'Structure',
        })
      }
      if (this.tab == 'missions') {
        return fetchMissions({
          'filter[structure_id]': this.id,
          page: this.$route.query.page || 1,
        })
      }
    },
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
          deleteStructure(this.structure.id).then(() => {
            this.$message({
              type: 'success',
              message: `L'organisation ${this.structure.name} a été supprimée.`,
            })
            this.$router.push('/dashboard/structures')
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
