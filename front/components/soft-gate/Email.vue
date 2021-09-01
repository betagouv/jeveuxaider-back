<template>
  <div class="mx-auto max-w-sm">
    <!-- <div class="">
      <FranceConnect is-dark class="text-center" />
    </div>
    <div class="relative my-7">
      <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t border-gray-300"></div>
      </div>
      <div class="relative flex justify-center">
        <span class="px-2 bg-gray-100 text-gray-400">
          Ou renseignez votre e-mail
        </span>
      </div>
    </div> -->
    <div class="text-center mb-6">
      <div
        class="text-gray-900 font-extrabold text-2xl lg:text-3xl leading-8 mb-2 lg:mb-3"
      >
        Avant toute chose
      </div>
      <div class="text-gray-500 font-semibold text-lg lg:text-xl">
        Renseignez votre e-mail
      </div>
    </div>
    <div class="mx-auto max-w-sm">
      <el-form
        ref="emailForm"
        :model="form"
        :rules="rules"
        label-position="top"
        class="mb-0 form-register-steps"
        :hide-required-asterisk="true"
        @submit.prevent.native="onSubmit"
      >
        <el-form-item label="Email" prop="email" class="mb-5">
          <el-input
            v-model.trim="form.email"
            placeholder="prenom.nom@email.fr"
          />
        </el-form-item>

        <el-button
          :loading="loading"
          class="font-bold max-w-sm mx-auto w-full flex items-center justify-center px-5 py-3 border border-transparent text-xl leading-6 rounded-full text-white bg-green-400 hover:bg-green-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out mt-8"
          @click.prevent="onSubmit"
        >
          Continuer
        </el-button>
      </el-form>
    </div>
  </div>
</template>

<script>
// import FranceConnect from '@/components/FranceConnect.vue'
import FormMixin from '@/mixins/Form'

export default {
  name: 'SoftGateEmail',
  mixins: [FormMixin],
  // components: { FranceConnect },
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
      },
    }
  },
  methods: {
    onSubmit() {
      this.$refs.emailForm.validate((valid, fields) => {
        if (valid) {
          this.loading = true
          this.$api
            .getUserFirstname(this.form.email)
            .then((res) => {
              this.loading = false
              if (!res.data) {
                this.$axios.post('/sendinblue/contact', {
                  email: this.form.email,
                  id_liste: 383,
                  url_mission: window.location.href,
                })
                this.$emit('register', { email: this.form.email })
              } else {
                this.$emit('login', res.data)
              }

              window.plausible &&
                window.plausible('Soft Gate - Étape 1 - Email')
            })
            .catch(() => {
              this.loading = false
            })
        } else {
          this.showErrors(fields)
        }
      })
    },
  },
}
</script>

<style lang="sass" scoped>
::placeholder
  font-weight: 500
</style>
