<template>
  <div class="cursor-pointer" @click="handleClickFranceConnect">
    <img class="h-16" src="/images/franceconnect-login.svg" alt="Soumettre" />
  </div>
</template>

<script>
import {
  franceConnectLoginAuthorize,
  franceConnectLoginCallback,
} from '@/api/auth.js'

export default {
  created() {
    if (this.$route.query.state && this.$route.query.code) {
      this.$emit('loading', true)
      franceConnectLoginCallback({
        state: this.$route.query.state,
        code: this.$route.query.code,
      }).then((response) => {
        this.$store.commit('auth/setTokens', {
          access_token: response.data.accessToken,
        })
        this.$store.dispatch('user/get').then(() => {
          if (this.$store.getters.noRole === false) {
            this.$router.push('/dashboard')
          } else {
            this.$router.push('/missions')
          }
        })
      })
    }
  },
  methods: {
    handleClickFranceConnect() {
      franceConnectLoginAuthorize().then((res) => {
        window.location.href = res.data
      })
    },
  },
}
</script>
