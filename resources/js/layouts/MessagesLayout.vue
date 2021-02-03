<template>
  <div class="bg-gray-100 h-full flex flex-col">
    <AppHeader />
    <router-view :key="$route.fullPath" />
  </div>
</template>

<script>
export default {
  name: 'MessagesLayout',
}
</script>
