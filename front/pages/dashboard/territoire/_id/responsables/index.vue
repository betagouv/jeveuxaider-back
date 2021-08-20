<template>
  <div class="">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ territoire.name }}
        </div>
        <div class="mb-12 font-bold text-[1.75rem] text-[#242526]">
          Gérer les responsables
        </div>
      </div>
      <nuxt-link
        :to="`/dashboard/territoire/${territoire.id}/responsables/add`"
      >
        <el-button type="primary"> Inviter un responsable </el-button>
      </nuxt-link>
    </div>
    <el-divider />
    <div class="px-12 mb-12">
      <div class="text-sm font-medium text-secondary mb-4">Responsables</div>
      <div
        v-for="responsable in responsables"
        :key="responsable.id"
        class="responsable py-4 px-6"
      >
        <div class="flex items-center">
          <Avatar
            :source="responsable.image ? responsable.image.thumb : null"
            :fallback="responsable.short_name"
          />
          <div class="flex flex-col ml-6" style="min-width: 350px">
            <div class="text-[#242526]">
              {{ responsable.first_name }} {{ responsable.last_name }}
            </div>
            <div class="text-xs text-secondary">
              <div class="break-all">{{ responsable.email }}</div>
              <div class="">{{ responsable.mobile }}</div>
            </div>
          </div>
          <el-button
            v-if="
              responsables.length > 1 &&
              $store.getters.user.profile.id != responsable.id
            "
            type="danger"
            icon="el-icon-delete"
            size="small"
            class="!ml-4 !m-auto is-plain"
            @click="deleteConfirm(responsable)"
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
    const territoire = await $api.getTerritoire(params.id)
    return {
      territoire,
    }
  },
  data() {
    return {
      responsables: [],
      invitations: [],
    }
  },
  async fetch() {
    const responsables = await this.$api.getTerritoireResponsables(
      this.$route.params.id
    )
    this.responsables = responsables.data
    const invitations = await this.$api.getTerritoireInvitations(
      this.$route.params.id
    )
    this.invitations = invitations.data
  },
  methods: {
    deleteConfirm(responsable) {
      this.$confirm(
        'Êtes vous sur de vouloir supprimer ce responsable ?<br><br>Ce responsable ne pourra plus accéder à ce territoire.<br>',
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
            .deleteResponsable(this.responsable.id, responsable.id)
            .then((response) => {
              this.loading = false
              this.$message({
                type: 'success',
                message: 'Le membre a bien été supprimé',
              })
              this.responsables = response.data
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
