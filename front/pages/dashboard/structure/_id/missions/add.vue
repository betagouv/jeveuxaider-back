<template>
  <div class="pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">
      Création d'une nouvelle mission
    </div>
    <div class="mb-8 flex">
      <div class="font-bold text-2-5xl text-[#242526]">
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
    onSelectDomaine(domaineId) {
      this.$router.push(
        `/dashboard/structure/${this.$route.params.id}/missions/add?step=2&domaine=${domaineId}`
      )
    },
    onSelectTemplate(template) {
      if (template) {
        this.$router.push(
          `/dashboard/structure/${this.$route.params.id}/missions/add?step=3&template=${template.id}`
        )
      } else {
        this.$router.push(
          `/dashboard/structure/${this.$route.params.id}/missions/add?step=3`
        )
      }
    },
  },
}
</script>
