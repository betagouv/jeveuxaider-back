<template>
  <div class="">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ structure.name }}
        </div>
        <div class="mb-12 font-bold text-2-5xl text-gray-800">
          Gérer votre équipe
        </div>
      </div>
      <nuxt-link :to="`/dashboard/structure/${structure.id}/members/add`">
        <el-button type="primary"> Inviter un membre </el-button>
      </nuxt-link>
    </div>
    <el-divider />
    <div class="px-12 mb-12">
      <div class="text-sm font-medium text-secondary mb-4">Membres</div>
      <div v-for="member in members" :key="member.id" class="member py-4 px-6">
        <div class="flex items-center">
          <Avatar
            :source="member.image ? member.image.thumb : null"
            :fallback="member.short_name"
          />
          <div class="flex flex-col ml-6" style="min-width: 350px">
            <div class="text-gray-800">
              {{ member.first_name }} {{ member.last_name }}
            </div>
            <div class="text-xs text-secondary">
              <div class="break-all">{{ member.email }}</div>
              <div class="">{{ member.mobile }}</div>
            </div>
          </div>
          <el-button
            v-if="
              members.length > 1 && $store.getters.user.profile.id != member.id
            "
            type="danger"
            icon="el-icon-delete"
            size="small"
            class="ml-4 m-auto is-plain"
            @click="deleteConfirm(member)"
          >
            Supprimer
          </el-button>
        </div>
      </div>
    </div>
    <div v-if="invitations.length > 0" class="px-12">
      <div class="text-sm font-medium text-secondary mb-4">Invitations</div>
      <div class="grid grid-cols-2 gap-4">
        <div
          v-for="invitation in invitations"
          :key="invitation.id"
          class="py-4 px-6 bg-gray-50 rounded"
        >
          <ModelInvitationTeaser :invitation="invitation" @updated="$fetch()" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params }) {
    const structure = await $api.getStructure(params.id)
    return {
      structure,
    }
  },
  data() {
    return {
      members: [],
      invitations: [],
    }
  },
  async fetch() {
    const members = await this.$api.getStructureMembers(this.$route.params.id)
    this.members = members.data
    const invitations = await this.$api.getStructureInvitations(
      this.$route.params.id
    )
    this.invitations = invitations.data
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
          this.$api
            .deleteMember(this.structure.id, member.id)
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
