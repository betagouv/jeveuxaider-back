<template>
  <div v-if="!$store.getters.loading" class="profile-view">
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
            :profile="profile"
            size="medium"
            class="flex items-center ml-3"
          />
        </div>
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
    <div class="px-12 mb-12">
      <div class="mb-6 text-2xl">
        Informations
      </div>
      <profile-infos :profile="profile" />
    </div>
  </div>
</template>

<script>
import { getProfile } from '@/api/user'
import ProfileInfos from '@/components/infos/ProfileInfos'
import ProfileRolesTags from '@/components/ProfileRolesTags.vue'

export default {
  name: 'Profile',
  components: { ProfileRolesTags, ProfileInfos },
  props: {
    id: {
      type: Number,
      default: null,
    },
  },
  data() {
    return {
      loading: false,
      profile: {},
      form: {},
    }
  },
  created() {
    this.$store.commit('setLoading', true)
    getProfile(this.id)
      .then((response) => {
        this.$store.commit('setLoading', false)
        this.profile = response.data
      })
      .catch(() => {
        this.loading = false
      })
  },

  methods: {},
}
</script>
