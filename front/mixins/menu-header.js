export default {
  data() {
    return {
      wrapperLinkClasses: `p-2 lg:px-4 lg:truncate lg:space-x-6 flex flex-col lg:flex-row`,
      linkClasses: `inline-flex p-2 hover:underline active:underline cursor-pointer`,
    }
  },
  methods: {
    handleLogout() {
      this.closeMobileMenu()
      this.$router.push('/')
      this.$store.dispatch('auth/logout')
    },
  },
}
