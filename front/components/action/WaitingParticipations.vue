<template>
  <nuxt-link
    :to="`/dashboard/structure/${action.structure.id}/participations?filter[state]=En attente de validation`"
    class="px-5 py-3 flex hover:bg-gray-50 cursor-pointer items-center justify-between"
  >
    <div class="flex-1 flex space-x-4">
      <div class="">
        <div class="">
          Vous avez
          <span class="font-semibold">{{ action.value }} participation(s)</span>
          en attente de validation
        </div>
        <div class="text-sm text-gray-500">
          Des bénévoles souhaitent aider
          {{ action.structure.name }}
        </div>
      </div>
    </div>
  </nuxt-link>
</template>

<script>
export default {
  props: {
    action: {
      type: Object,
      required: true,
    },
  },
}
</script>
