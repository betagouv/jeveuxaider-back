<template>
  <div class="relative z-10">
    <h2
      class="text-3xl md:text-5xl text-white !leading-tight tracking-tight font-bold text-center"
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
      <div
        class="bg-white w-72 h-64 m-4 flex-col items-center justify-center text-center px-4 py-10 rounded-2xl transform cursor-pointer hover:scale-105 duration-150"
        @click="handleChooseOrgaType('Association')"
      >
        <p class="text-4xl mb-0">💪</p>
        <p class="text-2xl leading-tight">
          Une<br /><span class="font-bold">association</span>
        </p>
        <p class="text-gray-500 text-sm leading-tight">
          Trouver des bénévoles<br />
          pour vos missions
        </p>
      </div>
      <div
        class="bg-white w-72 h-64 m-4 flex-col items-center justify-center text-center px-4 py-10 rounded-2xl transform cursor-pointer hover:scale-105 duration-150"
        @click="handleChooseOrgaType('Collectivité')"
      >
        <p class="text-4xl mb-0">🏫️</p>
        <p class="text-2xl leading-tight">
          Une
          <span class="font-bold">collectivité territoriale</span> ou un
          <span class="font-bold">CCAS</span>
        </p>
        <p class="text-gray-500 text-sm leading-tight">
          Mairies, CCAS, EPCI, départements, régions
        </p>
      </div>
      <div
        class="bg-white w-72 h-64 m-4 flex-col items-center justify-center text-center px-4 py-10 rounded-2xl transform cursor-pointer hover:scale-105 duration-150"
        @click="handleChooseOrgaType('Tête de réseau')"
      >
        <p class="text-4xl mb-0">🚀</p>
        <p class="text-2xl leading-tight">
          Une <span class="font-bold">tête de<br />réseau</span>
        </p>
        <p class="text-gray-500 text-sm leading-tight">
          Gérer vos différentes antennes, <br />délégations, associations
          locales ...
        </p>
      </div>
      <div
        class="bg-white w-72 h-64 m-4 flex-col items-center justify-center text-center px-4 py-10 rounded-2xl transform cursor-pointer hover:scale-105 duration-150"
        @click="handleChooseOrgaType('Organisation publique')"
      >
        <p class="text-4xl mb-0">🏢</p>
        <p class="text-2xl leading-tight">
          Autre organisation <br /><span class="font-bold">publique</span>
        </p>
        <p class="text-gray-500 text-sm leading-tight">
          Ehpad public, <br />services de l’Etat ...
        </p>
      </div>
      <div
        class="bg-white w-72 h-64 m-4 flex-col items-center justify-center text-center px-4 py-10 rounded-2xl transform cursor-pointer hover:scale-105 duration-150"
        @click="handleChooseOrgaType('Organisation privée')"
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
      </div>
      <a
        href="https://go.crisp.chat/chat/embed/?website_id=4b843a95-8a0b-4274-bfd5-e81cbdc188ac"
        target="_blank"
        class="bg-white w-72 h-64 m-4 px-4 py-10 flex-col items-center justify-center text-center rounded-2xl transform cursor-pointer hover:scale-105 duration-150"
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
        v-if="!userHasOrganisation"
        ref="registerResponsableForm"
        :model="form"
        label-position="top"
        :hide-required-asterisk="true"
        class="max-w-2xl mx-auto bg-gray-100 p-6 sm:p-12 rounded-2xl"
      >
        <div class="w-full m-0">
          <label
            class="uppercase font-semibold text-[#242526] text-sm mb-2 block"
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
            :show-add-button="!orgaExist"
            :loading-add-button="loading"
            @selected="onStructureApiSelected"
            @change="orgaExist = null"
            @added="onSubmitChooseName"
          />
          <el-input
            v-else
            v-model="form.structure.name"
            :placeholder="
              $route.query.orga_type === 'Collectivité'
                ? 'Nom de votre collectivité'
                : 'Nom de votre organisation'
            "
          />
        </div>
        <template v-if="!orgaExist && $route.query.orga_type !== 'Association'">
          <el-button
            type="primary"
            class="!w-full !flex !justify-center !p-4 !border !border-transparent !rounded-lg !shadow-lg !text-lg !font-bold !text-white !bg-jva-green hover:!shadow-lg hover:!scale-105 !transform !transition !mt-8 !leading-none"
            @click="onSubmitChooseName"
            @keyup.enter="onSubmitChooseName"
          >
            Continuer
          </el-button>
        </template>
        <div v-if="orgaExist" class="text-center mt-4">
          <p class="mb-0 font-bold">
            L'organisation
            <span class="text-primary">{{ orgaExist.structure_name }}</span>
            est déjà inscrite sur la plateforme.
          </p>
          <p class="text-gray-500 text-sm">
            <template v-if="orgaExist.responsable_fullname">
              Veuillez vous rapprocher de la personne suivante pour intégrer
              l'équipe :<br />
              <span class="text-black">{{
                orgaExist.responsable_fullname
              }}</span>
            </template>
            <template v-else>
              Merci de contacter notre support pour plus de détails.
            </template>
          </p>
        </div>
      </el-form>

      <div v-else class="max-w-2xl mx-auto bg-gray-100 p-6 sm:p-12 rounded-2xl">
        <div class="mb-6">
          Vous êtes déjà responsable de l'organisation
          <span class="font-bold">{{ userHasOrganisation.name }}</span>
        </div>
        <nuxt-link to="/register/responsable/step/profile">
          <el-button
            type="primary"
            class="!w-full !flex !justify-center !p-4 !border !border-transparent !rounded-lg !shadow-lg !text-lg !font-bold !text-white !bg-jva-green hover:!shadow-lg hover:!scale-105 !transform !transition !mt-8 !leading-none"
          >
            Continuer
          </el-button>
        </nuxt-link>
      </div>
    </div>

    <div v-else-if="currentStep.key == 'form_utilisateur'" class="mt-4">
      <el-form
        ref="registerResponsableForm"
        :model="form"
        label-position="top"
        :hide-required-asterisk="true"
        :rules="rules"
        class="form-register-steps max-w-xl mx-auto bg-gray-100 p-6 sm:p-12 rounded-2xl"
      >
        <div class="flex flex-wrap -mx-2">
          <el-form-item
            label="Prénom"
            prop="first_name"
            class="!w-full sm:!w-1/2 !px-2"
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
            class="!w-full sm:!w-1/2 !px-2"
          >
            <el-input
              v-model="form.last_name"
              label="Nom"
              autocomplete="new-password"
              placeholder="Nom"
            />
          </el-form-item>
          <el-form-item label="E-mail" prop="email" class="!w-full !px-2">
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
            class="!w-full sm:!w-1/2 !px-2"
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
            class="!w-full sm:!w-1/2 !px-2"
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
          class="!w-full !flex !justify-center !p-4 !border !border-transparent !rounded-lg !shadow-lg !text-lg !font-bold !text-white !bg-jva-green hover:!shadow-lg hover:!scale-105 !transform !transition !mt-4 !leading-none"
          @click="onSubmitRegisterResponsableForm"
          @keydown.enter="onSubmitRegisterResponsableForm"
        >
          <template v-if="$route.query.orga_type === 'Collectivité'">
            J'inscris ma collectivité
          </template>
          <template v-else-if="$route.query.orga_type === 'Association'">
            J'inscris mon association
          </template>
          <template v-else>J'inscris mon organisation</template>
        </el-button>
        <div class="mt-4 text-center text-[#242526] text-sm">
          En m'inscrivant j'accepte
          <a
            href="/politique-de-confidentialite"
            target="_blank"
            class="underline"
          >
            la politique de confidentialité
          </a>
          et
          <a href="/charte-reserve-civique" target="_blank" class="underline">
            la charte de JeVeuxAider.gouv.fr
          </a>
        </div>
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
      orgaExist: null,
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
    userHasOrganisation() {
      if (!this.$store.getters.profile) {
        return false
      }
      return this.$store.getters.profile.structures.length
        ? this.$store.getters.profile.structures[0]
        : null
    },
  },
  methods: {
    handleChooseOrgaType(orgaType) {
      this.$router.push(`/inscription/organisation?orga_type=${orgaType}`)
    },
    async onStructureApiSelected(structure) {
      const res = await this.$api.structureExists(structure._id)

      if (res.data) {
        this.orgaExist = res.data
      } else {
        this.form.structure = structure
        if (this.$store.getters.isLogged) {
          this.registerStructure()
        } else {
          this.currentStepKey = 'form_utilisateur'
        }
      }
    },
    async onSubmitChooseName() {
      if (!this.form.structure.name || this.form.structure.name.trim() == '') {
        this.$message.error({
          message: 'Merci de saisir un nom',
        })
        return
      }
      if (this.$route.query.orga_type === 'Collectivité') {
        const res = await this.$api.structureExists(this.form.structure.name)
        if (res.data) {
          this.orgaExist = res.data
          return false
        }
      }
      if (this.$store.getters.isLogged) {
        this.registerStructure()
      } else {
        this.currentStepKey = 'form_utilisateur'
      }
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
    registerStructure() {
      this.loading = true
      this.form.structure.statut_juridique = this.$route.query.orga_type

      this.$api
        .addStructure({
          name: this.form.structure.name,
          statut_juridique: this.$route.query.orga_type,
          structure_api: this.form.structure.rna ? this.form.structure : null,
        })
        .then(async () => {
          this.loading = false
          await this.$store.dispatch('auth/fetchUser')
          this.$router.push('/register/responsable/step/structure')
        })
        .catch(() => {
          this.loading = false
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
