<template>
  <div class="mission-view">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          Mission
        </div>
        <div v-if="mission" class="flex flex-wrap mb-8">
          <div class="font-bold text-2xl text-gray-800 mr-2">
            {{ mission.name }}
          </div>
          <state-tag :state="mission.state" />
        </div>
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
            <div class="mb-6 text-xl">
              Informations
            </div>
            <router-link :to="{ name: 'MissionFormEdit', params: { id } }">
              <el-button size="small" type="secondary" icon="el-icon-edit">
                Modifier
              </el-button>
            </router-link>
          </div>
          <mission-infos v-if="mission" :mission="mission" />
        </el-card>
      </div>
    </div>
    <div v-else-if="tab == 'history'">
      <TableActivities :table-data="activities" />
    </div>
    <div v-else-if="tab == 'participations'">
      <TableParticipations :table-data="participations" />
    </div>
  </div>
</template>

<script>
import { fetchActivities } from '@/api/app'
import { getMission } from '@/api/mission'
import { fetchParticipations } from '@/api/participation'
import TableActivities from '@/components/TableActivities'
import StateTag from '@/components/StateTag'
import MissionInfos from '@/components/infos/MissionInfos'
import TableParticipations from '@/components/TableParticipations'

export default {
  name: 'Mission',
  components: {
    StateTag,
    TableActivities,
    TableParticipations,
    MissionInfos,
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
      mission: null,
      activities: [],
      participations: [],
    }
  },
  async created() {
    const response = await getMission(this.id)
    this.mission = response.data

    if (this.tab == 'history') {
      const { data } = await fetchActivities({
        'filter[subject_id]': this.id,
        'filter[subject_type]': 'Mission',
      })
      this.activities = data.data
    }

    if (this.tab == 'participations') {
      const { data } = await fetchParticipations({
        'filter[mission.id]': this.id,
      })
      this.participations = data.data
    }
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
