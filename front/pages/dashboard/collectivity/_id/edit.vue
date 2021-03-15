<template>
  <div class="profile-form max-w-2xl pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">Collectivité</div>
    <div class="mb-8 flex">
      <div class="font-bold text-2xl">
        {{ collectivity.name }}
      </div>
    </div>

    <FormCollectivity :collectivity="collectivity" />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  // TODO MIDDLEWARE ADMIN OU RESPONSBALE DE LA COLLECTIVITY
  // beforeRouteEnter(to, from, next) {
  //   next((vm) => {
  //     if (vm.$store.getters.contextRole == 'admin') {
  //       return
  //     }
  //     if (
  //       vm.$store.getters.structure.collectivity.id !=
  //       to.params.id
  //     ) {
  //       vm.$router.push('/403')
  //     }
  //   })
  // },
  async asyncData({ $api, params, store, error }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
    const collectivity = await $api.getCollectivity(params.id)
    return {
      collectivity,
    }
  },
}
</script>
