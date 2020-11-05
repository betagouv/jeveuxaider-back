<template>
  <div class="structure-view">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          Collectivité
        </div>
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
            <div class="mb-6 text-xl">
              Informations
            </div>
            <router-link
              :to="{
                name: 'CollectivityFormEdit',
                params: { id: collectivity.id },
              }"
            >
              <el-button size="small" type="secondary" icon="el-icon-edit">
                Modifier
              </el-button>
            </router-link>
          </div>
          <collectivity-infos class="text-sm" :collectivity="collectivity" />
        </el-card>
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between">
            <div v-if="collectivity.profiles" class="mb-6 text-xl">
              Responsables
            </div>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <member-teaser
              v-for="member in collectivity.profiles"
              :key="member.id"
              class="member py-2"
              :member="member"
            />
          </div>
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
import { getCollectivity } from '@/api/app'
import { fetchMissions } from '@/api/mission'
import CollectivityInfos from '@/components/infos/CollectivityInfos'
import TableActivities from '@/components/TableActivities'
import TableMissions from '@/components/TableMissions'
import StateTag from '@/components/StateTag'
import MemberTeaser from '@/components/MemberTeaser'

export default {
  name: 'DashboardCollectivity',
  components: {
    CollectivityInfos,
    StateTag,
    MemberTeaser,
    TableActivities,
    TableMissions,
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
      loading: false,
      collectivity: {},
      activities: [],
      missions: [],
    }
  },
  async created() {
    const response = await getCollectivity(this.id)
    this.collectivity = response.data

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
