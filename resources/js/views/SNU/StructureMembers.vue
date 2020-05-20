<template>
  <div class="structure-members">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ structure.name }}
        </div>
        <div class="mb-12 font-bold text-2xl text-gray-800">
          Gérer votre équipe
        </div>
      </div>
      <router-link :to="`/dashboard/structure/${id}/members/add`">
        <el-button type="primary" icon="el-icon-plus">
          Inviter un membre
        </el-button>
      </router-link>
    </div>
    <el-divider />
    <div class="px-12">
      <div class="text-sm font-medium text-secondary mb-4">
        Membres
      </div>
      <div v-for="member in members" :key="member.id" class="member py-4 px-6">
        <div class="flex items-center">
          <el-avatar class="bg-primary w-10 rounded-full">
            {{ member.first_name[0] }}{{ member.last_name[0] }}
          </el-avatar>
          <div class="flex flex-col ml-6" style="min-width: 350px;">
            <div class="text-gray-800">
              {{ member.first_name }} {{ member.last_name }}
            </div>
            <div class="uppercase text-xs text-secondary">
              {{ member.pivot.role }}
            </div>
            <div class="text-xs text-secondary">
              {{ member.email }} - {{ member.mobile }}
            </div>
          </div>
          <el-button
            v-if="
              members.length > 1 && $store.getters.user.profile.id != member.id
            "
            type="danger"
            icon="el-icon-delete"
            size="small"
            class="ml-4 h-full m-auto is-plain"
            @click="deleteConfirm(member)"
          >
            Supprimer
          </el-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {
  getStructure,
  getStructureMembers,
  deleteMember,
} from '@/api/structure'

export default {
  name: 'StructureMembers',
  props: {
    id: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      structure: {},
      members: {},
    }
  },
  created() {
    this.$store.commit('setLoading', true)
    getStructure(this.id)
      .then((response) => {
        this.$store.commit('setLoading', false)
        this.structure = response.data
      })
      .catch((error) => {
        this.$store.commit('setLoading', false)
        this.errors = error.response.data.errors
      })
    getStructureMembers(this.id)
      .then((response) => {
        this.$store.commit('setLoading', false)
        this.members = response.data
      })
      .catch((error) => {
        this.$store.commit('setLoading', false)
        this.errors = error.response.data.errors
      })
  },
  methods: {
    deleteConfirm(member) {
      this.$confirm(
        'Êtes vous sur de vouloir supprimer ce membre ?<br><br>Ce membre ne pourra plus être affecté à de nouvelles missions.<br>',
        'Confirmation',
        {
          confirmButtonText: 'Supprimer',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          type: 'warning',
          center: true,
          dangerouslyUseHTMLString: true,
        }
      )
        .then(() => {
          this.loading = true
          deleteMember(this.structure.id, member.id)
            .then((response) => {
              this.loading = false
              this.$message({
                type: 'success',
                message: 'Le membre a bien été supprimé',
              })
              this.members = response.data
            })
            .catch((error) => {
              this.loading = false
              this.errors = error.response.data.errors
            })
        })
        .catch(() => {})
    },
  },
}
</script>
