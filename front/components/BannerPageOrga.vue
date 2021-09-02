<template>
  <el-card shadow="never" class="p-5">
    <div class>
      {{ $store.getters.contextStructure.name }} a sa propre page sur
      JeVeuxAider.gouv.fr
    </div>
    <div class="text-gray-400 mt-3 mb-4">
      Éditez les informations de votre organisation pour compléter sa
      présentation.
    </div>
    <div class="flex mt-2">
      <nuxt-link :to="`/organisations/${$store.getters.contextStructure.slug}`">
        <el-button type="primary" class="!mr-2"> Consulter ma page </el-button>
      </nuxt-link>

      <nuxt-link
        :to="`/dashboard/structure/${$store.getters.contextStructure.id}/edit`"
      >
        <el-button type="primary">Éditer les informations</el-button>
      </nuxt-link>
    </div>
  </el-card>
</template>

<script>
export default {
  data() {
    return {}
  },
}
</script>
