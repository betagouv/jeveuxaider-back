<template>
  <div class="register mx-auto w-full" style="max-width: 620px;">
    <div>
      <router-link to="/">
        <img
          class="h-8 w-auto"
          src="/images/logo-header-dark.png"
          alt="Réserve Civique"
        />
      </router-link>
      <h2 class="mt-8 text-3xl leading-tight font-extrabold text-gray-900">
        Vous souhaitez rejoindre les volontaires ?
        <br />Rejoignez la
        <span class="text-blue-800">Réserve Civique</span>
      </h2>
    </div>
    <div class="mt-8 border-t border-gray-200 pt-8" />
    <div>
      <h3 class="text-lg font-medium text-gray-900">
        Engagez-vous pour faire vivre les valeurs de la République
      </h3>
      <p class="mt-1 text-sm text-gray-500">
        <router-link to="/regles-de-securite">
          Cet engagement nécessite un respect strict des règles sanitaires
          applicables.
        </router-link>
      </p>
    </div>
    <template v-if="modeLigth">
      <div class="py-8">
        Quel élan de solidarité !
        <br />Vous êtes actuellement très nombreux·ses à vouloir vous engager et
        notre plateforme rencontre des difficultés. Revenez dans quelques
        minutes pour vous inscrire. Nous avons plus que jamais besoin de vous !
      </div>
    </template>
    <template v-else>
      <el-form
        ref="registerVolontaireForm"
        :model="form"
        label-position="top"
        :rules="rules"
        :hide-required-asterisk="true"
        class="mt-6"
      >
        <div class="flex flex-wrap -m-2">
          <el-form-item
            label="Prénom"
            prop="first_name"
            class="w-full sm:w-1/2 p-2"
          >
            <el-input v-model="form.first_name" placeholder="Prénom" />
          </el-form-item>
          <el-form-item
            label="Nom"
            prop="last_name"
            class="w-full sm:w-1/2 p-2"
          >
            <el-input v-model="form.last_name" placeholder="Nom" />
          </el-form-item>
          <el-form-item label="E-mail" prop="email" class="w-full sm:w-1/2 p-2">
            <el-input v-model.trim="form.email" placeholder="E-mail" />
          </el-form-item>
          <el-form-item
            label="Code Postal"
            prop="zip"
            class="w-full sm:w-1/2 p-2"
          >
            <el-input v-model="form.zip" placeholder="Code Postal" />
          </el-form-item>
          <el-form-item
            label="Téléphone mobile"
            prop="mobile"
            class="w-full sm:w-1/2 p-2"
          >
            <el-input v-model="form.mobile" placeholder="Téléphone mobile" />
          </el-form-item>
          <el-form-item
            label="Date de naissance"
            prop="birthday"
            class="w-full sm:w-1/2 p-2"
          >
            <el-input
              v-model="form.birthday"
              v-mask="'##/##/####'"
              placeholder="__/__/____"
            />
          </el-form-item>
          <el-form-item
            label="Mot de passe"
            prop="password"
            class="w-full sm:w-1/2 p-2"
          >
            <el-input
              v-model="form.password"
              placeholder="Choisissez votre mot de passe"
              show-password
            />
          </el-form-item>
          <el-form-item
            label="Confirmation du mot de passe"
            prop="password_confirmation"
            class="w-full sm:w-1/2 p-2"
          >
            <el-input
              v-model="form.password_confirmation"
              placeholder="Confirmez votre mot de passe"
              show-password
            />
          </el-form-item>
          <el-form-item class="-mb-3 py-4 ml-2" prop="service_civique">
            <el-checkbox
              v-model="form.service_civique"
              @change="handleServiceCiviqueChange"
            >
              Je suis volontaire en Service Civique
            </el-checkbox>
          </el-form-item>
        </div>
      </el-form>
      <div class="mt-8 sm:col-span-">
        <span class="block w-full rounded-md shadow-sm">
          <el-button
            type="primary"
            :loading="loading"
            style="height: 48px;"
            class="w-full flex justify-center py-2 px-4 border border-transparent sm:text-xl font-medium rounded-md text-white bg-blue-800 hover:bg-primary focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out"
            @click="onSubmit"
            >Je m'inscris en tant que volontaire</el-button
          >
        </span>
      </div>
    </template>

    <div class="mt-6">
      <div class="relative">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t border-gray-300" />
        </div>
        <div class="relative flex justify-center text-sm">
          <span class="px-2 bg-white text-gray-500">OU</span>
        </div>
      </div>
      <div class="mt-6 sm:col-span-">
        <router-link to="/login">
          <span class="block w-full rounded-md shadow-sm">
            <button
              type="submit"
              class="w-full flex justify-center py-2 px-4 border border-transparent font-medium border border-gray-300 rounded bg-white text-sm font-medium text-gray-500 hover:text-gray-400 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition duration-150 ease-in-out"
            >
              J'ai déjà un compte
            </button>
          </span>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import dayjs from 'dayjs'
var customParseFormat = require('dayjs/plugin/customParseFormat')
dayjs.extend(customParseFormat)

export default {
  name: 'RegisterVolontaire',
  data() {
    var checkLowercase = (rule, value, callback) => {
      if (value !== value.toLowerCase()) {
        callback(new Error('Merci de ne saisir que des minuscules'))
      } else {
        callback()
      }
    }
    var validatePass2 = (rule, value, callback) => {
      if (value !== this.form.password) {
        callback(new Error('Les mots de passe ne sont pas identiques'))
      } else {
        callback()
      }
    }
    return {
      loading: false,
      form: {
        email: '',
        first_name: '',
        last_name: '',
        password: '',
        mobile: '',
        zip: '',
        birthday: '',
        service_civique: false,
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
          { validator: checkLowercase, trigger: 'blur' },
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
        birthday: [
          {
            required: true,
            message: 'Date de naissance obligatoire',
            trigger: 'blur',
          },
          {
            pattern: /^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/,
            message: 'Format accepté: 24/12/1990',
            trigger: 'blur',
          },
        ],
        zip: [
          {
            required: true,
            message: 'Code postal obligatoire',
            trigger: 'blur',
          },
          {
            pattern: /^\d+$/,
            message: 'Ne doit contenir que des chiffres',
            trigger: 'blur',
          },
          {
            min: 5,
            max: 5,
            message: 'Format erroné',
            trigger: 'blur',
          },
        ],
        mobile: [
          {
            required: true,
            message: 'Téléphone obligatoire',
            trigger: 'blur',
          },
          {
            pattern: /^[+|\s|\d]*$/,
            message: 'Le format du numéro de téléphone est incorrect',
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
            message: 'Votre mot de passe doit contenir au moins 8 charactères',
            trigger: 'blur',
          },
        ],
        password_confirmation: [{ validator: validatePass2, trigger: 'blur' }],
      },
    }
  },
  computed: {
    modeLigth() {
      return process.env.MIX_MODE_APP_LIGTH
        ? JSON.parse(process.env.MIX_MODE_APP_LIGTH)
        : false
    },
  },
  methods: {
    handleServiceCiviqueChange() {
      if (this.form.service_civique) {
        this.$confirm(
          'Je suis actuellement sous contrat de Service Civique et certifie sur l’honneur que ma mission de Service Civique est, en accord avec mon organisme d’accueil en Service Civique, actuellement en tout ou partie suspendue dans le cadre des mesures prises pour la lutte contre la propagation du virus Covid-19.<br><br> Je me déclare volontaire pour rejoindre la Réserve civique jusqu’à la date de reprise, à temps complet, de ma mission au sein de mon organisme d’accueil en Service Civique.<br>',
          'Confirmation',
          {
            confirmButtonText: 'Accepter',
            cancelButtonText: 'Annuler',
            // type: "warning",
            center: true,
            dangerouslyUseHTMLString: true,
          }
        )
          .then(() => {})
          .catch(() => {
            this.form.service_civique = false
          })
      }
    },
    onSubmit() {
      this.loading = true
      this.$refs['registerVolontaireForm'].validate((valid) => {
        if (valid) {
          let birthdayValidFormat = dayjs(
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
              this.loading = false
              this.$router.push('/register/reserviste/step')
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
