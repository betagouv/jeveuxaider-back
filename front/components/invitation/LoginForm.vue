<template>
  <div class="">
    <el-form
      ref="loginInvitationForm"
      :model="form"
      label-position="top"
      :rules="rules"
      :hide-required-asterisk="true"
      class="form-register-steps mt-4"
    >
      <el-form-item label="E-mail" prop="email">
        <el-input v-model.trim="form.email" placeholder="Email" disabled />
      </el-form-item>
      <el-form-item label="Mot de passe" prop="password" class="mb-0">
        <el-input
          v-model="form.password"
          placeholder="Entrez votre mot de passe"
          show-password
          @keyup.native.enter="onSubmit"
        />
      </el-form-item>
      <div class="pt-4">
        <nuxt-link to="/password/forgot">
          <div class="text-sm leading-5">
            <nuxt-link
              to="/password/forgot"
              class="font-medium text-blue-800 hover:text-blue-900 focus:outline-none focus:underline transition ease-in-out duration-150"
            >
              Mot de passe perdu ?
            </nuxt-link>
          </div>
        </nuxt-link>
      </div>
    </el-form>
    <div class="mt-4">
      <button
        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-lg font-bold text-white bg-blue-800 hover:shadow-lg hover:scale-105 transform transition duration-150 ease-in-out"
        @click="onSubmit"
      >
        J'accepte l'invitation
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'InvitationLoginForm',
  props: {
    invitation: {
      type: Object,
      required: true,
    },
  },
  data() {
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
        password: [
          {
            required: true,
            message: 'Saisissez votre mot de passe',
            trigger: 'change',
          },
        ],
      },
    }
  },
  created() {
    this.form.email = this.invitation.email
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs.loginInvitationForm.validate((valid) => {
        if (valid) {
          this.$emit('on-processing', true)
          this.$store
            .dispatch('auth/login', {
              email: this.form.email,
              password: this.form.password,
            })
            .then(() => {
              this.$api.acceptInvitation(this.invitation.token).then(() => {
                this.$store.dispatch('auth/fetchUser').then(() => {
                  if (this.invitation.role == 'benevole') {
                    this.$router.push('/')
                  } else {
                    this.$router.push('/dashboard')
                  }
                })
              })
            })
            .catch(() => {
              this.loading = false
            })
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
