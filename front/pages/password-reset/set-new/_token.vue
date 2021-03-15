<template>
  <div class="relative bg-blue-800 overflow-hidden">
    <img
      class="z-1 object-cover absolute h-screen lg:h-auto"
      alt="Je Veux Aider"
      :srcSet="bgHeroMultipleSizes.srcSet"
      :src="bgHeroMultipleSizes.src"
    />

    <div class="pb-12 mt-12 px-4 relative w-full lg:inset-y-0 text-center z-10">
      <div class="">
        <h2
          class="mt-6 mb-4 md:mb-0 text-center text-3xl font-bold text-white leading-8 px-4"
        >
          Réinitialisation de votre mot de passe
        </h2>
        <p class="text-center text-base text-blue-200">
          Renseignez votre nouveau mot de passe afin de vous connecter à la
          plateforme.
        </p>
      </div>
      <div class="mt-12 sm:mx-auto sm:w-full sm:max-w-md text-left">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
          <div class="relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-xl">
              <span class="px-2 bg-white font-bold text-gray-900">
                Nouveau mot de passe
              </span>
            </div>
          </div>
          <template v-if="!submitted">
            <el-form
              ref="resetPasswordForm"
              class="mt-4"
              :model="form"
              label-position="top"
              :rules="rules"
              :hide-required-asterisk="true"
            >
              <el-form-item label="Nouveau mot de passe" prop="password">
                <el-input
                  v-model="form.password"
                  placeholder="Choisissez votre nouveau mot de passe"
                  show-password
                />
              </el-form-item>
              <el-form-item
                label="Confirmation du nouveau mot de passe"
                prop="password_confirmation"
              >
                <el-input
                  v-model="form.password_confirmation"
                  placeholder="Confirmez votre nouveau mot de passe"
                  show-password
                />
              </el-form-item>
            </el-form>
            <div class="mt-8 sm:col-span-">
              <span class="block w-full rounded-md shadow-sm">
                <el-button
                  type="primary"
                  :loading="loading"
                  class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-lg font-bold text-white bg-blue-800 hover:shadow-lg hover:scale-105 transform transition duration-150 ease-in-out"
                  @click="onSubmit"
                  >Modifier mon mot de passe</el-button
                >
              </span>
            </div>
          </template>
          <template v-else>
            <div class="mt-4">
              Votre mot de passe a été mis à jour.
              <br />
              <br />Vous pouvez désormais vous connecter avec vos nouveaux
              identifiants.
            </div>
            <div class="mt-6 sm:col-span-">
              <nuxt-link :to="`/login?email=${$route.query.email}`">
                <span class="block w-full rounded-md shadow-sm">
                  <button
                    type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-lg font-bold text-white bg-blue-800 hover:shadow-lg hover:scale-105 transform transition duration-150 ease-in-out"
                  >
                    Se connecter
                  </button>
                </span>
              </nuxt-link>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const bgHeroMultipleSizes = require('@/assets/images/bg-jva.jpg?resize&sizes[]=320&sizes[]=640&sizes[]=960&sizes[]=1200&sizes[]=1800&sizes[]=2400&sizes[]=3900')

export default {
  middleware: 'guest',
  data() {
    return {
      bgHeroMultipleSizes,
      loading: false,
      submitted: false,
      form: {
        email: '',
        password: '',
        password_confirmation: '',
        token: '',
      },
      rules: {
        password: [
          {
            required: true,
            message: 'Veuillez renseigner un mot de passe',
            trigger: 'blur',
          },
          {
            min: 8,
            message: 'Votre mot de passe doit contenir au moins 8 caractères',
            trigger: 'blur',
          },
        ],
        password_confirmation: [
          {
            required: true,
            message: 'Veuillez confirmer votre mot de passe',
            trigger: 'blur',
          },
          {
            min: 8,
            message: 'Votre mot de passe doit contenir au moins 8 caractères',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  created() {
    this.form.token = this.$route.params.token
    this.form.email = this.$route.query.email
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs.resetPasswordForm.validate((valid) => {
        if (valid) {
          this.loading = true
          this.$api
            .resetPassword(this.form)
            .then(() => {
              this.loading = false
              this.submitted = true
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
