import apiApp from '@/api/app'
import apiMission from '@/api/mission'
import apiStructure from '@/api/structure'
import apiParticipation from '@/api/participation'
import apiUser from '@/api/user'

export default ({ $axios }, inject) => {
  // Inject `api` key
  // -> app.$api
  // -> this.$api in vue components
  // -> this.$api in store actions/mutations
  const api = {
    ...apiApp($axios),
    ...apiMission($axios),
    ...apiStructure($axios),
    ...apiParticipation($axios),
    ...apiUser($axios),
  }

  inject('api', api)
}
