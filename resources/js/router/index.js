import Vue from "vue";
import store from "../store";

import Router from "vue-router";
/* Layout */
import Layout from "@/layout";
import Layout2Cols from "@/layout/Layout2Cols";
/* Pages */
import Login from "@/views/Login.vue";
// import Logout from "@/views/Logout.vue";
import Register from "@/views/Register.vue";
import PasswordForgot from "@/views/PasswordForgot.vue";
import PasswordReset from "@/views/PasswordReset.vue";
// import NotFound from "@/views/NotFound.vue";
// import Forbidden from "@/views/Forbidden.vue";
// import BrowserOutdated from "@/views/BrowserOutdated.vue";
// import Maintenance from "@/views/Maintenance.vue";
// import Releases from "@/views/Releases.vue";

// import RegisterSteps from "@/layout/RegisterSteps";

// Fix for NavigationDuplicated error -> need to add catch to push promise.
const originalPush = Router.prototype.push;
Router.prototype.push = function push(location) {
  return originalPush.call(this, location).catch(err => err);
};

Vue.use(Router);

export default new Router({
  mode: "history",
  base: process.env.BASE_URL,
  routes: [
    {
      path: "/",
      component: Layout2Cols,
      redirect: "/login",
      meta: { requiresAuth: true },
      children: [
        {
            path: "/login",
            name: "Login",
            component: Login,
            meta: { requiresAnonymous: true }
          },
          {
            path: "/register",
            name: "Register",
            component: Register,
            meta: { requiresAnonymous: true }
          },
          {
            path: "/password/forgot",
            name: "PasswordForgot",
            component: PasswordForgot,
            meta: { requiresAnonymous: true }
          },
          {
            path: "/password/reset/:token",
            name: "PasswordReset",
            component: PasswordReset,
            meta: { requiresAnonymous: true }
          }
      ]
    },
    // {
    //   path: "/register/step",
    //   name: "RegisterSteps",
    //   component: RegisterSteps,
    //   redirect: "/register/step/norole",
    //   meta: { requiresAuth: true },
    //   children: [
    //     {
    //       path: "/register/step/norole",
    //       component: () =>
    //         import(
    //           /* webpackChunkName: "no-role-step" */ "@/views/RegisterSteps/NoRoleStep.vue"
    //         ),
    //       name: "NoRoleStep"
    //     },
    //     {
    //       path: "/register/step/other",
    //       component: () =>
    //         import(
    //           /* webpackChunkName: "no-role-step" */ "@/views/RegisterSteps/OtherStep.vue"
    //         ),
    //       name: "OtherStep"
    //     },
    //     {
    //       path: "/register/step/profile",
    //       component: () =>
    //         import(
    //           /* webpackChunkName: "profile-step" */ "@/views/RegisterSteps/ProfileStep.vue"
    //         ),
    //       name: "ProfileStep"
    //     },
    //     {
    //       path: "/register/step/structure",
    //       component: () =>
    //         import(
    //           /* webpackChunkName: "structure-step" */ "@/views/RegisterSteps/StructureStep.vue"
    //         ),
    //       name: "StructureStep"
    //     },
    //     {
    //       path: "/register/step/address",
    //       component: () =>
    //         import(
    //           /* webpackChunkName: "address-step" */ "@/views/RegisterSteps/AddressStep.vue"
    //         ),
    //       name: "AddressStep"
    //     }
    //   ]
    // },
    // {
    //   path: "/logout",
    //   name: "Logout",
    //   component: Logout
    // },
    // {
    //   path: "/releases",
    //   component: Releases,
    //   meta: { requiresAuth: true }
    // },
    // {
    //   path: "/browser-outdated",
    //   component: BrowserOutdated
    // },
    // {
    //   path: "/maintenance",
    //   name: "maintenance",
    //   component: Maintenance
    // },
    // { path: "/403", component: Forbidden },
    // { path: "*", component: NotFound }
  ]
});
