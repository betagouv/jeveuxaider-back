import apiMission from '@/api/mission'

export default ({ $axios }, inject) => {
  // Inject `api` key
  // -> app.$api
  // -> this.$api in vue components
  // -> this.$api in store actions/mutations
  const api = {
    ...apiMission($axios),
  }

  inject('api', api)
}
