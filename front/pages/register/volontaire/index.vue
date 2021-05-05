<template>
  <div>
    <div class="relative bg-blue-800 overflow-hidden">
      <img
        class="z-1 object-cover absolute h-screen lg:h-auto"
        alt="Je Veux Aider"
        :srcSet="bgHeroMultipleSizes.srcSet"
        :src="bgHeroMultipleSizes.src"
      />

      <div class="relative py-4 lg:py-12 z-10">
        <div class="mx-auto max-w-screen-xl">
          <div class="px-4 lg:px-8 lg:grid lg:grid-cols-12 lg:gap-8 pb-10">
            <div class="max-w-2xl mx-auto lg:col-span-6">
              <h1
                class="mt-10 lg:mt-24 text-4xl tracking-tight leading-10 font-bold text-white sm:leading-none sm:text-6xl lg:text-5xl xl:text-6xl"
              >
                Devenez bénévole avec JeVeuxAider.gouv.fr
              </h1>

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
                    Trouvez des missions en quelques clics
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
                    Devenez bénévole près de chez vous ou à distance
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
                    Faites vivre l'engagement de chacun pour tous&nbsp;!
                  </p>
                </li>
              </ul>

              <p
                class="pt-10 pb-16 lg:pb-0 leading-10 text-4xl font-medium text-white sm:mt-5 tracking-tight"
              >
                Plus de <b class="font-bold">300 000 bénévoles</b> <br />sont
                déjà inscrits.
              </p>
            </div>

            <div
              id="form"
              class="max-w-2xl mx-auto lg:col-span-6 lg:px-0 lg:w-full"
            >
              <div class="rounded-lg shadow-xl">
                <div
                  class="bg-white px-6"
                  :class="
                    isLoadingFranceConnect ? 'rounded-lg' : 'rounded-t-lg'
                  "
                >
                  <template v-if="isFranceConnectActive">
                    <div class="pt-8">
                      <h2
                        class="mt-2 text-center text-3xl font-bold text-gray-900 leading-8 px-4"
                      >
                        Utilisez FranceConnect pour créer votre espace bénévole
                      </h2>

                      <div
                        v-show="isLoadingFranceConnect"
                        class="font-medium text-center p-4"
                      >
                        Inscription en cours avec FranceConnect...
                      </div>

                      <div v-show="!isLoadingFranceConnect">
                        <div
                          class="mt-4 sm:mx-auto sm:w-full sm:max-w-md text-left"
                        >
                          <div class="py-4 px-4 sm:px-10 text-center">
                            <div class="relative text-gray-500">
                              <FranceConnect
                                is-dark
                                @loading="isLoadingFranceConnect = $event"
                              />
                              <span class="block mt-4">OU</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </template>
                  <template v-else>
                    <div class="py-6">
                      <h2
                        class="text-center text-3xl font-bold text-gray-900 leading-8 px-4"
                      >
                        Création de votre compte
                      </h2>
                    </div>
                  </template>
                </div>
                <div
                  v-show="!isLoadingFranceConnect"
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
                      ref="registerVolontaireForm"
                      :model="form"
                      label-position="top"
                      :rules="rules"
                      :hide-required-asterisk="true"
                      class="form-register-steps"
                    >
                      <div class="flex flex-wrap -m-2">
                        <el-form-item
                          label="Prénom"
                          prop="first_name"
                          class="w-full sm:w-1/2 p-2"
                        >
                          <el-input
                            v-model="form.first_name"
                            label="Prénom"
                            autocomplete="new-password"
                            placeholder="Prénom"
                          />
                        </el-form-item>
                        <el-form-item
                          label="Nom"
                          prop="last_name"
                          class="w-full sm:w-1/2 p-2"
                        >
                          <el-input
                            v-model="form.last_name"
                            label="Nom"
                            autocomplete="new-password"
                            placeholder="Nom"
                          />
                        </el-form-item>
                        <el-form-item
                          label="E-mail"
                          prop="email"
                          class="w-full sm:w-1/2 p-2"
                        >
                          <el-input
                            v-model.trim="form.email"
                            label="E-mail"
                            autocomplete="new-password"
                            placeholder="E-mail"
                          />
                        </el-form-item>
                        <el-form-item
                          label="Code postal"
                          prop="zip"
                          class="w-full sm:w-1/2 p-2"
                        >
                          <el-input
                            v-model="form.zip"
                            label="Code postal"
                            autocomplete="new-password"
                            placeholder="Code Postal"
                          />
                        </el-form-item>
                        <el-form-item
                          label="Portable"
                          prop="mobile"
                          class="w-full sm:w-1/2 p-2"
                        >
                          <el-input
                            v-model="form.mobile"
                            label="Mobile"
                            placeholder="Téléphone mobile"
                          />
                        </el-form-item>
                        <el-form-item
                          label="Date de naissance"
                          prop="birthday"
                          class="w-full sm:w-1/2 p-2"
                        >
                          <el-input
                            v-model="form.birthday"
                            v-mask="'##/##/####'"
                            autocomplete="new-password"
                            label="Date de naissance"
                            placeholder="Date de naissance"
                          />
                        </el-form-item>
                        <el-form-item
                          label="Mot de passe"
                          prop="password"
                          class="w-full sm:w-1/2 p-2"
                        >
                          <el-input
                            v-model="form.password"
                            placeholder="Mot de passe"
                            label="Mot de passe"
                            show-password
                            autocomplete="new-password"
                          />
                        </el-form-item>
                        <el-form-item
                          label="Confirmation"
                          prop="password_confirmation"
                          class="w-full sm:w-1/2 p-2"
                        >
                          <el-input
                            v-model="form.password_confirmation"
                            label="Confirmation mot de passe"
                            placeholder="Confirmation mot de passe"
                            show-password
                          />
                        </el-form-item>
                        <el-form-item
                          class="-mb-3 py-4 ml-2"
                          prop="service_civique"
                        >
                          <el-checkbox
                            v-model="form.service_civique"
                            @change="handleServiceCiviqueChange"
                          >
                            Cochez si vous êtes déjà volontaire en Service
                            Civique
                          </el-checkbox>
                        </el-form-item>
                      </div>
                    </el-form>
                    <div class="mt-8 sm:col-span-">
                      <span class="block w-full rounded-md shadow-sm">
                        <el-button
                          type="primary"
                          :loading="loading"
                          class="shadow-lg block w-full text-center rounded-lg z-10 border border-transparent bg-green-400 px-6 py-4 text-2xl leading-6 font-medium text-white hover:bg-green-500 focus:border-indigo-700 focus:outline-none focus:shadow-outline-indigo transition ease-in-out duration-150"
                          @click="onSubmit"
                          >Je m'inscris<span class="hidden sm:inline">
                            en tant que bénévole</span
                          ></el-button
                        >
                      </span>
                    </div>

                    <div class="mt-6 mb-3 bg-gray-50">
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
                        <br />
                        <span>Déjà inscrit ? </span>
                        <nuxt-link to="/login">
                          <span
                            class="text-xs leading-5 text-center font-medium text-gray-900 hover:underline"
                          >
                            Je me connecte
                          </span>
                        </nuxt-link>
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
          JeVeuxAider.gouv.fr
        </h3>
        <div class="overflow-hidden">
          <div class="flex flex-wrap items-center justify-center -m-4 sm:-m-8">
            <img
              alt="Article1"
              class="min-w-0 m-4 sm:m-8 object-contain"
              src="@/assets/images/logo_article1.png"
              style="max-height: 3rem"
            />

            <img
              alt="APHP"
              class="min-w-0 m-4 sm:m-8 object-contain max-h-20"
              src="@/assets/images/logo_aphp.png"
            />
            <img
              alt="Emmaus"
              class="min-w-0 m-4 sm:m-8 object-contain max-h-20"
              src="@/assets/images/logo_emmaus.png"
            />
            <img
              alt="Banque Alimentaire"
              class="min-w-0 m-4 sm:m-8 object-contain max-h-20"
              src="@/assets/images/logo_banquealimentaire.png"
              style="max-width: 11rem"
            />
            <img
              alt="J'agis pour la nature"
              class="min-w-0 m-4 sm:m-8 object-contain"
              src="@/assets/images/logo-jagis-pour-la-nature.png"
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
          <blockquote class="lg:flex-grow lg:flex lg:flex-col">
            <div
              class="relative text-lg leading-7 font-medium text-gray-800 lg:flex-grow"
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
                J’ai eu la chance de pouvoir me rendre utile en faisant une
                distribution de repas pour les SDF de Nancy. Une expérience qui
                ne laisse pas indifférent … J'ai des souvenirs plein la tête.
                Maintenant je continue mon chemin avec JeVeuxAider.gouv.fr. Je
                me sens utile et citoyenne.
              </p>
            </div>
            <footer class="mt-6">
              <div class="flex items-start">
                <div
                  class="flex-shrink-0 inline-flex rounded-full border-2 border-gray-200"
                >
                  <img
                    alt="photo bénévole"
                    class="h-12 w-12 rounded-full"
                    src="@/assets/images/bene2.png"
                  />
                </div>
                <div class="ml-4">
                  <div class="text-base leading-6 font-medium text-gray-900">
                    Catherine
                  </div>
                  <div class="text-base leading-6 font-medium text-gray-400">
                    Bénévole à Nancy (54)
                  </div>
                </div>
              </div>
            </footer>
          </blockquote>
        </div>
        <div
          class="p-8 sm:p-12 sm:px-24 lg:px-12 lg:flex lg:flex-col bg-white rounded-lg shadow-lg"
        >
          <blockquote class="lg:flex-grow lg:flex lg:flex-col">
            <div
              class="relative text-lg leading-7 font-medium text-gray-800 lg:flex-grow"
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
                Pendant la crise de Covid-19, je ressentais le besoin de
                m’engager et d’aider. Je me suis inscrite sur
                JeVeuxAider.gouv.fr et j’ai participé au dispositif Covidom, aux
                côtés des professionnels de l'APHP et d'une équipe de bénévoles
                très motivés. Notre mission : nous assurer que tous les patients
                puissent bénéficier d’un suivi médical à distance. Covidom
                montre que le partenariat entre public, privé et société civile
                est non seulement possible mais surtout incroyablement efficace
                en situation d’urgence.
              </p>
            </div>
            <footer class="mt-6">
              <div class="flex items-start">
                <div
                  class="flex-shrink-0 inline-flex rounded-full border-2 border-gray-200"
                >
                  <img
                    class="h-12 w-12 rounded-full"
                    src="@/assets/images/bene.png"
                    alt="photo bénévole"
                  />
                </div>
                <div class="ml-4">
                  <div class="text-base leading-6 font-medium text-gray-900">
                    Marion
                  </div>
                  <div class="text-base leading-6 font-medium text-gray-400">
                    Bénévole à Paris (75)
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
            class="h-10 mx-auto opacity-75 mb-4"
            src="@/assets/images/logo-gray.svg"
            alt=""
          />
          <img
            class="w-full sm:h-24 sm:w-auto mx-auto opacity-50"
            src="@/assets/images/chacunpourtous.png"
            style="max-width: 420px"
            alt=""
          />
        </div>
      </div>
    </section>
  </div>
</template>

<script>
const bgHeroMultipleSizes = require('@/assets/images/bg-jva.jpg?resize&sizes[]=320&sizes[]=640&sizes[]=960&sizes[]=1200&sizes[]=1800&sizes[]=2400&sizes[]=3900')

export default {
  middleware: 'guest',
  data() {
    const validatePass2 = (rule, value, callback) => {
      if (value !== this.form.password) {
        callback(new Error('Les mots de passe ne sont pas identiques'))
      } else {
        callback()
      }
    }
    return {
      loading: false,
      isLoadingFranceConnect: false,
      bgHeroMultipleSizes,
      form: {
        email: '',
        first_name: '',
        last_name: '',
        password: '',
        mobile: '',
        zip: '',
        birthday: null,
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
          {
            min: 10,
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
            message: 'Votre mot de passe doit contenir au moins 8 caractères',
            trigger: 'blur',
          },
        ],
        password_confirmation: [{ validator: validatePass2, trigger: 'blur' }],
      },
    }
  },
  head() {
    return {
      title:
        'Devenez bénévole avec JeVeuxAider.gouv.fr, la plateforme publique du bénévolat par la Réserve Civique',
      meta: [
        {
          hid: 'description',
          name: 'description',
          content:
            'Créez votre page dédiée et centralisez les missions de vos associations et organisations publiques afin de promouvoir le bénévolat de proximité.',
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
    modeLight() {
      return this.$config.app.modeLight
        ? JSON.parse(this.$config.app.modeLight)
        : false
    },
    isFranceConnectActive() {
      return this.$config.franceConnect
        ? JSON.parse(this.$config.franceConnect)
        : false
    },
  },
  methods: {
    handleServiceCiviqueChange() {
      if (this.form.service_civique) {
        this.$confirm(
          'Je suis actuellement sous contrat de Service Civique et certifie sur l’honneur que ma mission de Service Civique est, en accord avec mon organisme d’accueil en Service Civique, actuellement en tout ou partie suspendue dans le cadre des mesures prises pour la lutte contre la propagation du virus Covid-19.<br><br> Je me déclare bénévole pour rejoindre JeVeuxAider jusqu’à la date de reprise, à temps complet, de ma mission au sein de mon organisme d’accueil en Service Civique.<br>',
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
      this.$refs.registerVolontaireForm.validate((valid) => {
        if (valid) {
          const birthdayValidFormat = this.$dayjs(
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
              window.plausible('Inscription depuis la page inscription')
              this.loading = false
              if (this.$route.query.redirect) {
                this.$router.push(this.$route.query.redirect)
              } else {
                this.$router.push('/register/volontaire/step/profile')
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
::v-deep .el-form-item
  @apply mb-3

::v-deep .el-input,
::v-deep .el-input input
  @apply rounded-lg

::v-deep .el-checkbox
  @apply text-gray-500 font-normal
</style>
