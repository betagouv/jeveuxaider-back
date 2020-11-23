<template>
  <div class="mx-auto w-full" style="max-width: 390px">
    <div>
      <router-link to="/">
        <img
          class="h-8 w-auto"
          src="/images/logo-header-dark-small.png"
          alt="Réserve Civique"
        />
      </router-link>
      <h2
        v-if="!submitted"
        class="mt-8 text-3xl leading-tight font-extrabold text-gray-900"
      >
        Réinitialisation du
        <span class="text-blue-800">mot de passe</span>
      </h2>
      <h2
        v-else
        class="mt-8 text-3xl leading-tight font-extrabold text-gray-900"
      >
        Email
        <span class="text-blue-800">envoyé</span>
      </h2>
    </div>
    <div class="mt-8 border-t border-gray-200 pt-8" />
    <div v-if="!submitted">
      <div class="mb-6 text-sm">
        Pour réinitialiser votre mot de passe, entrez l'adresse mail que vous
        avez utilisée pour vous connecter à la plateforme.
      </div>
      <el-form
        ref="forgotPasswordForm"
        :model="form"
        label-position="top"
        :rules="rules"
        :hide-required-asterisk="true"
      >
        <el-form-item prop="email">
          <el-input
            v-model.trim="form.email"
            placeholder="Saisissez votre email"
          />
        </el-form-item>
      </el-form>
      <div class="mt-8 sm:col-span-">
        <span class="block w-full rounded-md shadow-sm">
          <el-button
            type="primary"
            :loading="loading"
            style="height: 48px"
            class="w-full flex justify-center items-center py-2 px-4 border border-transparent text-xl font-medium rounded-md text-white bg-blue-800 hover:bg-primary focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out"
            @click="onSubmit"
            >Réinitialiser mon mot de passe</el-button
          >
        </span>
      </div>
      <div class="mt-6">
        <div class="border-gray-300 pb-4 text-sm">
          Vous allez recevoir un email qui vous permettra de créer un nouveau
          mot de passe.
        </div>
      </div>
    </div>
    <div v-else>
      <div class="mb-6 text-sm">
        Une email contenant les instructions pour réinitialiser votre mot de
        passe vient de vous être envoyé.
        <br />
        <br />Si vous ne vous souvenez plus de votre email de connexion,
        écrivez-nous à contact@reserve-civique.beta.gouv.fr.
      </div>
    </div>
  </div>
</template>

<script>
import { forgotPassword } from '@/api/auth'

export default {
  name: 'PasswordForgot',
  data() {
    return {
      loading: false,
      submitted: false,
      form: {
        email: '',
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
      this.$refs['forgotPasswordForm'].validate((valid) => {
        if (valid) {
          this.loading = true
          forgotPassword(this.form.email)
            .then(() => {
              this.loading = true
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
