import apiMission from '@/api/mission'
import apiStructure from '@/api/structure'
import apiParticipation from '@/api/participation'

export default ({ $axios }, inject) => {
  // Inject `api` key
  // -> app.$api
  // -> this.$api in vue components
  // -> this.$api in store actions/mutations
  const api = {
    ...apiMission($axios),
    ...apiStructure($axios),
    ...apiParticipation($axios),
  }

  inject('api', api)
}
