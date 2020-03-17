import Vue from "vue";
import * as Sentry from "@sentry/browser";
import * as Integrations from "@sentry/integrations";
import store from "../store";

if (process.env.VUE_APP_SENTRY_DSN) {
  Sentry.init({
    environment: process.env.NODE_ENV,
    dsn: process.env.VUE_APP_SENTRY_DSN,
    integrations: [
      new Integrations.Vue({ Vue, attachProps: true, logErrors: true })
    ]
  });
  if (store.getters.profile) {
    Sentry.configureScope(scope => {
      scope.setUser({
        email: store.getters.profile.email,
        id: store.getters.profile.id,
        username: store.getters.profile.full_name
      });
    });
  } else {
    Sentry.configureScope(scope => {
      scope.setUser({
        isLogged: store.getters.isLogged
      });
    });
  }
}
/* pour tester
Sentry.captureMessage("Something went wrong");
myUndefinedFunction();
*/
