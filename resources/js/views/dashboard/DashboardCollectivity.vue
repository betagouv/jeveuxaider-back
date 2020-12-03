<template>
  <div v-if="!loading" class="structure-view">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Collectivité</div>
        <div class="flex flex-wrap mb-8">
          <div class="font-bold text-2xl text-gray-800 mr-2">
            {{ collectivity.name }}
          </div>
          <state-tag v-if="collectivity.state" :state="collectivity.state" />
          <el-tag v-if="collectivity.published" class="m-1 ml-0" size="medium">
            En ligne
          </el-tag>
          <el-tag v-if="!collectivity.published" class="m-1 ml-0" size="medium">
            Hors ligne
          </el-tag>
        </div>
      </div>
      <div v-if="collectivity">
        <el-dropdown split-button type="primary" @command="handleCommand">
          <router-link
            :to="{
              name: 'DashboardCollectivityFormEdit',
              params: { id: collectivity.id },
            }"
          >
            Modifier la collectivité
          </router-link>
          <el-dropdown-menu slot="dropdown">
            <router-link
              :to="{
                name: 'Collectivity',
                params: { slug: collectivity.slug },
              }"
              target="_blank"
            >
              <el-dropdown-item> Visualiser la collectivité</el-dropdown-item>
            </router-link>

            <el-dropdown-item divided :command="{ action: 'delete' }">
              Supprimer la collectivité
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
      <el-menu-item :index="`/dashboard/collectivity/${id}`">
        Informations
      </el-menu-item>
      <el-menu-item :index="`/dashboard/collectivity/${id}/history`">
        Historique
      </el-menu-item>
    </el-menu>

    <div v-if="!tab" class="px-12">
      <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between">
            <div class="mb-6 text-xl">Informations</div>
          </div>
          <collectivity-infos :collectivity="collectivity" />
        </el-card>
        <el-card v-if="collectivity.structure" shadow="never" class="p-4">
          <div class="flex justify-between">
            <router-link
              :to="{
                name: 'DashboardStructure',
                params: { id: collectivity.structure.id },
              }"
              class="mb-6 text-xl hover:text-blue-800"
              >{{ collectivity.structure.name }}</router-link
            >
          </div>
          <structure-infos :structure="collectivity.structure" />
        </el-card>
      </div>
    </div>
    <div v-else-if="tab == 'history'">
      <TableActivities :table-data="activities" />
    </div>
  </div>
</template>

<script>
import { fetchActivities } from '@/api/app'
import { getCollectivity, deleteCollectivity } from '@/api/app'
import StructureInfos from '@/components/infos/StructureInfos'
import CollectivityInfos from '@/components/infos/CollectivityInfos'
import TableActivities from '@/components/TableActivities'
import StateTag from '@/components/StateTag'
import MemberTeaser from '@/components/MemberTeaser'

export default {
  name: 'DashboardCollectivity',
  components: {
    CollectivityInfos,
    StateTag,
    MemberTeaser,
    TableActivities,
    StructureInfos,
  },
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
      collectivity: {},
      activities: [],
      missions: [],
    }
  },
  async created() {
    const response = await getCollectivity(this.id)
    this.collectivity = response.data
    this.loading = false

    if (this.tab == 'history') {
      const { data } = await fetchActivities({
        'filter[subject_id]': this.id,
        'filter[subject_type]': 'Collectivity',
      })
      this.activities = data.data
    }
    // if (this.tab == 'missions') {
    //   const { data } = await fetchMissions({
    //     'filter[collectivity_id]': this.id,
    //   })
    //   this.missions = data.data
    // }
  },
  methods: {
    // onUpdatedRowMissions(row) {
    //   let foundIndex = this.missions.findIndex((el) => el.id === row.id)
    //   this.missions.splice(foundIndex, 1, row)
    // },
    handleCommand(command) {
      if (command.action == 'delete') {
        this.handleClickDelete(command.id)
      } else {
        this.$router.push(command)
      }
    },
    handleClickDelete() {
      this.$confirm(
        `Êtes vous sur de vouloir supprimer cette collectivité ?`,
        'Supprimer cette collectivité',
        {
          confirmButtonText: 'Supprimer',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          center: true,
          type: 'error',
        }
      ).then(() => {
        deleteCollectivity(this.collectivity.id).then(() => {
          this.$message({
            type: 'success',
            message: `La collectivité a été supprimée.`,
          })
          this.$router.push('/dashboard/collectivities')
        })
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
    border-bottom: solid 3px #1e429f
</style>
