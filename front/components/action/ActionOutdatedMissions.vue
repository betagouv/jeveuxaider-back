<template>
  <nuxt-link
    :to="`/dashboard/structure/${action.structure.id}/missions?filter[dates]=outdated&filter[state]=Validée`"
    class="px-5 py-3 flex hover:bg-gray-50 cursor-pointer items-center justify-between"
  >
    <div class="flex-1 flex space-x-4">
      <div class="">
        <div class="">
          Vous avez
          <span class="font-semibold">{{ action.value }} mission(s)</span> dont
          la date de fin est passée
        </div>
        <div class="text-sm text-gray-500">
          Pensez à terminer ou mettre à jour les missions de
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
