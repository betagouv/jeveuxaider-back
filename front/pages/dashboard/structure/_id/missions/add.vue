<template>
  <div class="px-12 pb-12 max-w-7xl">
    <div class="flex justify-between">
      <div>
        <div class="text-gray-900 text-3xl font-extrabold">
          Création d'une nouvelle mission
        </div>
        <div class="text-gray-500 mt-2">
          Choisissez le domaine d'action de cette mission
        </div>
      </div>
      <div class="text-xs text-gray-500">
        Votre mission doit respecter <br />
        <nuxt-link class="text-primary" to="/charte-reserve-civique"
          >la charte</nuxt-link
        >
        de Jeveuxaider.gouv.fr
      </div>
    </div>

    <div v-if="step == 1" class="flex flex-wrap -m-2 mt-8">
      <div
        v-for="domaine in domaines"
        :key="domaine.id"
        class="shadow-lg rounded-lg w-60 py-6 px-16 text-center flex flex-col items-center justify-center m-2 font-bold cursor-pointer"
        :class="[
          domaine.id == $route.query.domaine
            ? 'bg-primary text-white'
            : 'hover:bg-primary hover:text-white',
        ]"
        @click="onclickDomaine(domaine.id)"
      >
        <div class="text-4xl mb-2">{{ iconeDomaine(domaine) }}</div>
        <div class="">{{ domaine.name.fr }}</div>
      </div>
    </div>

    <div v-if="step == 1 && domaine_id" class="border-t mt-10">
      <h2 class="font-bold mt-8 text-lg">Sélectionnez un modèle de mission</h2>
      <div class="text-gray-500 mt-2 mb-6">
        En utilisant un modèle déjà existant, votre mission sera publiée sans
        besoin de validation.
      </div>
      <div class="grid grid-cols-4 gap-6">
        <CardMissionTemplate
          title="Personnalisez votre mission"
          description="Tous les champs sont éditables. Elle sera publiée après validation par le référent départemental."
          image-url="/images/card-add.png"
          state-text="Validation par un référent"
          state-style="warning"
          @click.native="onSelectTemplate()"
        />
        <CardMissionTemplate
          v-for="missionTemplate in templates"
          :key="missionTemplate.id"
          :title="missionTemplate.title"
          :description="missionTemplate.subtitle"
          :image-url="
            (missionTemplate.photo && missionTemplate.photo.large) ||
            `/images/templates/${missionTemplate.id}.jpg`
          "
          @click.native="onSelectTemplate(missionTemplate.id)"
        />
      </div>
    </div>

    <FormMission
      v-if="step == 2"
      :mission="form"
      :structure-id="parseInt($route.params.id)"
      class="mt-8"
    />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params, store, error }) {
    const domaines = await $api.fetchTags({ 'filter[type]': 'domaine' })
    const structure = await $api.getStructure(params.id)
    return {
      domaines: domaines.data.data,
      structure,
    }
  },
  data() {
    return {
      step: this.$route.query.step || 1,
      template_id: null,
      domaine_id: parseInt(this.$route.query.domaine) || null,
      templates: [],
      form: {},
    }
  },
  async fetch() {
    this.domaine_id = parseInt(this.$route.query.domaine) || null
    this.template_id = parseInt(this.$route.query.template) || null

    if (this.domaine_id) {
      const templates = await this.$api.fetchMissionTemplates({
        'filter[domaine.id]': this.domaine_id,
        'filter[published]': 1,
        'filter[with_reseaux]': this.structure.reseaux
          ? this.structure.reseaux.map((reseau) => reseau.id).join(',')
          : null,
        pagination: 99,
      })
      this.templates = templates.data.data
    }
  },
  watch: {
    '$route.query': '$fetch',
  },
  methods: {
    onclickDomaine(domaineId) {
      this.$router.push(
        `/dashboard/structure/${this.$route.params.id}/missions/add?domaine=${domaineId}`
      )
    },
    onSelectTemplate(templateId) {
      if (templateId) {
        this.$router.push(
          `/dashboard/structure/${this.$route.params.id}/missions/add?step=2&template=${templateId}`
        )
      } else {
        this.$router.push(
          `/dashboard/structure/${this.$route.params.id}/missions/add?step=2&domaine=${this.domaine_id}`
        )
      }
    },
    iconeDomaine(domaine) {
      switch (domaine.id) {
        case 1: // Mobilisation Covid-19
          return '😷'
        case 2: // Éducation pour tous
          return '📚'
        case 3: // Santé pour tous
          return '💊'
        case 4: // Protection de la nature
          return '🌿'
        case 6: // Solidarité et insertion
          return '🍲'
        case 7: // Sport pour tous
          return '🏀'
        case 8: // Prévention et protection
          return '🚨'
        case 9: // Mémoire et citoyenneté
          return '📯'
        case 10: // Coopération internationale
          return '🌍'
        case 11: // Art & Culture pour tous
          return '🎨'
      }
    },
  },
}
</script>
