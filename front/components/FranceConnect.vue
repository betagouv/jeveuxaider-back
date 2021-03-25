<template>
  <div>
    <div class="cursor-pointer" @click="handleClickFranceConnect">
      <img
        class="hover:opacity-75 mx-auto w-auto h-16 mb-1 shadow-lg rounded-lg overflow-hidden transform transition duration-150 ease-in-out"
        :src="
          isDark
            ? '/images/franceconnect-blue.svg'
            : '/images/franceconnect.svg'
        "
        alt="Soumettre"
      />
    </div>
    <a
      href="https://franceconnect.gouv.fr/"
      target="_blank"
      rel="noopener"
      class="text-sm"
      :class="
        isDark
          ? 'text-blue-600 hover:text-blue-800'
          : 'text-blue-200 hover:text-white'
      "
      >Qu'est-ce que FranceConnect ?</a
    >
  </div>
</template>

<script>
export default {
  props: {
    isDark: {
      type: Boolean,
      default: false,
    },
  },
  created() {
    if (this.$route.query.state && this.$route.query.code) {
      this.$emit('loading', true)
      this.$api
        .franceConnectLoginCallback({
          state: this.$route.query.state,
          code: this.$route.query.code,
        })
        .then((response) => {
          this.$cookies.set('access-token', response.data.accessToken, {
            maxAge: 3600 * 24 * 365,
            path: '/',
          })
          this.$store.commit('auth/setAccessToken', response.data.accessToken)
          this.$store.dispatch('auth/fetchUser').then(() => {
            if (this.$store.getters.noRole === false) {
              this.$router.push('/dashboard')
            } else {
              this.$router.push('/missions-benevolat')
            }
          })
        })
    }
  },
  methods: {
    handleClickFranceConnect() {
      console.log('click go')
      this.$api.franceConnectLoginAuthorize().then((res) => {
        window.location.href = res.data
      })
    },
  },
}
</script>
