<template>
  <div />
</template>

<script>
export default {
  name: 'Logout',
  created() {
    this.$store.dispatch('auth/logout')
  },
}
</script>
