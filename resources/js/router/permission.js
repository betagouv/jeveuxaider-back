import router from ".";
import store from "../store";

router.beforeEach(async (to, from, next) => {
  // IF REQUIRE AUTH
  console.log('try go to', to)
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // REDIRECT IF NOT LOGGED
    console.log('requiresAuth is true')
    if (!store.getters.isLogged) {
        console.log('requiresAuth is true && is NOT logged')
      next("/login");
    } else {
      // IF NOT CONTEXT ROLE
      console.log('requiresAuth is true && is logged')
      if (!store.getters.contextRole) {
        await store.dispatch("user/get");
      }

      // IF TOKEN EXPIRED
      if (store.getters.tokenHasExpired) {
        await store.dispatch("auth/refreshToken");
      }

      // ROLES RESTRICTIONS
      if (to.meta.roles && !to.meta.roles.includes(store.getters.contextRole)) {
        next("/403");
      }

      next();
    }
  } else {
    // ONLY ANONYMOUS PAGE
    if (to.matched.some(record => record.meta.requiresAnonymous)) {
      if (store.getters.isLogged) {
        next("/missions");
      }
    }
    next();
  }
  if (process.env.MIX_MAINTENANCE_MODE == 1 && to.name != "Maintenance") {
    next("/maintenance");
  }
});
