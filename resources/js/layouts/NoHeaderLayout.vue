<template>
  <div class="bg-gray-100">
    <router-view :key="$route.fullPath" />
    <AppFooter />
  </div>
</template>

<script>
export default {
  name: 'NoHeaderLayout',
}
</script>
