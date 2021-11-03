<template>
  <div class="lg:text-sm text-[#161616] h-full">
    <MenuAnonymous v-if="!$store.getters.isLogged" />
    <MenuLogged v-else />
  </div>
</template>

<script>
export default {}
</script>
