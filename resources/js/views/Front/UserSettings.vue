<template>
  <div>
    <div class="font-bold text-2xl text-gray-800 mb-4">
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
          v-if="$store.getters.isVolunteerOnly === true"
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
import { updatePassword, anonymizeUser } from '@/api/user'
import UserMenu from '@/components/UserMenu'
export default {
  name: 'FrontUserSettings',
  components: { UserMenu },
  data() {
    var validatePass2 = (rule, value, callback) => {
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
      this.$refs['settingsForm'].validate((valid) => {
        if (valid) {
          updatePassword(this.form)
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
        anonymizeUser().then(() => {
          this.$store.dispatch('auth/logout').then(() => {
            this.$router.push('/')
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
