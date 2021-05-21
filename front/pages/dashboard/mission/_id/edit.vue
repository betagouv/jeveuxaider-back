<template>
  <div class="pl-12 pb-12 max-w-3xl">
    <div class="text-m text-gray-600 uppercase">Mission</div>
    <div class="mb-8 pb-4 flex border-b border-gray-200">
      <div class="font-bold text-2-5xl text-gray-800">
        {{ mission.name }}
      </div>
      <TagModelState
        :state="mission.state"
        class="relative ml-3"
        style="top: 1px"
      />
    </div>
    <FormMission :mission="mission" :structure-id="mission.structure_id" />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params, store, error }) {
    if (
      ![
        'admin',
        'referent',
        'referent_regional',
        'superviseur',
        'responsable',
      ].includes(store.getters.contextRole)
    ) {
      return error({ statusCode: 403 })
    }

    const mission = await $api.getMission(params.id)

    if (!mission) {
      return error({ statusCode: 404 })
    }

    if (store.getters.contextRole == 'responsable') {
      if (store.getters.structure.id != mission.structure_id) {
        return error({ statusCode: 403 })
      }
    }

    mission.tags = mission.tags.map((tag) => tag.name.fr)
    return {
      mission,
    }
  },
  methods: {},
}
</script>
