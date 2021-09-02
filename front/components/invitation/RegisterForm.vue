<template>
  <div class="">
    <el-form
      ref="registerInvitationForm"
      :model="form"
      label-position="top"
      :rules="rules"
      :hide-required-asterisk="true"
      class="form-register-steps mt-4"
    >
      <div class="flex flex-wrap -m-2">
        <el-form-item
          label="Prénom"
          prop="first_name"
          class="w-full sm:w-1/2 p-2"
        >
          <el-input v-model="form.first_name" placeholder="Prénom" />
        </el-form-item>
        <el-form-item label="Nom" prop="last_name" class="w-full sm:w-1/2 p-2">
          <el-input v-model="form.last_name" placeholder="Nom" />
        </el-form-item>
        <el-form-item label="E-mail" prop="email" class="w-full p-2">
          <el-input v-model.trim="form.email" placeholder="E-mail" disabled />
        </el-form-item>
        <el-form-item label="Mot de passe" prop="password" class="w-full p-2">
          <el-input
            v-model="form.password"
            placeholder="Choisissez votre mot de passe"
            show-password
          />
        </el-form-item>
        <el-form-item
          label="Confirmation du mot de passe"
          prop="password_confirmation"
          class="w-full p-2"
        >
          <el-input
            v-model="form.password_confirmation"
            placeholder="Confirmez votre mot de passe"
            show-password
          />
        </el-form-item>
      </div>
    </el-form>
    <div class="mt-4">
      <button
        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-lg font-bold text-white bg-[#070191] hover:shadow-lg hover:scale-105 transform transition"
        @click="onSubmit"
      >
        J'accepte l'invitation
      </button>
    </div>
  </div>
</template>

<script>
import FormMixin from '@/mixins/Form'

export default {
  name: 'InvitationRegisterForm',
  mixins: [FormMixin],
  props: {
    invitation: {
      type: Object,
      required: true,
    },
  },
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
        email: [
          {
            type: 'email',
            message: "Le format de l'email n'est pas correct",
            trigger: 'blur',
          },
          {
            required: true,
            message: 'Veuillez renseigner votre email',
            trigger: 'blur',
          },
        ],
        first_name: [
          {
            required: true,
            message: 'Prénom obligatoire',
            trigger: 'blur',
          },
        ],
        last_name: [
          {
            required: true,
            message: 'Nom obligatoire',
            trigger: 'blur',
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
            message: 'Votre mot de passe doit contenir au moins 8 caractères',
            trigger: 'blur',
          },
        ],
        password_confirmation: [{ validator: validatePass2, trigger: 'blur' }],
      },
    }
  },
  created() {
    this.form.email = this.invitation.email
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs.registerInvitationForm.validate((valid, fields) => {
        if (valid) {
          this.$store
            .dispatch('auth/registerInvitation', {
              form: this.form,
              invitation: this.invitation,
            })
            .then(() => {
              this.loading = false
              window.plausible &&
                window.plausible('Inscription depuis une invitation')
              if (this.invitation.role == 'benevole') {
                this.$router.push('/register/volontaire/step/profile')
              } else {
                this.$router.push('/register/invitation/step/profile')
              }
            })
            .catch(() => {
              this.loading = false
            })
        } else {
          this.showErrors(fields)
          this.loading = false
        }
      })
    },
  },
}
</script>

<style lang="postcss" scoped>
::v-deep .el-form-item {
  @apply mb-3;
}
</style>
