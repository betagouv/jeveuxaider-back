<template>
  <div class="">
    <HeaderReseau :reseau="reseau">
      <template #action>
        <nuxt-link :to="`/dashboard/reseaux/${reseau.id}/responsables/add`">
          <el-button type="primary"> Inviter un responsable </el-button>
        </nuxt-link>
      </template>
    </HeaderReseau>
    <NavTabReseau :reseau="reseau" />

    <div v-if="responsables.length" class="px-12 mb-12">
      <div class="text-xl font-medium text-gray-700 mb-4">
        Responsables ({{ responsables.length }})
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div
          v-for="responsable in responsables"
          :key="responsable.id"
          class="py-4 px-6 bg-gray-50 rounded"
        >
          <div class="flex items-center">
            <Avatar
              :source="responsable.image ? responsable.image.thumb : null"
              :fallback="responsable.short_name"
            />
            <router-link
              :to="`/dashboard/profile/${responsable.id}`"
              target="_blank"
              class="flex flex-1 flex-col ml-6"
            >
              <div class="text-[#242526]">
                {{ responsable.first_name }} {{ responsable.last_name }}
              </div>
              <div class="text-xs text-secondary">
                <div class="break-all">{{ responsable.email }}</div>
                <div class="">{{ responsable.mobile }}</div>
              </div>
            </router-link>
            <el-button
              v-if="
                $store.getters.user.profile.id != responsable.id ||
                $store.getters.contextRole == 'admin'
              "
              type="danger"
              icon="el-icon-delete"
              size="small"
              class="!ml-4 !m-auto is-plain"
              @click="deleteConfirm(responsable)"
            >
              Retirer
            </el-button>
          </div>
        </div>
      </div>
    </div>
    <div v-if="invitations.length" class="px-12">
      <div class="text-xl font-medium text-gray-700 mb-4">
        Invitations en attente ({{ invitations.length }})
      </div>
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
  async asyncData({ $api, store, error, params }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
    const reseau = await $api.getReseau(params.id)
    return {
      reseau,
    }
  },
  data() {
    return {
      responsables: [],
      invitations: [],
    }
  },
  async fetch() {
    const responsables = await this.$api.getReseauResponsables(
      this.$route.params.id
    )
    this.responsables = responsables.data
    const invitations = await this.$api.getReseauInvitationsResponsables(
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
            .deleteReseauResponsable(this.reseau.id, responsable.id)
            .then((response) => {
              this.loading = false
              this.$message({
                type: 'success',
                message: 'Le responsable a bien été supprimé',
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
