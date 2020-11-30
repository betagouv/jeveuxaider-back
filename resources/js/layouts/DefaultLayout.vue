<template>
  <div class="bg-gray-100">
    <AppHeader />
    <router-view :key="$route.fullPath" />
    <AppFooter />
  </div>
</template>

<script>
export default {
  name: 'DefaultLayout',
}
</script>
