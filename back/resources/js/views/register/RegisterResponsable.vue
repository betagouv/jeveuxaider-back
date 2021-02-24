<template>
  <div>
    <div class="relative bg-blue-800 overflow-hidden">
      <img
        class="z-1 object-cover absolute h-screen lg:h-auto"
        src="/images/bg-jva.jpg"
      />

      <div class="relative py-4 lg:py-12 z-10">
        <div class="mx-auto max-w-screen-xl">
          <div class="px-4 lg:px-8 lg:grid lg:grid-cols-12 lg:gap-8 pb-10">
            <div class="max-w-2xl mx-auto lg:col-span-6">
              <h2
                class="mt-10 lg:mt-24 text-4xl tracking-tight leading-10 font-bold text-white sm:leading-none sm:text-6xl lg:text-5xl xl:text-6xl"
              >
                Trouvez des bénévoles avec JeVeuxAider
              </h2>

              <ul class="pt-6 lg:pt-14">
                <li class="flex items-start">
                  <div class="flex-shrink-0">
                    <svg
                      class="h-6 w-6 text-green-400"
                      stroke="currentColor"
                      fill="none"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                      ></path>
                    </svg>
                  </div>
                  <p class="ml-3 text-xl lg:text-2xl leading-6 text-white">
                    Inscrivez votre organisation en quelques clics
                  </p>
                </li>
                <li class="mt-6 flex items-start">
                  <div class="flex-shrink-0">
                    <svg
                      class="h-6 w-6 text-green-400"
                      stroke="currentColor"
                      fill="none"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                      ></path>
                    </svg>
                  </div>
                  <p class="ml-3 text-xl lg:text-2xl leading-6 text-white">
                    Publiez vos missions de bénévolat
                  </p>
                </li>
                <li class="mt-6 flex items-start">
                  <div class="flex-shrink-0">
                    <svg
                      class="h-6 w-6 text-green-400"
                      stroke="currentColor"
                      fill="none"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                      ></path>
                    </svg>
                  </div>
                  <p class="ml-3 text-xl lg:text-2xl leading-6 text-white">
                    Trouvez des bénévoles motivés et disponibles
                  </p>
                </li>
              </ul>

              <p
                class="pt-10 pb-16 lg:pb-0 leading-10 text-4xl font-medium text-white sm:mt-5 tracking-tight"
              >
                Plus de <b class="font-bold">300 000 bénévoles</b> <br />sont
                prêts à vous aider.
              </p>
            </div>

            <div
              id="form"
              class="max-w-2xl mx-auto lg:col-span-6 lg:px-0 lg:w-full"
            >
              <div class="rounded-lg shadow-xl">
                <div class="bg-white rounded-t-lg px-6 pt-8 pb-8">
                  <div>
                    <p
                      class="text-center text-base text-gray-800 sm:text-xl lg:text-lg xl:text-xl"
                    >
                      Vous êtes une <strong>organisation publique</strong>, une
                      <strong>collectivité</strong> ou une
                      <strong>association</strong> ?
                    </p>
                    <h3
                      class="mt-2 text-center text-4xl leading-9 font-bold text-gray-900 sm:-mx-6 tracking-tight"
                    >
                      Rejoignez le mouvement
                    </h3>
                  </div>
                </div>
                <div
                  class="border-t-2 border-gray-100 rounded-b-lg pt-10 pb-2 px-4 sm:px-12 bg-gray-50"
                >
                  <template v-if="modeLight">
                    <div class="pt-6 pb-16 text-center">
                      <p>Quel élan de solidarité&nbsp;!</p>
                      <p>
                        Vous êtes actuellement très nombreux·ses à vouloir vous
                        engager et notre plateforme rencontre des difficultés.
                      </p>
                      <p>
                        Revenez dans quelques minutes pour vous inscrire. Nous
                        avons plus que jamais besoin de vous&nbsp;!
                      </p>
                    </div>
                  </template>
                  <template v-else>
                    <el-form
                      ref="registerResponsableForm"
                      :model="form"
                      label-position="top"
                      :rules="rules"
                      :hide-required-asterisk="true"
                    >
                      <div class="flex flex-wrap -m-2">
                        <el-form-item prop="structure_name" class="w-full p-2">
                          <el-input
                            v-model="form.structure_name"
                            placeholder="Votre organisation"
                            :disabled="disableFields"
                          />
                        </el-form-item>
                        <el-form-item
                          prop="first_name"
                          class="w-full sm:w-1/2 p-2"
                        >
                          <el-input
                            v-model="form.first_name"
                            placeholder="Prénom"
                            :disabled="disableFields"
                          />
                        </el-form-item>
                        <el-form-item
                          prop="last_name"
                          class="w-full sm:w-1/2 p-2"
                        >
                          <el-input
                            v-model="form.last_name"
                            placeholder="Nom"
                            :disabled="disableFields"
                          />
                        </el-form-item>
                        <el-form-item prop="email" class="w-full p-2">
                          <el-input
                            v-model.trim="form.email"
                            placeholder="E-mail"
                            :disabled="disableFields"
                            autocomplete="new-password"
                          />
                        </el-form-item>
                        <el-form-item
                          prop="password"
                          class="w-full sm:w-1/2 p-2"
                        >
                          <el-input
                            v-model="form.password"
                            placeholder="Mot de passe"
                            show-password
                            autocomplete="new-password"
                          />
                        </el-form-item>
                        <el-form-item
                          prop="password_confirmation"
                          class="w-full sm:w-1/2 p-2"
                        >
                          <el-input
                            v-model="form.password_confirmation"
                            placeholder="Confirmation de mot de passe"
                            show-password
                          />
                        </el-form-item>
                      </div>
                    </el-form>
                    <div class="mt-8 sm:col-span-">
                      <span class="block w-full rounded-md shadow-sm">
                        <el-button
                          type="primary"
                          :loading="loading"
                          class="shadow-lg block w-full text-center rounded-lg z-10 border border-transparent bg-green-400 px-4 sm:px-6 py-4 text-lg sm:text-2xl leading-6 font-medium text-white hover:bg-green-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150"
                          @click="onSubmit"
                          >J'inscris mon organisation</el-button
                        >
                      </span>
                    </div>

                    <div class="mt-6 mb-3 bg-gray-50">
                      <p class="text-xs leading-5 text-gray-500 text-center">
                        <span>En m'inscrivant j'accepte la</span>
                        <router-link
                          to="/politique-de-confidentialite"
                          target="_blank"
                          class="font-medium text-gray-900 hover:underline"
                        >
                          politique de confidentialité
                        </router-link>
                        <br />
                        <span>et la</span>
                        <router-link
                          to="/charte-reserve-civique"
                          target="_blank"
                          class="font-medium text-gray-900 hover:underline"
                        >
                          charte de JeVeuxAider
                        </router-link>
                        <br />
                        <span>Déjà inscrit ? </span>
                        <router-link to="/login">
                          <span
                            class="text-xs leading-5 text-center font-medium text-gray-900 hover:underline"
                          >
                            Je me connecte
                          </span>
                        </router-link>
                      </p>
                    </div>
                  </template>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-gray-100">
      <div class="max-w-screen-xl mx-auto pt-14 pb-20 px-4 sm:px-6 lg:px-8">
        <h3
          class="text-center leading-8 pb-8 text-gray-800 text-sm font-medium text-3xl tracking-tight px-4"
        >
          Plus de <b class="font-bold">4000 organisations</b> ont déjà rejoint
          JeVeuxAider
        </h3>
        <div class="overflow-hidden">
          <div class="flex flex-wrap items-center justify-center -m-4 sm:-m-8">
            <img
              alt=""
              class="min-w-0 m-4 sm:m-8 object-contain"
              src="/images/logo_article1.png"
              style="max-height: 3rem"
            />

            <img
              alt=""
              class="min-w-0 m-4 sm:m-8 object-contain max-h-20"
              src="/images/logo_aphp.png"
            />
            <img
              alt=""
              class="min-w-0 m-4 sm:m-8 object-contain max-h-20"
              src="/images/logo_emmaus.png"
            />
            <img
              alt=""
              class="min-w-0 m-4 sm:m-8 object-contain max-h-20"
              src="/images/logo_banquealimentaire.png"
              style="max-width: 11rem"
            />
            <img
              alt=""
              class="min-w-0 m-4 sm:m-8 object-contain"
              src="/images/logo-jagis-pour-la-nature.svg"
              style="max-height: 3rem"
            />
          </div>
        </div>
      </div>
    </div>

    <section class="bg-gray-100 pb-16">
      <div
        class="max-w-screen-xl mx-auto grid gap-6 lg:grid-cols-2 px-4 lg:px-6 lg:px-8"
      >
        <div
          class="p-8 sm:p-12 sm:px-24 lg:px-12 lg:flex lg:flex-col bg-white rounded-lg shadow-lg"
        >
          <div class="md:flex-shrink-0">
            <img alt="" class="h-10 mt-4" src="/images/lafourmiliere.png" />
          </div>

          <blockquote class="mt-8 lg:flex-grow lg:flex lg:flex-col">
            <div
              class="relative text-lg leading-7 font-medium text-gray-800 md:flex-grow"
            >
              <svg
                class="absolute top-0 left-0 transform -translate-x-3 -translate-y-3 h-8 w-8 text-gray-300 opacity-50"
                fill="currentColor"
                viewBox="0 0 32 32"
              >
                <path
                  d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z"
                ></path>
              </svg>
              <p class="relative">
                La puissance de mobilisation de JeVeuxAider est impressionnante
                ! Nous avons reçu 170 nouvelles candidatures de bénévoles en
                moins de 6 semaines. JeVeuxAider est clairement l’un des
                meilleurs canaux en termes d’efficacité du temps investi et de
                nombre de retours. En plus, les bénévoles viennent de toute la
                France et de divers horizons sociaux.
              </p>
            </div>
            <footer class="mt-6">
              <div class="flex items-start">
                <div
                  class="flex-shrink-0 inline-flex rounded-full border-2 border-gray-200"
                >
                  <img
                    alt=""
                    class="h-12 w-12 rounded-full"
                    src="/images/clement.jpg"
                  />
                </div>
                <div class="ml-4">
                  <div class="text-base leading-6 font-medium text-gray-900">
                    Clément Debosque
                  </div>
                  <div class="text-base leading-6 font-medium text-gray-400">
                    Co-fondateur de La Fourmilière
                  </div>
                </div>
              </div>
            </footer>
          </blockquote>
        </div>
        <div
          class="p-8 sm:p-12 sm:px-24 lg:px-12 lg:flex lg:flex-col bg-white rounded-lg shadow-lg"
        >
          <div class="md:flex-shrink-0">
            <img alt="" class="h-10 mt-4" src="/images/gip.png" />
          </div>

          <blockquote class="mt-8 lg:flex-grow lg:flex lg:flex-col">
            <div
              class="relative text-lg leading-7 font-medium text-gray-800 md:flex-grow"
            >
              <svg
                class="absolute top-0 left-0 transform -translate-x-3 -translate-y-3 h-8 w-8 text-gray-300 opacity-50"
                fill="currentColor"
                viewBox="0 0 32 32"
              >
                <path
                  d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z"
                ></path>
              </svg>
              <p class="relative">
                Pendant la crise de Covid-19, Gers Solidaire s’est engagé avec
                JeVeuxAider : plus de 100 personnes ont répondu favorablement à
                notre appel. Nous avons livré 700 masques dans tout le
                département avec l’aide de JeVeuxAider. Une trentaine de
                volontaires se sont également engagés auprès des personnages
                âgées pour les aider dans leur vie quotidienne (appels de
                courtoisie, livraisons de médicaments, promenade ...). Il s’agit
                de prendre soin des gens, tout simplement.
              </p>
            </div>
            <footer class="mt-6">
              <div class="flex items-start">
                <div
                  class="flex-shrink-0 inline-flex rounded-full border-2 border-gray-200"
                >
                  <img
                    class="h-12 w-12 rounded-full"
                    src="/images/rene.jpg"
                    alt=""
                  />
                </div>
                <div class="ml-4">
                  <div class="text-base leading-6 font-medium text-gray-900">
                    René Ortega
                  </div>
                  <div class="text-base leading-6 font-medium text-gray-400">
                    GIP Gers Solidaire
                  </div>
                </div>
              </div>
            </footer>
          </blockquote>
        </div>
      </div>
      <div class="mt-16">
        <div class="px-4">
          <img
            alt=""
            class="h-10 mx-auto opacity-75 mb-4"
            src="/images/logo-gray.svg"
          />
          <img
            alt=""
            class="w-full sm:h-24 sm:w-auto mx-auto opacity-50"
            src="/images/chacunpourtous.png"
            style="max-width: 420px"
          />
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import metadatas from '@/utils/metadatas.json'

export default {
  name: 'RegisterResponsable',
  metaInfo: metadatas.filter((item) => item.name == 'RegisterResponsable')[0]
    .metaInfo,
  data() {
    var validatePass2 = (rule, value, callback) => {
      if (value !== this.form.password) {
        callback(new Error('Les mots de passe ne sont pas identiques'))
      } else {
        callback()
      }
    }
    return {
      loading: false,
      disableFields: false,
      form: {
        email: '',
        first_name: '',
        last_name: '',
        password: '',
        structure_name: '',
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
        structure_name: [
          {
            required: true,
            message: 'Nom de votre organisation obligatoire',
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
    modeLight() {
      return process.env.MIX_MODE_APP_LIGHT
        ? JSON.parse(process.env.MIX_MODE_APP_LIGHT)
        : false
    },
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs['registerResponsableForm'].validate((valid) => {
        if (valid) {
          this.$store
            .dispatch('auth/registerResponsable', {
              email: this.form.email,
              password: this.form.password,
              first_name: this.form.first_name,
              last_name: this.form.last_name,
              structure_name: this.form.structure_name,
            })
            .then(() => {
              this.loading = false
              this.$router.push('/register/responsable/step/profile')
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

::v-deep .el-input,
::v-deep .el-input input
  @apply rounded-lg

::v-deep .el-checkbox
  @apply text-gray-500 font-normal
</style>
