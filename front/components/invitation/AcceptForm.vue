<template>
  <div class="">
    <div class="mt-4">
      <button
        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-lg font-bold text-white bg-[#070191] hover:shadow-lg hover:scale-105 transform transition duration-150 ease-in-out"
        @click="handleAcceptInvitation"
      >
        J'accepte l'invitation
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'InvitationAcceptForm',
  props: {
    invitation: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
    }
  },
  created() {},
  methods: {
    handleAcceptInvitation() {
      if (this.$store.getters.user.email == this.invitation.email) {
        this.$api.acceptInvitation(this.invitation.token).then(() => {
          this.$store.dispatch('auth/fetchUser').then(() => {
            if (this.invitation.role == 'benevole') {
              this.$router.push('/')
            } else {
              this.$router.push('/dashboard')
            }
          })
        })
      } else {
        this.$message({
          message: 'Cette invitation ne vous est pas destinée !',
          type: 'error',
        })
      }
    },
  },
}
</script>

<style lang="postcss" scoped>
::v-deep .el-form-item {
  @apply mb-3;
}
</style>
