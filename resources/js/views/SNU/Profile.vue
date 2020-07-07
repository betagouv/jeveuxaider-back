<template>
  <div class="profile-view">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          Utilisateur
        </div>
        <div class="mb-8 flex">
          <div class="font-bold text-2xl text-gray-800">
            {{ profile.first_name }} {{ profile.last_name }}
          </div>
          <profile-roles-tags
            v-if="profile.roles"
            :profile="profile"
            size="medium"
            class="flex items-center ml-3"
          />
        </div>
      </div>
    </div>
    <el-menu
      :default-active="$router.history.current.path"
      mode="horizontal"
      class="mb-8"
      @select="$router.push($event)"
    >
      <el-menu-item :index="`/dashboard/profile/${id}`">
        Informations
      </el-menu-item>
      <el-menu-item :index="`/dashboard/profile/${id}/activities`">
        Activité
      </el-menu-item>
    </el-menu>

    <div v-if="!tab" class="px-12 grid grid-cols-1 gap-4 xl:grid-cols-2">
      <el-card shadow="never" class="p-4">
        <div class="flex justify-between">
          <div class="mb-6 text-xl">
            Informations
          </div>
          <router-link
            v-if="$store.getters.contextRole == 'admin'"
            :to="{ name: 'ProfileFormEdit', params: { id: profile.id } }"
          >
            <el-button type="secondary" icon="el-icon-edit">
              Modifier la fiche
            </el-button>
          </router-link>
        </div>
        <profile-infos v-if="profile.roles" :profile="profile" />
      </el-card>
    </div>
    <div v-else-if="tab == 'activities'">
      <TableActivities :table-data="activities" />
    </div>
  </div>
</template>

<script>
import { getProfile } from '@/api/user'
import { fetchActivities } from '@/api/app'
import TableActivities from '@/components/TableActivities'
import ProfileInfos from '@/components/infos/ProfileInfos'
import ProfileRolesTags from '@/components/ProfileRolesTags.vue'

export default {
  name: 'Profile',
  components: { ProfileRolesTags, ProfileInfos, TableActivities },
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
      profile: {},
      activities: [],
    }
  },
  async created() {
    const response = await getProfile(this.id)
    this.profile = response.data

    if (this.tab == 'activities') {
      const { data } = await fetchActivities({
        'filter[subject_id]': this.id,
        'filter[subject_type]': 'Profile',
      })
      this.activities = data.data
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
