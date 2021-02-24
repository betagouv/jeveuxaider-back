<template>
  <div v-if="!loading" class="structure-view">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Collectivité</div>
        <div class="flex flex-wrap mb-8">
          <div class="font-bold text-2xl text-gray-800 mr-2">
            Page de retour prochainement
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DashboardCollectivityResponsableStats',

  data() {
    return {
      loading: false,
    }
  },
}
</script>

<style scoped lang="sass"></style>
