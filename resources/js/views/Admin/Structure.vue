<template>
  <div class="structure-view">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          Organisation
        </div>
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
      <el-menu-item :index="`/dashboard/structure/${id}/activities`">
        Activités
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
              :to="{ name: 'StructureFormEdit', params: { id: structure.id } }"
            >
              <el-button size="small" type="secondary" icon="el-icon-edit">
                Modifier
              </el-button>
            </router-link>
          </div>
          <structure-infos class="text-sm" :structure="structure" />
        </el-card>
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between">
            <div v-if="structure.members" class="mb-6 text-xl">
              Équipe
            </div>
            <div>
              <router-link
                :to="{
                  name: 'StructureMembers',
                  params: {
                    id: structure.id,
                  },
                }"
                class="mr-2"
              >
                <el-button size="small" type="secondary" icon="el-icon-edit">
                  Gérer l'équipe
                </el-button>
              </router-link>
              <router-link
                :to="{
                  name: 'StructureMembersAdd',
                  params: {
                    id: structure.id,
                  },
                }"
              >
                <el-button size="small" type="secondary" icon="el-icon-plus">
                  Ajouter un membre
                </el-button>
              </router-link>
            </div>
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
    <div v-else-if="tab == 'activities'">
      <TableActivities :table-data="activities" />
    </div>
    <div v-else-if="tab == 'missions'">
      <TableMissions
        :table-data="missions"
        :on-updated-row="onUpdatedRowMissions"
      />
    </div>
  </div>
</template>

<script>
import { fetchActivities } from '@/api/app'
import { getStructure } from '@/api/structure'
import { fetchMissions } from '@/api/mission'
import StructureInfos from '@/components/infos/StructureInfos'
import TableActivities from '@/components/TableActivities'
import TableMissions from '@/components/TableMissions'
import StateTag from '@/components/StateTag'
import MemberTeaser from '@/components/MemberTeaser'

export default {
  name: 'Structure',
  components: {
    StructureInfos,
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
      structure: {},
      activities: [],
      missions: [],
    }
  },
  async created() {
    const response = await getStructure(this.id)
    this.structure = response.data

    if (this.tab == 'activities') {
      const { data } = await fetchActivities({
        'filter[subject_id]': this.id,
        'filter[subject_type]': 'Structure',
      })
      this.activities = data.data
    }
    if (this.tab == 'missions') {
      const { data } = await fetchMissions({
        'filter[structure_id]': this.id,
      })
      this.missions = data.data
    }
  },
  methods: {
    onUpdatedRowMissions(row) {
      let foundIndex = this.missions.findIndex((el) => el.id === row.id)
      this.missions.splice(foundIndex, 1, row)
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
