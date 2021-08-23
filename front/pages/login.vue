<template>
  <div class="relative bg-blue-800 overflow-hidden">
    <img
      class="z-1 object-cover absolute h-screen lg:h-auto"
      alt="JeVeuxAider"
      :srcSet="bgHeroMultipleSizes.srcSet"
      :src="bgHeroMultipleSizes.src"
    />

    <div class="pb-12 mt-12 px-4 relative w-full lg:inset-y-0 text-center z-10">
      <template v-if="isFranceConnectActive">
        <div class="">
          <h2
            class="mt-6 mb-4 md:mb-0 text-center text-3xl font-bold text-white leading-8 px-4"
          >
            Utilisez FranceConnect pour vous connecter ou créer un compte
          </h2>
          <p class="text-center text-base text-blue-200">
            FranceConnect, c'est la solution proposée par l'Etat pour sécuriser
            et simplifier la connexion à plus de 700 services en ligne.
          </p>
        </div>

        <div
          v-show="isLoadingFranceConnect"
          class="text-white font-medium text-center p-4"
        >
          Connexion en cours avec FranceConnect...
        </div>
      </template>
      <template v-else>
        <div class="">
          <h2
            class="mt-6 mb-6 md:mb-12 text-center text-3xl font-bold text-white leading-8 px-4"
          >
            Connexion à votre compte
          </h2>
        </div>
      </template>
      <div v-show="!isLoadingFranceConnect">
        <div
          v-if="isFranceConnectActive"
          class="mt-4 sm:mx-auto sm:w-full sm:max-w-md text-left"
        >
          <div class="py-4 px-4 sm:px-10 text-center">
            <div class="relative">
              <FranceConnect @loading="isLoadingFranceConnect = $event" />
              <span class="block mt-4 text-blue-200">OU</span>
            </div>
          </div>
        </div>

        <div class="mt-2 sm:mx-auto sm:w-full sm:max-w-md text-left">
          <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <div class="relative">
              <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
              </div>
              <div class="relative flex justify-center text-xl">
                <span class="px-2 bg-white font-bold text-gray-900">
                  J'ai déjà un compte
                </span>
              </div>
            </div>
            <el-form
              ref="loginForm"
              class="mt-4"
              :model="form"
              label-position="top"
              :rules="rules"
              :hide-required-asterisk="true"
            >
              <el-form-item label="E-mail" prop="email">
                <el-input v-model.trim="form.email" placeholder="Email" />
              </el-form-item>
              <el-form-item label="Mot de passe" prop="password" class="mb-0">
                <el-input
                  v-model="form.password"
                  placeholder="Entrez votre mot de passe"
                  show-password
                  @keyup.native.enter="onSubmit"
                />
              </el-form-item>
              <div class="pt-4 text-sm leading-5">
                <nuxt-link
                  to="/password-reset"
                  class="font-medium text-blue-800 hover:text-blue-900 focus:underline transition ease-in-out duration-150"
                >
                  Mot de passe perdu ?
                </nuxt-link>
              </div>
            </el-form>

            <div class="mt-4">
              <button
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-lg font-bold text-white bg-blue-800 hover:shadow-lg hover:scale-105 transform transition duration-150 ease-in-out"
                @click="onSubmit"
              >
                Connexion
              </button>
            </div>

            <div class="mt-8">
              <div class="relative">
                <div class="absolute inset-0 flex items-center">
                  <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-xl">
                  <span class="px-2 bg-white font-bold text-gray-900">
                    Première visite ?
                  </span>
                </div>
              </div>

              <div>
                <nuxt-link
                  to="/register/volontaire"
                  class="mt-4 w-full flex justify-center py-3 px-4 border border-transparent rounded-lg text-lg shadow-sm font-bold text-gray-800 border-gray-200 bg-white hover:shadow-lg hover:scale-105 transform transition duration-150 ease-in-out"
                >
                  Créez votre espace bénévole
                </nuxt-link>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="max-w-2xl mx-auto">
        <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
          <div class="border-blue-600 border overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <dt class="text-base font-medium text-blue-200 truncate">
                Vous êtes une organisation ?
              </dt>
              <nuxt-link
                to="/inscription/organisation"
                class="mt-4 w-full flex justify-center py-3 px-4 rounded-lg text-lg shadow-sm font-bold text-blue-900 bg-blue-200 hover:shadow-lg hover:text-gray-800 hover:border-transparent hover:bg-white hover:scale-105 transform transition duration-150 ease-in-out"
              >
                Publiez vos missions
              </nuxt-link>
            </div>
          </div>

          <div class="border-blue-600 border overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <dt class="text-base font-medium text-blue-200 truncate">
                Vous êtes une collectivité ?
              </dt>
              <nuxt-link
                to="/inscription/organisation?orga_type=Collectivité"
                class="mt-4 w-full flex justify-center py-3 px-4 rounded-lg text-lg shadow-sm font-bold text-blue-900 bg-blue-200 hover:shadow-lg hover:text-gray-800 hover:border-transparent hover:bg-white hover:scale-105 transform transition duration-150 ease-in-out"
              >
                Créez votre page
              </nuxt-link>
            </div>
          </div>
        </dl>
      </div>
    </div>
  </div>
</template>

<script>
import FormMixin from '@/mixins/Form'

const bgHeroMultipleSizes = require('@/assets/images/bg-jva.jpg?resize&sizes[]=320&sizes[]=640&sizes[]=960&sizes[]=1200&sizes[]=1800&sizes[]=2400&sizes[]=3900')

export default {
  name: 'Login',
  mixins: [FormMixin],
  middleware: 'guest',
  data() {
    return {
      bgHeroMultipleSizes,
      loading: false,
      isLoadingFranceConnect: false,
      form: {
        email: this.$route.query.email ? this.$route.query.email : '',
        password: '',
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
        password: [
          {
            required: true,
            message: 'Veuillez renseigner votre mot de passe',
            trigger: 'change',
          },
          {
            min: 8,
            message: 'Votre mot de passe doit contenir au moins 8 charactères',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  head() {
    return {
      title: 'Connectez-vous à votre espace personnel | JeVeuxAider.gouv.fr',
      link: [
        {
          rel: 'canonical',
          href: 'https://www.jeveuxaider.gouv.fr/login',
        },
      ],
      meta: [
        {
          hid: 'description',
          name: 'description',
          content:
            'Connectez-vous à votre espace personnel et gérez vos missions de bénévolat en quelques clics. ',
        },
        {
          hid: 'og:image',
          property: 'og:image',
          content: '/images/share-image.jpg',
        },
      ],
    }
  },
  computed: {
    isFranceConnectActive() {
      return !!this.$config.franceConnect
    },
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs.loginForm.validate((valid, fields) => {
        if (valid) {
          this.$store.dispatch('auth/login', {
            email: this.form.email,
            password: this.form.password,
          })
        } else {
          this.showErrors(fields)
        }
      })
    },
  },
}
</script>
