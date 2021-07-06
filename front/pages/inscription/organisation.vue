<template>
  <div class="relative z-10">
    <h2
      class="text-3xl md:text-5xl text-white leading-tight tracking-tight font-bold text-center"
      v-html="currentStep.title"
    />
    <div
      class="text-xl md:text-3xl text-white mt-7 tracking-tight text-center"
      v-html="currentStep.subtitle"
    />

    <div
      v-if="currentStep.key == 'choix_orga_type'"
      class="max-w-5xl flex flex-col flex-wrap items-center justify-center mt-10 mb-12 md:flex-row mx-auto"
    >
      <nuxt-link
        class="bg-white w-72 h-64 m-4 flex-col items-center justify-center text-center px-4 py-10 rounded-xl transform cursor-pointer hover:scale-105 duration-150"
        to="?orga_type=association"
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
      <div
        class="bg-white w-72 h-64 m-4 flex-col items-center justify-center text-center px-4 py-10 rounded-xl transform cursor-pointer hover:scale-105 duration-150"
      >
        <p class="text-4xl mb-0">🏫️</p>
        <p class="text-2xl leading-tight">
          Une <span class="font-bold">collectivité territoriale</span>
        </p>
        <p class="text-gray-500 text-sm leading-tight">
          Mairies, départements,<br />
          régions et EPCI
        </p>
      </div>
      <div
        class="bg-white w-72 h-64 m-4 flex-col items-center justify-center text-center px-4 py-10 rounded-xl transform cursor-pointer hover:scale-105 duration-150"
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
        class="bg-white w-72 h-64 m-4 flex-col items-center justify-center text-center px-4 py-10 rounded-xl transform cursor-pointer hover:scale-105 duration-150"
      >
        <p class="text-4xl mb-0">🏢</p>
        <p class="text-2xl leading-tight">
          Autre organisation <br /><span class="font-bold">publique</span>
        </p>
        <p class="text-gray-500 text-sm leading-tight">
          CCAS, Ehpad public, <br />services de l’Etat ...
        </p>
      </div>
      <div
        class="bg-white w-72 h-64 m-4 flex-col items-center justify-center text-center px-4 py-10 rounded-xl transform cursor-pointer hover:scale-105 duration-150"
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
      <div
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
      </div>
    </div>

    <div v-else-if="currentStep.key == 'choix_nom_asso'" class="mt-10">
      <el-form
        ref="registerResponsableForm"
        :model="form"
        label-position="top"
        :hide-required-asterisk="true"
        class="max-w-2xl mx-auto bg-gray-100 p-12 rounded-xl"
      >
        <div class="w-full m-0">
          <label
            class="uppercase font-semibold text-gray-800 text-sm mb-2 block"
          >
            Nom de votre association
          </label>
          <StructureApiSearchInput
            v-model="form.structure.name"
            placeholder="Nom de votre association"
            @selected="onStructureApiSelected"
            @clear="structureApi = null"
          />
          <div v-if="structureApi" class="text-xs text-gray-400 leading-tight">
            RNA: {{ structureApi.rna }}
          </div>
        </div>
      </el-form>
    </div>

    <div v-else-if="currentStep.key == 'form_utilisateur'">
      <el-form
        ref="registerVolontaireForm"
        :model="form"
        label-position="top"
        :hide-required-asterisk="true"
        class="form-register-steps max-w-2xl mx-auto bg-gray-100 p-12 rounded-xl"
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
          <el-form-item label="E-mail" prop="email" class="w-full sm:w-1/2 p-2">
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
              placeholder="Code postal"
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
        </div>
      </el-form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'InscriptionOrganisation',
  layout: 'header-only',
  middleware: 'guest',
  data() {
    return {
      currentStepKey:
        this.$route.query.orga_type === 'association'
          ? 'choix_nom_asso'
          : 'choix_orga_type',
      form: {
        structure: {},
      },
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
          key: 'choix_nom_asso',
          title: 'Votre association est <br /> la bienvenue chez nous !',
          subtitle: 'Quel est son petit nom ?',
        },
        {
          key: 'form_utilisateur',
          title: "On n'attendait plus que vous, chez Codeconut",
          subtitle: 'Et vous dans tout ça ?',
        },
      ]
    },
    currentStep() {
      return this.steps.find((step) => step.key == this.currentStepKey)
    },
  },
  methods: {
    onStructureApiSelected(structure) {
      this.form.structure = structure
      this.currentStepKey = 'form_utilisateur'
    },
  },
}
</script>
