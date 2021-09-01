import apiApp from '@/api/app'
import apiMission from '@/api/mission'
import apiStructure from '@/api/structure'
import apiParticipation from '@/api/participation'
import apiConversation from '@/api/conversation'
import apiUser from '@/api/user'
import apiPlausible from '@/api/plausible'
import apiEngagement from '@/api/api-engagement'
import apiTerritoire from '@/api/territoire'

export default ({ $axios, $config, $cookies }, inject) => {
  // Inject `api` key
  // -> app.$api
  // -> this.$api in vue components
  // -> this.$api in store actions/mutations
  const api = {
    ...apiApp($axios),
    ...apiMission($axios),
    ...apiStructure($axios),
    ...apiParticipation($axios),
    ...apiConversation($axios),
    ...apiUser($axios, $cookies),
    ...apiPlausible($axios, $config),
    ...apiEngagement($axios, $config),
    ...apiTerritoire($axios),
  }

  inject('api', api)
}
