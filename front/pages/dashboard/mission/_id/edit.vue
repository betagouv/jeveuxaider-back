<template>
  <div class="pl-12 pb-12 max-w-3xl">
    <div class="text-m text-gray-600 uppercase">Mission</div>
    <div class="flex gap-3 justify-between items-start">
      <div class="font-bold text-2-5xl text-gray-800">
        {{ mission.name }}
      </div>
      <TagModelState
        :state="mission.state"
        size=""
        class="relative"
        style="top: 1px"
      />
    </div>

    <div
      v-if="!['En attente de validation', 'Signalée'].includes(mission.state)"
      class="font-light text-gray-600 flex items-center"
    >
      <div
        :class="
          mission.structure.state == 'Validée' &&
          ['Validée', 'Terminée'].includes(mission.state)
            ? 'bg-green-500'
            : 'bg-red-500'
        "
        class="rounded-full h-2 w-2 mr-2"
      ></div>

      <nuxt-link
        target="_blank"
        :to="`/missions-benevolat/${mission.id}/${mission.slug}`"
      >
        <span class="text-sm underline hover:no-underline">
          {{ $config.appUrl }}/missions-benevolat/{{ mission.id }}/{{
            mission.slug
          }}
        </span>
      </nuxt-link>
    </div>

    <hr class="border-gray-200 my-4" />

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
