<template>
  <div class="pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">
      Création d'une nouvelle mission
    </div>
    <div class="mb-8 flex">
      <div class="font-bold text-2xl text-gray-800">
        <template v-if="step == 1"
          >Choisissez le domaine d'action de cette mission</template
        >
        <template v-else-if="step == 2"
          >Choisissez le type de modèle de cette mission</template
        >
        <template v-else>
          <template v-if="template_id"
            >Publier une mission à partir d'un modèle</template
          >
          <template v-else>Rédiger intégralement une mission</template>
        </template>
      </div>
    </div>
    <template v-if="step == 1">
      <FormMissionSelectDomaine
        :domaines="domaines"
        @selected="onSelectDomaine"
      />
    </template>
    <template v-if="step == 2">
      <FormMissionSelectTemplate
        :domaine-id="domaine_id"
        :domaines="domaines"
        :templates="templates"
        @change-domaine="onSelectDomaine"
        @selected="onSelectTemplate"
      />
    </template>
    <template v-if="step == 3">
      <FormMission :mission="form" :structure-id="parseInt($route.params.id)" />
    </template>
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
    this.step = this.$router.history.current.query.step || 1
    this.domaine_id =
      parseInt(this.$router.history.current.query.domaine) || null
    this.template_id =
      parseInt(this.$router.history.current.query.template) || null

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
    onSelectDomaine(domaineId) {
      this.$router.push(
        `/dashboard/structure/${this.$route.params.id}/missions/add?step=2&domaine=${domaineId}`
      )
    },
    async onSelectTemplate(template) {
      const structure = await this.$api.getStructure(this.$route.params.id)
      this.form = {
        domaine_id: template ? null : this.domaine_id,
        template_id: template ? template.id : null,
        template: template || null,
        state: template ? 'Validée' : 'En attente de validation', // TODO : cette logique devrait être côté PHP setState()
        responsable_id: parseInt(this.$store.getters.user.profile.id),
        structure_id: parseInt(this.$route.params.id),
        structure,
      }
      this.$router.push(
        `/dashboard/structure/${
          this.$route.params.id
        }/missions/add?step=3&template=${template ? template.id : null}`
      )
    },
  },
}
</script>
