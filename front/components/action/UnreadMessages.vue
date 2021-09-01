<template>
  <nuxt-link
    to="/messages"
    class="px-5 py-3 flex hover:bg-gray-50 cursor-pointer items-center justify-between"
  >
    <div class="flex-1 flex space-x-4">
      <div class="">
        <div class="">
          Vous avez
          <span class="font-semibold"
            >{{ action.value }} message(s) non lu(s)</span
          >
        </div>
        <div class="text-sm text-gray-500">Restez à l'écoute des bénévoles</div>
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
