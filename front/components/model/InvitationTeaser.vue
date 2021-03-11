<template>
  <div class="flex justify-between">
    <div class="flex items-center">
      <el-avatar
        class="bg-primary text-white flex items-center justify-center"
        icon="el-icon-s-promotion"
      >
      </el-avatar>
      <div class="flex flex-col ml-6">
        <div class="text-gray-900 flex items-center">
          {{ invitation.email }}
        </div>
        <div class="font-light text-gray-600 text-xs">
          Envoyée {{ invitation.last_sent_at | fromNow }}
        </div>
      </div>
    </div>
    <TailwindDropdownEllipsis>
      <template slot="menu">
        <div
          class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
          role="menuitem"
          @click="onCopyInvitationLink"
        >
          Copier le lien d'invitation
        </div>
        <div
          class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
          role="menuitem"
          @click="onResendInvitationLink"
        >
          Renvoyer le mail
        </div>
        <div
          class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
          role="menuitem"
          @click="onDeleteInvitation"
        >
          Supprimer l'invitation
        </div>
      </template>
    </TailwindDropdownEllipsis>
  </div>
</template>

<script>
export default {
  props: {
    invitation: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {}
  },
  methods: {
    onCopyInvitationLink() {
      this.$copyText(
        this.$config.appUrl + '/invitation/' + this.invitation.token
      ).then(() => {
        this.$message.success({
          message:
            "Le lien d'invitation a été copié dans votre presse papier (CTRL+V)",
        })
      })
    },
    onResendInvitationLink() {
      this.$api.resendInvitation(this.invitation.token).then(() => {
        this.$emit('updated')
        this.$message.success({
          message: `Un email a été renvoyé à ${this.invitation.email}`,
        })
      })
    },
    onDeleteInvitation() {
      this.$confirm(
        `L'invitation pour ${this.invitation.email} sera supprimée de la plateforme. Voulez-vous continuer ?`,
        "Supprimer l'invitation",
        {
          confirmButtonText: 'Supprimer',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          center: true,
          type: 'error',
        }
      ).then(() => {
        this.$api.deleteInvitation(this.invitation.token).then(() => {
          this.$emit('updated')
          this.$message.success({
            message: `L'invitation pour ${this.invitation.email} a été supprimée.`,
          })
        })
      })
    },
  },
}
</script>
