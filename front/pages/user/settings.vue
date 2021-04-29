<template>
  <div>
    <div class="font-bold text-2-5xl text-gray-800 mb-4">
      Paramètres de compte
    </div>

    <div class="mb-8 text-md text-gray-600">
      Pour changer votre mot de passe, remplissez les champs ci-dessous
    </div>

    <el-form
      ref="settingsForm"
      :model="form"
      label-position="top"
      :rules="rules"
      :hide-required-asterisk="true"
    >
      <el-form-item
        label="Ancien mot de passe"
        prop="current_password"
        class="mb-6"
      >
        <el-input
          v-model="form.current_password"
          placeholder="Ancien mot de passe"
          show-password
        />
      </el-form-item>
      <el-form-item label="Nouveau mot de passe" prop="password" class="mb-6">
        <el-input
          v-model="form.password"
          placeholder="Nouveau mot de passe"
          show-password
        />
      </el-form-item>
      <el-form-item
        label="Confirmation du nouveau mot de passe"
        prop="password_confirmation"
        class="mb-6"
      >
        <el-input
          v-model="form.password_confirmation"
          placeholder="Confirmez votre nouveau mot de passe"
          show-password
        />
      </el-form-item>
      <div class="mt-8 flex items-center">
        <el-button type="primary" :loading="loading" class="" @click="onSubmit">
          Enregistrer les modifications
        </el-button>
        <div
          class="text-red-500 ml-4 cursor-pointer hover:underline"
          @click="onSubmitDelete"
        >
          Supprimer mon compte
        </div>
      </div>
    </el-form>
  </div>
</template>

<script>
export default {
  layout: 'profile',
  data() {
    const validatePass2 = (rule, value, callback) => {
      if (value !== this.form.password) {
        callback(new Error('Les mots de passe ne sont pas identiques'))
      } else {
        callback()
      }
    }
    return {
      loading: false,
      form: {},
      rules: {
        current_password: [
          {
            required: true,
            message: 'Ce champ est requis',
            trigger: 'change',
          },
        ],
        password: [
          {
            required: true,
            message: 'Choisissez votre mot de passe',
            trigger: 'change',
          },
          {
            min: 8,
            message: 'Votre mot de passe doit contenir au moins 8 charactères',
            trigger: 'blur',
          },
        ],
        password_confirmation: [{ validator: validatePass2, trigger: 'blur' }],
      },
    }
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs.settingsForm.validate((valid) => {
        if (valid) {
          this.$api
            .updatePassword(this.form)
            .then(() => {
              this.loading = false
              this.$message({
                message: 'Votre mot de passe a été modifié',
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
    onSubmitDelete() {
      this.$confirm(
        'Attention, vos données seront supprimées et vous serez immédiatement déconnecté. Souhaitez-vous réellement supprimer votre compte ?',
        'Supprimer mon compte',
        {
          confirmButtonText: 'Je confirme',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          center: true,
          type: 'error',
        }
      ).then(() => {
        this.$api.anonymizeUser().then(() => {
          this.$router.push('/')
          this.$store.dispatch('auth/logout').then(() => {
            this.$message({
              type: 'success',
              message: `Votre compte a bien été supprimé.`,
            })
          })
        })
      })
    },
  },
}
</script>

<style lang="sass" scoped>
::v-deep .el-form-item
  @apply mb-1
</style>
