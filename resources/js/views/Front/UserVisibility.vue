<template>
  <div class="">
    <div class="font-bold text-2xl text-gray-800 mb-4">
      Visibilité de mon profil
    </div>
    <div class="mb-8 text-md text-gray-600">
      En rendant votre profil visible, vous acceptez de recevoir des
      propositions d'organisations publiques ou associatives à la recherche de
      réservistes.
    </div>

    <el-form
      ref="profileForm"
      :model="form"
      label-position="top"
      :rules="rules"
      :hide-required-asterisk="true"
    >
      <el-form-item
        :label="
          form.is_visible
            ? 'Votre profil est visible'
            : 'Votre profil n\'est pas visible'
        "
        prop="is_visible"
        class="mb-6"
      >
        <el-switch
          v-model="form.is_visible"
          active-color="#1E429F"
          inactive-color="#959595"
        ></el-switch>
      </el-form-item>

      <div class="mt-8">
        <el-button type="primary" :loading="loading" class="" @click="onSubmit">
          Enregistrer les modifications
        </el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import UserMenu from '@/components/UserMenu'

export default {
  name: 'FrontUserVisibility',
  components: { UserMenu },
  data() {
    return {
      loading: false,
      form: this.$store.getters.user.profile,
      skills: null,
      domaines: null,
      rules: {},
    }
  },
  created() {},
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs['profileForm'].validate((valid) => {
        if (valid) {
          this.$store
            .dispatch('user/updateProfile', this.form)
            .then(() => {
              this.loading = false
              this.$message({
                message: 'Votre profil a été mis à jour',
                type: 'success',
              })
            })
            .catch(() => {
              this.loading = false
            })
          this.loading = false
        } else {
          this.loading = false
        }
      })
    },
  },
}
</script>

<style lang="sass" scoped>
::v-deep .el-form-item
    @apply mb-3
</style>
