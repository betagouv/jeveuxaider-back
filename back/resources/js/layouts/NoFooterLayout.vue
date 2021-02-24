<template>
  <div class="bg-gray-100">
    <AppHeader />
    <router-view :key="$route.fullPath" />
  </div>
</template>

<script>
export default {
  name: 'NoFooterLayout',
}
</script>
