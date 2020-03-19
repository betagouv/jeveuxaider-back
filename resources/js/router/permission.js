import router from ".";
import store from "../store";

router.beforeEach(async (to, from, next) => {
  // IF REQUIRE AUTH
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // REDIRECT IF NOT LOGGED
    if (!store.getters.isLogged) {
      next("/login");
    } else {
      // IF NOT CONTEXT ROLE
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
