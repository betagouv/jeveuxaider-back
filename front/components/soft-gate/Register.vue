<template>
  <div>
    <div class="text-center mb-6">
      <div
        class="text-gray-900 font-extrabold text-2xl lg:text-3xl leading-8 mb-2 lg:mb-3"
      >
        Rejoignez le mouvement !
      </div>
      <div class="text-gray-500 font-semibold text-lg lg:text-xl">
        Créez rapidement votre compte Bénévole
      </div>
    </div>
    <div class="mx-auto max-w-sm">
      <el-form
        ref="registerForm"
        :model="form"
        :rules="rules"
        class="mb-0 form-center"
        @submit.prevent.native="onSubmit"
      >
        <el-form-item prop="email" class="mb-5">
          <div
            class="input-shadow relative text-center bg-white px-5 py-1 w-full rounded-full text-gray-900 placeholder-gray-400 focus:outline-none focus:shadow-outline"
          >
            {{ form.email }}

            <img
              class="absolute inset-y-0 my-auto"
              style="right: 15px"
              src="@/assets/images/email-check.svg"
              alt="Email valide"
            />
          </div>
        </el-form-item>

        <el-form-item prop="password" class="mb-5">
          <input
            v-model="form.password"
            type="password"
            class="input-shadow text-center bg-white px-5 py-1 w-full rounded-full text-gray-900 placeholder-gray-400 focus:outline-none focus:shadow-outline"
            placeholder="Votre mot de passe"
          />
        </el-form-item>

        <div class="lg:flex lg:space-x-4">
          <el-form-item prop="first_name" class="w-full lg:w-1/2 mb-5">
            <input
              v-model="form.first_name"
              class="input-shadow text-center bg-white px-5 py-1 w-full rounded-full text-gray-900 placeholder-gray-400 focus:outline-none focus:shadow-outline"
              label="Prénom"
              placeholder="Prénom"
            />
          </el-form-item>
          <el-form-item prop="last_name" class="w-full lg:w-1/2 mb-5">
            <input
              v-model="form.last_name"
              class="input-shadow text-center bg-white px-5 py-1 w-full rounded-full text-gray-900 placeholder-gray-400 focus:outline-none focus:shadow-outline"
              label="Nom"
              placeholder="Nom"
            />
          </el-form-item>
        </div>
        <div class="lg:flex lg:space-x-4">
          <el-form-item prop="zip" class="w-full lg:w-1/2 mb-5">
            <input
              v-model="form.zip"
              class="input-shadow text-center bg-white px-5 py-1 w-full rounded-full text-gray-900 placeholder-gray-400 focus:outline-none focus:shadow-outline"
              label="Code postal"
              placeholder="Code Postal"
            />
          </el-form-item>
          <el-form-item prop="birthday" class="w-full lg:w-1/2 mb-5">
            <input
              v-model="form.birthday"
              v-mask="'##/##/####'"
              class="input-shadow text-center bg-white px-5 py-1 w-full rounded-full text-gray-900 placeholder-gray-400 focus:outline-none focus:shadow-outline"
              label="Date de naissance"
              placeholder="Date de naissance"
            />
          </el-form-item>
        </div>
        <el-form-item prop="mobile" class="mb-5">
          <input
            v-model="form.mobile"
            class="input-shadow text-center bg-white px-5 py-1 w-full rounded-full text-gray-900 placeholder-gray-400 focus:outline-none focus:shadow-outline"
            label="Mobile"
            placeholder="Téléphone mobile"
          />
        </el-form-item>

        <el-button
          :loading="loading"
          class="font-bold max-w-sm mx-auto w-full flex items-center justify-center px-5 py-3 border border-transparent text-xl leading-6 rounded-full text-white bg-green-400 hover:bg-green-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
          @click.prevent="onSubmit"
        >
          S'inscrire
        </el-button>
      </el-form>

      <div class="mt-6 mb-3">
        <p class="text-xs leading-5 text-gray-500 text-center">
          <span>En m'inscrivant j'accepte la</span>
          <nuxt-link
            to="/politique-de-confidentialite"
            target="_blank"
            class="font-medium text-gray-900 hover:underline"
          >
            politique de confidentialité
          </nuxt-link>
          <br />
          <span>et la</span>
          <nuxt-link
            to="/charte-reserve-civique"
            target="_blank"
            class="font-medium text-gray-900 hover:underline"
          >
            charte de JeVeuxAider.gouv.fr
          </nuxt-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import dayjs from 'dayjs'
const customParseFormat = require('dayjs/plugin/customParseFormat')
dayjs.extend(customParseFormat)

export default {
  name: 'SoftGateRegister',
  props: {
    datas: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      form: { ...this.datas },
      rules: {
        email: [
          {
            type: 'email',
            message: "Le format de l'email n'est pas correct",
            trigger: 'blur',
          },
          {
            required: true,
            message: 'Champ obligatoire',
            trigger: 'blur',
          },
        ],
        first_name: [
          {
            required: true,
            message: 'Champ obligatoire',
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
        birthday: [
          {
            required: true,
            message: 'Champ obligatoire',
            trigger: 'blur',
          },
        ],
        zip: [
          {
            required: true,
            message: 'Champ obligatoire',
            trigger: 'blur',
          },
          {
            pattern: /^\d+$/,
            message: 'Format incorrect',
            trigger: 'blur',
          },
          {
            min: 5,
            max: 6,
            message: 'Format erroné',
            trigger: 'blur',
          },
        ],
        mobile: [
          {
            required: true,
            message: 'Champ obligatoire',
            trigger: 'blur',
          },
          {
            pattern: /^[+|\s|\d]*$/,
            message: 'Format incorrect',
            trigger: 'blur',
          },
          {
            min: 10,
            message: 'Format incorrect',
            trigger: 'blur',
          },
        ],
        password: [
          {
            required: true,
            message: 'Champ obligatoire',
            trigger: 'change',
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
  created() {},
  methods: {
    onSubmit() {
      this.$refs.registerForm.validate((valid) => {
        if (valid) {
          this.loading = true
          const birthdayValidFormat = dayjs(
            this.form.birthday,
            'DD/MM/YYYY'
          ).format('YYYY-MM-DD')
          this.$store
            .dispatch('auth/registerVolontaire', {
              email: this.form.email,
              password: this.form.password,
              first_name: this.form.first_name,
              last_name: this.form.last_name,
              mobile: this.form.mobile,
              birthday: birthdayValidFormat,
              zip: this.form.zip,
              service_civique: this.form.service_civique,
            })
            .then(() => {
              window.plausible('Inscription depuis une page mission')
              this.loading = false

              if (
                this.$store.getters.user
                  .nbTodayParticipationsOnPendingValidation >= 3
              ) {
                this.$emit('too-many-participations')
              } else {
                this.$emit('next')
              }
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
::placeholder
  font-weight: 500
</style>
