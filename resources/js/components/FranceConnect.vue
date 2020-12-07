<template>
  <div @click="handleClickFranceConnect">
    <input type="image" src="/images/franceconnect-login.svg" alt="Soumettre" />
  </div>
</template>

<script>
import { fcLoginAuthorize } from '@/api/auth.js'

export default {
  methods: {
    handleClickFranceConnect() {
      fcLoginAuthorize().then((res) => {
        window.location.href = res.data
      })
    },
  },
}
</script>
