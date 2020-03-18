import Vue from "vue";
// import store from "../store";

import Router from "vue-router";

/* Layouts */
// import Layout from "@/layout";
import Layout2Cols from "@/layout/Layout2Cols";
import LayoutRegisterSteps from "@/layout/LayoutRegisterSteps";

/* Pages */
import Login from "@/views/Login.vue";
import Logout from "@/views/Logout.vue";
import Register from "@/views/Register.vue";
import RegisterResponsable from "@/views/RegisterResponsable.vue";
import RegisterVolontaire from "@/views/RegisterVolontaire.vue";
import PasswordForgot from "@/views/PasswordForgot.vue";
import PasswordReset from "@/views/PasswordReset.vue";
import NotFound from "@/views/NotFound.vue";
import Forbidden from "@/views/Forbidden.vue";
// import BrowserOutdated from "@/views/BrowserOutdated.vue";
// import Maintenance from "@/views/Maintenance.vue";
// import Releases from "@/views/Releases.vue";

import FrontMissions from "@/views/Front/Missions";

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
      path: "/user",
      component: Layout2Cols,
      redirect: "/user/login",
      children: [
        {
            path: "/user/login",
            name: "Login",
            component: Login,
            meta: { requiresAnonymous: true }
          },
          {
            path: "/user/register",
            name: "Register",
            component: Register,
            meta: { requiresAnonymous: true }
          },
          {
            path: "/user/register/volontaire",
            name: "RegisterVolontaire",
            component: RegisterVolontaire,
            meta: { requiresAnonymous: true }
          },
          {
            path: "/user/register/responsable",
            name: "RegisterResponsable",
            component: RegisterResponsable,
            meta: { requiresAnonymous: true }
          },
          {
            path: "/user/password/forgot",
            name: "PasswordForgot",
            component: PasswordForgot,
            meta: { requiresAnonymous: true }
          },
          {
            path: "/user/password/reset/:token",
            name: "PasswordReset",
            component: PasswordReset,
            meta: { requiresAnonymous: true }
          },
          {
            path: "/user/logout",
            name: "Logout",
            component: Logout
          },
      ]
    },
    {
      path: "/register/step",
      component: LayoutRegisterSteps,
      redirect: "/register/step/profile",
      meta: { requiresAuth: true },
      children: [
        {
          path: "/register/step/norole",
          component: () =>
            import(
              /* webpackChunkName: "no-role-step" */ "@/views/RegisterSteps/NoRoleStep.vue"
            ),
          name: "NoRoleStep"
        },
        {
          path: "/register/step/profile",
          component: () =>
            import(
              /* webpackChunkName: "profile-step" */ "@/views/RegisterSteps/ProfileStep.vue"
            ),
          name: "ProfileStep"
        },
        {
          path: "/register/step/structure",
          component: () =>
            import(
              /* webpackChunkName: "structure-step" */ "@/views/RegisterSteps/StructureStep.vue"
            ),
          name: "StructureStep"
        },
        {
          path: "/register/step/address",
          component: () =>
            import(
              /* webpackChunkName: "address-step" */ "@/views/RegisterSteps/AddressStep.vue"
            ),
          name: "AddressStep"
        }
    //     {
    //       path: "/register/step/other",
    //       component: () =>
    //         import(
    //           /* webpackChunkName: "no-role-step" */ "@/views/RegisterSteps/OtherStep.vue"
    //         ),
    //       name: "OtherStep"
    //     },
      ]
    },
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
    {
      path: '/missions',
      component: FrontMissions

    },
    { path: "/403", component: Forbidden },
    { path: "*", component: NotFound }
  ]
});
