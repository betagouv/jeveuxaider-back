<template>
  <div
    class="
      px-5
      py-3
      flex
      hover:bg-gray-50
      cursor-pointer
      items-center
      justify-between
    "
  >
    <div class="flex-1 flex space-x-4">
      <div class="">
        <div class="">
          Vous avez
          <span class="font-semibold">{{ action.value }} mission(s)</span> dont
          la date de fin est passée
        </div>
        <div class="text-sm text-gray-500">
          Penser à terminer les missions de {{ action.structure.name }}
        </div>
      </div>
      <div class="">CTA</div>
    </div>
  </div>
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
