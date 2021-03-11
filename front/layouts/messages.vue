<template>
  <div class="bg-gray-100 h-full flex flex-col">
    <AppHeader />
    <Nuxt />
  </div>
</template>

<script>
export default {
  name: 'MessagesLayout',
  middleware: 'logged',
}
</script>
