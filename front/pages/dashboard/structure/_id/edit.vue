<template>
  <div class="structure-form pl-12 pb-12 max-w-3xl">
    <div class="text-m text-gray-600 uppercase">Organisation</div>

    <div class="mb-8 pb-4 border-b border-gray-200">
      <div class="flex items-center">
        <div class="font-bold text-2-5xl text-gray-800">
          {{ structure.name }}
        </div>
        <TagModelState
          :state="structure.state"
          class="relative ml-3"
          style="top: 1px"
        />
      </div>

      <div
        v-if="
          structure.statut_juridique == 'Association' &&
          structure.state == 'Validée'
        "
        class="mt-2 flex items-center"
      >
        <div class="mr-2 text-gray-450">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-4 w-4"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path
              fill-rule="evenodd"
              d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z"
              clip-rule="evenodd"
            />
          </svg>
        </div>
        <nuxt-link target="_blank" :to="`/organisations/${structure.slug}`">
          <span class="text-sm underline hover:no-underline">
            {{ $config.appUrl }}/organisations/{{ structure.slug }}
          </span>
        </nuxt-link>
      </div>
    </div>

    <FormStructure :structure="structure" :domaines="domaines" />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params }) {
    const structure = await $api.getStructure(params.id)
    const tags = await $api.fetchTags({ 'filter[type]': 'domaine' })
    return {
      structure,
      domaines: tags.data.data,
    }
  },
  methods: {},
}
</script>
