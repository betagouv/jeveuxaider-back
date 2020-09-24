import router from '.'
import store from '../store'

router.beforeEach(async (to, from, next) => {
  // IF REQUIRE AUTH
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    // REDIRECT IF NOT LOGGED
    if (!store.getters.isLogged) {
      next(`/login?redirect=${to.path}`)
    } else {
      // IF NOT CONTEXT ROLE
      if (!store.getters.contextRole) {
        await store.dispatch('user/get')
      }

      // IF TOKEN EXPIRED
      if (store.getters.tokenHasExpired) {
        await store.dispatch('auth/refreshToken')
      }

      // ROLES RESTRICTIONS
      if (to.meta.roles) {
        if (!to.meta.roles.includes(store.getters.contextRole)) {
          next('/403')
        } else {
          if (store.getters.contextRole === 'responsable') {
            if (
              to.name === 'StructureFormEdit' ||
              to.name === 'StructureMembers' ||
              to.name === 'StructureMembersAdd' ||
              to.name === 'Structure'
            ) {
              // Accès seulement si c'est la structure du responsable.
              if (
                !store.getters.structure_as_responsable ||
                store.getters.structure_as_responsable.id != to.params.id
              ) {
                next('/403')
              }
            } else if (to.name === 'MissionFormAdd') {
              // Accès seulement si c'est la structure du responsable.
              if (
                !store.getters.structure_as_responsable ||
                store.getters.structure_as_responsable.id !=
                  to.params.structureId
              ) {
                next('/403')
              }
            }
          }
        }
      }

      next()
    }
  } else {
    // ONLY ANONYMOUS PAGE
    if (to.matched.some((record) => record.meta.requiresAnonymous)) {
      if (store.getters.isLogged) {
        next('/missions')
      }
    }
    next()
  }
  if (
    process.env.MIX_MAINTENANCE_MODE == 1 &&
    to.name != 'Maintenance' &&
    to.query.skip === undefined
  ) {
    next('/maintenance')
  }
})
