<template>
  <div class="relative bg-[#070191] overflow-hidden">
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
        <p class="text-center text-base text-[#c3ddfd]">
          Pour réinitialiser votre mot de passe, entrez l'adresse mail que vous
          avez utilisée pour vous connecter à la plateforme.
        </p>
      </div>
      <div class="mt-12 sm:mx-auto sm:w-full sm:max-w-md text-left">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
          <div class="relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-[#d2d6dc]"></div>
            </div>
            <div class="relative flex justify-center text-xl">
              <span class="px-2 bg-white font-bold text-gray-900">
                J'ai oublié mon mot de passe
              </span>
            </div>
          </div>
          <template v-if="!submitted">
            <el-form
              ref="forgotPasswordForm"
              :model="form"
              class="mt-4"
              label-position="top"
              :rules="rules"
              :hide-required-asterisk="true"
              @submit.native.prevent
            >
              <el-form-item label="E-mail" prop="email">
                <el-input
                  v-model.trim="form.email"
                  placeholder="Saisissez votre email"
                  @keyup.enter.native="onSubmit"
                />
              </el-form-item>
              <div class="mt-8 sm:col-span-">
                <span class="block w-full rounded-md shadow-sm">
                  <el-button
                    type="submit"
                    :loading="loading"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-lg font-bold text-white bg-[#070191] hover:shadow-lg hover:scale-105 transform transition duration-150 ease-in-out"
                    @click.native="onSubmit"
                    >Réinitialiser mon mot de passe</el-button
                  >
                </span>
              </div>
            </el-form>
            <p class="mt-4">
              Vous allez recevoir un email qui vous permettra de créer un
              nouveau mot de passe.
            </p>
          </template>
          <template v-else>
            <p class="mt-4">
              Une email contenant les instructions pour réinitialiser votre mot
              de passe vient de vous être envoyé.
              <br />
              <br />Si vous ne vous souvenez plus de votre email de connexion,
              écrivez-nous à contact@reserve-civique.beta.gouv.fr.
            </p>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FormMixin from '@/mixins/Form'

const bgHeroMultipleSizes = require('@/assets/images/bg-jva.jpg?resize&sizes[]=320&sizes[]=640&sizes[]=960&sizes[]=1200&sizes[]=1800&sizes[]=2400&sizes[]=3900')

export default {
  name: 'PasswordForgot',
  mixins: [FormMixin],
  data() {
    return {
      bgHeroMultipleSizes,
      loading: false,
      submitted: false,
      form: {
        email: this.$route.query.email ? this.$route.query.email : '',
      },
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
      this.$refs.forgotPasswordForm.validate((valid, fields) => {
        if (valid) {
          this.loading = true
          this.$api
            .forgotPassword(this.form.email)
            .then(() => {
              this.loading = true
              this.submitted = true
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
