<template>
  <div class="structure-form px-12 pb-12">
    <div class="mb-8">
      <div class="flex justify-between">
        <div>
          <div class="text-m text-gray-600 uppercase">Réseau</div>

          <div class="font-bold text-[1.75rem] text-[#242526]">
            {{ reseau.name }}
          </div>
        </div>

        <nuxt-link :to="`/dashboard/reseaux/${$route.params.id}`">
          <el-button>Retour</el-button>
        </nuxt-link>
      </div>

      <div v-if="reseau.is_published" class="flex items-center">
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
        <nuxt-link target="_blank" :to="reseau.full_url">
          <span class="text-sm underline hover:no-underline break-all">
            {{ $config.appUrl }}{{ reseau.full_url }}
          </span>
        </nuxt-link>
      </div>
    </div>

    <FormReseau class="max-w-2xl" :reseau="reseau" />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, store, error, params }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
    const reseau = await $api.getReseau(params.id)
    return {
      reseau,
    }
  },
}
</script>
