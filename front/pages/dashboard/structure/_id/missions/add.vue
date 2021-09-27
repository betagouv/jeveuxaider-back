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

    <div v-if="!template_id" class="flex flex-wrap -m-2 mt-8">
      <div
        v-for="domaine in domaines"
        :key="domaine.id"
        class="shadow-lg rounded-lg w-60 py-6 px-16 text-center flex items-center justify-center m-2 font-bold cursor-pointer"
        :class="[
          domaine.id == $route.query.domaine
            ? 'bg-primary text-white'
            : 'hover:bg-primary hover:text-white',
        ]"
        @click="onclickDomaine(domaine.id)"
      >
        {{ domaine.name.fr }}
      </div>
    </div>

    <div v-if="!template_id" class="border-t mt-10">
      <h2 class="font-bold mt-8 text-lg">Sélectionnez un modèle de mission</h2>
      <div class="text-gray-500 mt-2 mb-6">
        En utilisant un modèle déjà existant, votre mission sera publiée sans
        besoin de validation.
      </div>
      <div class="grid grid-cols-4 gap-6">
        <CardMissionTemplate
          v-for="missionTemplate in templates"
          :key="missionTemplate.id"
          :title="missionTemplate.title"
          :description="missionTemplate.subtitle"
          :image-url="
            (missionTemplate.photo && missionTemplate.photo.large) ||
            '/images/card-thumbnail-default@2x.jpg'
          "
          @click.native="onSelectTemplate(missionTemplate.id)"
        />
      </div>
    </div>

    <FormMission
      v-if="template_id"
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
    return {
      domaines: domaines.data.data,
    }
  },
  data() {
    return {
      step: null,
      template_id: null,
      domaine_id: null,
      templates: [],
      form: {},
    }
  },
  async fetch() {
    this.step = this.$route.query.step || 1
    this.domaine_id = parseInt(this.$route.query.domaine) || null
    this.template_id = parseInt(this.$route.query.template) || null

    if (this.domaine_id) {
      const templates = await this.$api.fetchMissionTemplates({
        'filter[domaine.id]': this.domaine_id,
        'filter[published]': 1,
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
          `/dashboard/structure/${this.$route.params.id}/missions/add?step=2`
        )
      }
    },
  },
}
</script>
