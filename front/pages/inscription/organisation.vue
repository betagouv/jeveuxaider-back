<template>
  <div class="relative z-10">
    <h2
      class="text-3xl md:text-5xl text-white leading-tight tracking-tight font-bold text-center"
      v-html="currentStep.title"
    />
    <div
      class="text-xl md:text-3xl text-white mt-2 tracking-tight text-center"
      v-html="currentStep.subtitle"
    />

    <div
      v-if="currentStep.key == 'choix_orga_type'"
      class="max-w-5xl flex flex-col flex-wrap items-center justify-center mt-4 mb-12 md:flex-row mx-auto"
    >
      <nuxt-link
        class="bg-white w-72 h-64 m-4 flex-col items-center justify-center text-center px-4 py-10 rounded-xl transform cursor-pointer hover:scale-105 duration-150"
        to="?orga_type=Association"
      >
        <p class="text-4xl mb-0">💪</p>
        <p class="text-2xl leading-tight">
          Une<br /><span class="font-bold">association</span>
        </p>
        <p class="text-gray-500 text-sm leading-tight">
          Trouver des bénévoles<br />
          pour vos missions
        </p>
      </nuxt-link>
      <nuxt-link
        class="bg-white w-72 h-64 m-4 flex-col items-center justify-center text-center px-4 py-10 rounded-xl transform cursor-pointer hover:scale-105 duration-150"
        to="?orga_type=Collectivité"
      >
        <p class="text-4xl mb-0">🏫️</p>
        <p class="text-2xl leading-tight">
          Une <span class="font-bold">collectivité territoriale</span>
        </p>
        <p class="text-gray-500 text-sm leading-tight">
          Mairies, départements,<br />
          régions et EPCI
        </p>
      </nuxt-link>
      <nuxt-link
        class="bg-white w-72 h-64 m-4 flex-col items-center justify-center text-center px-4 py-10 rounded-xl transform cursor-pointer hover:scale-105 duration-150"
        to="?orga_type=Tête de réseau"
      >
        <p class="text-4xl mb-0">🚀</p>
        <p class="text-2xl leading-tight">
          Une <span class="font-bold">tête de<br />réseau</span>
        </p>
        <p class="text-gray-500 text-sm leading-tight">
          Gérer vos différentes antennes, <br />délégations, associations
          locales ...
        </p>
      </nuxt-link>
      <nuxt-link
        class="bg-white w-72 h-64 m-4 flex-col items-center justify-center text-center px-4 py-10 rounded-xl transform cursor-pointer hover:scale-105 duration-150"
        to="?orga_type=Organisation publique"
      >
        <p class="text-4xl mb-0">🏢</p>
        <p class="text-2xl leading-tight">
          Autre organisation <br /><span class="font-bold">publique</span>
        </p>
        <p class="text-gray-500 text-sm leading-tight">
          CCAS, Ehpad public, <br />services de l’Etat ...
        </p>
      </nuxt-link>
      <nuxt-link
        class="bg-white w-72 h-64 m-4 flex-col items-center justify-center text-center px-4 py-10 rounded-xl transform cursor-pointer hover:scale-105 duration-150"
        to="?orga_type=Organisation privée"
      >
        <p class="text-4xl mb-0">🏩</p>
        <p class="text-2xl leading-tight">
          <span class="font-bold">Organisation privée</span><br />à but non
          lucratif
        </p>
        <p class="text-gray-500 text-sm leading-tight">
          Établissement de santé privé d'intérêt collectif, Ehpad privé,
          fondation, ESUS
        </p>
      </nuxt-link>
      <a
        href="mailto:nassim@jeuveuxaider.gouv.fr"
        class="bg-white w-72 h-64 m-4 px-4 py-10 flex-col items-center justify-center text-center rounded-xl transform cursor-pointer hover:scale-105 duration-150"
      >
        <p class="text-4xl mb-0">🤔</p>
        <p class="text-2xl leading-tight">
          Vous êtes<br />
          <span class="font-bold">perdu ?</span>
        </p>
        <p class="text-gray-500 text-sm leading-tight">
          Notre équipe se fera une joie<br />
          de vous guider :)
        </p>
      </a>
    </div>

    <div v-else-if="currentStep.key == 'choix_orga_nom'" class="mt-4">
      <el-form
        ref="registerResponsableForm"
        :model="form"
        label-position="top"
        :hide-required-asterisk="true"
        class="max-w-2xl mx-auto bg-gray-100 p-6 sm:p-12 rounded-xl"
      >
        <div class="w-full m-0">
          <label
            class="uppercase font-semibold text-gray-800 text-sm mb-2 block"
          >
            <template v-if="$route.query.orga_type === 'Collectivité'">
              Nom de votre collectivité
            </template>
            <template v-else-if="$route.query.orga_type === 'Association'">
              Nom de votre association
            </template>
            <template v-else>Nom de votre organisation</template>
          </label>
          <StructureApiSearchInput
            v-if="$route.query.orga_type === 'Association'"
            v-model="form.structure.name"
            placeholder="Nom de votre association"
            @selected="onStructureApiSelected"
            @clear="structureApi = null"
            @change="rnaExist = null"
            @added="onSubmitChooseName"
          >
          </StructureApiSearchInput>
          <el-input
            v-else
            v-model="form.structure.name"
            :placeholder="
              $route.query.orga_type === 'Collectivité'
                ? 'Nom de votre collectivité'
                : 'Nom de votre organisation'
            "
          />
          <div v-if="structureApi" class="text-xs text-gray-400 leading-tight">
            RNA: {{ structureApi.rna }}
          </div>
        </div>
        <template v-if="$route.query.orga_type !== 'Association'">
          <el-button
            v-if="!rnaExist"
            type="primary"
            class="w-full flex justify-center p-4 border border-transparent rounded-lg shadow-lg text-lg font-bold text-white bg-green-400 hover:shadow-lg hover:scale-105 transform transition duration-150 ease-in-out mt-8"
            @click="onSubmitChooseName"
          >
            Continuer
          </el-button>
          <div v-else class="text-center mt-4">
            <p class="mb-0 font-bold">
              L'association
              <span class="text-primary">{{ rnaExist.structure_name }}</span>
              est déjà inscrite sur la plateforme.
            </p>
            <p class="text-gray-500 text-sm">
              Veuillez vous rapprocher de la personne suivante pour intégrer
              l'équipe :<br />
              <span class="text-black">{{
                rnaExist.responsable_fullname
              }}</span>
            </p>
          </div>
        </template>
      </el-form>
    </div>

    <div v-else-if="currentStep.key == 'form_utilisateur'" class="mt-4">
      <el-form
        ref="registerResponsableForm"
        :model="form"
        label-position="top"
        :hide-required-asterisk="true"
        :rules="rules"
        class="form-register-steps max-w-xl mx-auto bg-gray-100 p-6 sm:p-12 rounded-xl"
      >
        <div class="flex flex-wrap -mx-2">
          <el-form-item
            label="Prénom"
            prop="first_name"
            class="w-full sm:w-1/2 px-2"
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
            class="w-full sm:w-1/2 px-2"
          >
            <el-input
              v-model="form.last_name"
              label="Nom"
              autocomplete="new-password"
              placeholder="Nom"
            />
          </el-form-item>
          <el-form-item label="E-mail" prop="email" class="w-full px-2">
            <el-input
              v-model.trim="form.email"
              label="E-mail"
              autocomplete="new-password"
              placeholder="E-mail"
            />
          </el-form-item>
          <el-form-item
            label="Mot de passe"
            prop="password"
            class="w-full sm:w-1/2 px-2"
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
            class="w-full sm:w-1/2 px-2"
          >
            <el-input
              v-model="form.password_confirmation"
              label="Confirmation mot de passe"
              placeholder="Confirmation mot de passe"
              show-password
            />
          </el-form-item>
        </div>
        <el-button
          type="primary"
          :loading="loading"
          class="w-full flex justify-center p-4 border border-transparent rounded-lg shadow-lg text-lg font-bold text-white bg-green-400 hover:shadow-lg hover:scale-105 transform transition duration-150 ease-in-out mt-4"
          @click="onSubmitRegisterResponsableForm"
        >
          <template v-if="$route.query.orga_type === 'Collectivité'">
            J'inscris ma collectivité
          </template>
          <template v-else-if="$route.query.orga_type === 'Association'">
            J'inscris mon association
          </template>
          <template v-else>J'inscris mon organisation</template>
        </el-button>
      </el-form>
    </div>

    <div v-else-if="currentStep.key == 'form_reseau'" class="mt-4">
      <FormLeadReseau />
    </div>
  </div>
</template>

<script>
import FormMixin from '@/mixins/Form'

export default {
  name: 'InscriptionOrganisation',
  mixins: [FormMixin],
  layout: 'header-only',
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
      currentStepKey:
        this.$route.query.orga_type === 'Association' ||
        this.$route.query.orga_type === 'Collectivité' ||
        this.$route.query.orga_type === 'Organisation publique' ||
        this.$route.query.orga_type === 'Organisation privée'
          ? 'choix_orga_nom'
          : this.$route.query.orga_type === 'Tête de réseau'
          ? 'form_reseau'
          : 'choix_orga_type',
      form: {
        structure: {},
      },
      rnaExist: null,
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
      loading: false,
      structureApi: null,
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
    steps() {
      return [
        {
          key: 'choix_orga_type',
          title: 'Excellent choix !',
          subtitle: 'Vous êtes...',
        },
        {
          key: 'choix_orga_nom',
          title:
            this.$route.query.orga_type === 'Collectivité'
              ? 'Voilà un grand pas<br /> pour votre collectivité !'
              : this.$route.query.orga_type === 'Association'
              ? 'Votre association est <br /> la bienvenue chez nous !'
              : 'Voilà un grand pas<br /> pour votre organisation !',
          subtitle: 'Quel est son petit nom ?',
        },
        {
          key: 'form_utilisateur',
          title:
            this.$route.query.orga_type === 'Association'
              ? `On n'attendait plus que vous,<br /> ${this.form.structure.name} !`
              : `Bienvenue parmi nous <br /> ${this.form.structure.name}`,
          subtitle:
            this.$route.query.orga_type === 'Association'
              ? 'Et vous dans tout ça ?'
              : 'Qui êtes-vous ?',
        },
        {
          key: 'form_reseau',
          title: 'Voilà un grand pas<br /> pour votre réseau !',
          subtitle: 'Quel est le nom de votre tête de réseau ?',
        },
      ]
    },
    currentStep() {
      return this.steps.find((step) => step.key == this.currentStepKey)
    },
  },
  methods: {
    async onStructureApiSelected(structure) {
      const res = await this.$api.structureExists(structure.rna)
      if (res.data) {
        this.rnaExist = res.data
      } else {
        this.form.structure = structure
        this.currentStepKey = 'form_utilisateur'
      }
    },
    onSubmitChooseName() {
      this.currentStepKey = 'form_utilisateur'
    },
    onSubmitRegisterResponsableForm() {
      this.loading = true
      this.$refs.registerResponsableForm.validate((valid, fields) => {
        if (valid) {
          this.$store
            .dispatch('auth/registerResponsable', {
              email: this.form.email,
              password: this.form.password,
              first_name: this.form.first_name,
              last_name: this.form.last_name,
              structure_name: this.form.structure.name,
              structure_statut_juridique: this.$route.query.orga_type,
              structure_api: this.form.structure.rna
                ? this.form.structure
                : null,
            })
            .then(() => {
              this.loading = false
              window.plausible &&
                window.plausible(
                  'Inscription responsable - Étape 1 - Création de compte'
                )
              this.$router.push('/register/responsable/step/profile')
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

<style scoped>
.el-form-item {
  margin-bottom: 26px;
}
</style>
