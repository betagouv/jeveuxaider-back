<template>
  <div class="mx-auto w-full" style="max-width: 390px;">
    <div>
      <router-link to="/">
        <img
          class="h-8 w-auto"
          src="/images/logo-header-dark.png"
          alt="Réserve Civique"
        />
      </router-link>
      <h2
        v-if="!submitted"
        class="mt-8 text-3xl leading-tight font-extrabold text-gray-900"
      >
        Modification de mon
        <span class="text-blue-800">mot de passe</span>
      </h2>
      <h2
        v-else
        class="mt-8 text-3xl leading-tight font-extrabold text-gray-900"
      >
        Mot de passe
        <span class="text-blue-800">modifié !</span>
      </h2>
    </div>
    <div class="mt-8 border-t border-gray-200 pt-8" />
    <div v-if="!submitted">
      <el-form
        ref="resetPasswordForm"
        :model="form"
        label-position="top"
        :rules="rules"
        :hide-required-asterisk="true"
      >
        <el-form-item label="Nouveau mot de passe" prop="password">
          <el-input
            v-model="form.password"
            placeholder="Définir un mot de passe"
            show-password
          />
        </el-form-item>
        <el-form-item
          label="Confirmez votre nouveau mot de passe"
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
            style="height: 48px;"
            class="w-full flex justify-center py-2 px-4 border border-transparent text-xl font-medium rounded-md text-white bg-blue-800 hover:bg-blue-900 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out"
            @click="onSubmit"
            >Modifier mon mot de passe</el-button
          >
        </span>
      </div>
    </div>
    <div v-else>
      <div class="mb-6 text-sm">
        Votre mot de passe a été mis à jour.
        <br />
        <br />Vous pouvez désormais vous connecter avec vos nouveaux
        identifiants.
      </div>
      <div class="mt-6 sm:col-span-">
        <router-link to="/login">
          <span class="block w-full rounded-md shadow-sm">
            <button
              type="submit"
              class="w-full flex justify-center py-2 px-4 border border-transparent text-s font-medium rounded-md border border-gray-300 rounded-md bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition duration-150 ease-in-out"
            >
              Me connecter
            </button>
          </span>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { resetPassword } from '@/api/auth'

export default {
  name: 'PasswordReset',
  data() {
    return {
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
      this.$refs['resetPasswordForm'].validate((valid) => {
        if (valid) {
          this.loading = true
          resetPassword(this.form)
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
