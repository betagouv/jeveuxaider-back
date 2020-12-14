<template>
  <div class="relative bg-blue-800 overflow-hidden">
    <img
      class="z-1 object-cover absolute h-screen lg:h-auto"
      src="/images/bg-jva.jpg"
    />

    <div class="pb-12 mt-12 px-4 relative w-full lg:inset-y-0 text-center z-10">
      <div class="">
        <h2
          class="mt-6 mb-4 md:mb-0 text-center text-3xl font-bold text-white leading-8 px-4"
        >
          Utilisez FranceConnect pour vous connecter ou créer un compte
        </h2>
        <p class="text-center text-base text-blue-200">
          FranceConnect, c'est la solution proposée par l'Etat pour sécuriser et
          simplifier la connexion à plus de 700 services en ligne.
        </p>
      </div>

      <div
        v-show="isLoadingFranceConnect"
        class="text-white font-medium text-center p-4"
      >
        Connexion en cours avec FranceConnect...
      </div>
      <div v-show="!isLoadingFranceConnect">
        <div class="mt-4 sm:mx-auto sm:w-full sm:max-w-md text-left">
          <div class="py-4 px-4 sm:px-10 text-center">
            <div class="relative text-blue-200">
              <FranceConnect @loading="isLoadingFranceConnect = $event" />
              <span class="block mt-4">OU</span>
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
              <div class="pt-4">
                <router-link to="/password/forgot">
                  <div class="text-sm leading-5">
                    <router-link
                      to="/password/forgot"
                      class="font-medium text-blue-800 hover:text-blue-900 focus:outline-none focus:underline transition ease-in-out duration-150"
                    >
                      Mot de passe perdu ?
                    </router-link>
                  </div>
                </router-link>
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
                <router-link
                  to="/register/volontaire"
                  class="mt-4 w-full flex justify-center py-3 px-4 border border-transparent rounded-lg text-lg shadow-sm font-bold text-gray-800 border-gray-200 bg-white hover:shadow-lg hover:scale-105 transform transition duration-150 ease-in-out"
                >
                  Créez votre espace bénévole
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- This example requires Tailwind CSS v2.0+ -->
      <div class="max-w-2xl mx-auto">
        <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
          <div class="border-blue-600 border overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <dt class="text-base font-medium text-blue-200 truncate">
                Vous êtes une organisation ?
              </dt>
              <router-link
                to="/register/responsable"
                class="mt-4 w-full flex justify-center py-3 px-4 rounded-lg text-lg shadow-sm font-bold text-blue-900 bg-blue-300 hover:shadow-lg hover:text-gray-800 hover:border-transparent hover:bg-white hover:scale-105 transform transition duration-150 ease-in-out"
              >
                Publiez vos missions
              </router-link>
            </div>
          </div>

          <div class="border-blue-600 border overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <dt class="text-base font-medium text-blue-200 truncate">
                Vous êtes une collectivité ?
              </dt>
              <router-link
                to="/register/responsable"
                class="mt-4 w-full flex justify-center py-3 px-4 rounded-lg text-lg shadow-sm font-bold text-blue-900 bg-blue-300 hover:shadow-lg hover:text-gray-800 hover:border-transparent hover:bg-white hover:scale-105 transform transition duration-150 ease-in-out"
              >
                Créez votre page
              </router-link>
            </div>
          </div>
        </dl>
      </div>
    </div>
  </div>
</template>

<script>
import metadatas from '@/utils/metadatas.json'
import FranceConnect from '@/components/FranceConnect.vue'

export default {
  name: 'Login',
  metaInfo: metadatas.filter((item) => item.name == 'Login')[0].metaInfo,
  components: {
    FranceConnect,
  },
  data() {
    return {
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
            message: 'Choisissez votre mot de passe',
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
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs['loginForm'].validate((valid) => {
        if (valid) {
          this.$store
            .dispatch('auth/login', {
              email: this.form.email,
              password: this.form.password,
            })
            .then(() => {
              if (this.$route.query.redirect) {
                this.$router.push(this.$route.query.redirect)
              } else {
                // console.log("noRole", this.$store.getters.noRole);
                if (
                  this.$store.getters.noRole === true &&
                  this.$store.getters.contextRole != 'volontaire'
                ) {
                  this.$router.push('/register/responsable/step/norole')
                }
                if (this.$store.getters.noRole === false) {
                  this.$router.push('/dashboard')
                } else {
                  this.$router.push('/missions')
                }
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
