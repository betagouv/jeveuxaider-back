import Vue from "vue";
import store from "../store";

import Router from "vue-router";
/* Layout */
import Layout from "@/layout";
/* Pages */
import Login from "@/views/Login.vue";
// import Logout from "@/views/Logout.vue";
// import Register from "@/views/Register.vue";
// import PasswordForgot from "@/views/PasswordForgot.vue";
// import PasswordReset from "@/views/PasswordReset.vue";
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
      component: Layout,
      redirect: "/dashboard",
      meta: { requiresAuth: true },
      children: [
        // {
        //   path: "/dashboard",
        //   component: () =>
        //     import(/* webpackChunkName: "dashboard" */ "@/views/Dashboard.vue"),
        //   name: "Dashboard"
        // },
        // {
        //   path: "/missions",
        //   component: () =>
        //     import(/* webpackChunkName: "missions" */ "@/views/Missions.vue"),
        //   name: "Missions"
        // },
        // {
        //   path: "/structures",
        //   component: () =>
        //     import(
        //       /* webpackChunkName: "structures" */ "@/views/Structures.vue"
        //     ),
        //   name: "Structures"
        // },
        // {
        //   path: "/structure/add",
        //   component: () =>
        //     import(
        //       /* webpackChunkName: "structure" */ "@/views/StructureForm.vue"
        //     ),
        //   name: "StructureFormAdd",
        //   props: { mode: "add" },
        //   meta: {
        //     roles: ["admin"]
        //   }
        // },
        // {
        //   path: "/structure/:id",
        //   component: () =>
        //     import(/* webpackChunkName: "young" */ "@/views/Structure.vue"),
        //   name: "Structure",
        //   props: route => ({ id: parseInt(route.params.id) }),
        //   meta: {
        //     roles: ["admin", "referent", "superviseur", "responsable"]
        //   }
        // },
        // {
        //   path: "/structure/:id/edit",
        //   component: () =>
        //     import(
        //       /* webpackChunkName: "structure" */ "@/views/StructureForm.vue"
        //     ),
        //   name: "StructureFormEdit",
        //   props: route => ({ mode: "edit", id: parseInt(route.params.id) })
        // },
        // {
        //   path: "/structure/:structureId/missions/add",
        //   component: () =>
        //     import(/* webpackChunkName: "mission" */ "@/views/MissionForm.vue"),
        //   name: "MissionFormAdd",
        //   props: route => ({ structureId: parseInt(route.params.structureId) })
        // },
        // {
        //   path: "/structure/:id/members",
        //   component: () =>
        //     import(
        //       /* webpackChunkName: "structure-members" */ "@/views/StructureMembers.vue"
        //     ),
        //   name: "StructureMembers",
        //   props: route => ({ id: parseInt(route.params.id) })
        // },
        // {
        //   path: "/structure/:id/members/add",
        //   component: () =>
        //     import(
        //       /* webpackChunkName: "structure-members-add" */ "@/views/StructureMembersAdd.vue"
        //     ),
        //   name: "StructureMembersAdd",
        //   props: route => ({ id: parseInt(route.params.id) })
        // },
        // {
        //   path: "/mission/:id/edit",
        //   component: () =>
        //     import(/* webpackChunkName: "mission" */ "@/views/MissionForm.vue"),
        //   name: "MissionFormEdit",
        //   props: route => ({ mode: "edit", id: parseInt(route.params.id) })
        // },
        // {
        //   path: "/profiles",
        //   component: () =>
        //     import(/* webpackChunkName: "users" */ "@/views/Profiles.vue"),
        //   name: "Profiles",
        //   meta: {
        //     roles: ["admin", "referent", "superviseur"]
        //   }
        // },
        // {
        //   path: "/profile/:id",
        //   component: () =>
        //     import(/* webpackChunkName: "young" */ "@/views/Profile.vue"),
        //   name: "Profile",
        //   props: route => ({ id: parseInt(route.params.id) }),
        //   meta: {
        //     roles: ["admin", "referent", "superviseur"]
        //   }
        // },
        // {
        //   path: "/profile/:role/add",
        //   component: () =>
        //     import(/* webpackChunkName: "profile" */ "@/views/ProfileForm.vue"),
        //   name: "ProfileFormAdd",
        //   props: route => ({ mode: "add", role: route.params.role })
        // },
        // {
        //   path: "/profile/:id/edit",
        //   component: () =>
        //     import(/* webpackChunkName: "profile" */ "@/views/ProfileForm.vue"),
        //   name: "ProfileFormEdit",
        //   props: route => ({ mode: "edit", id: parseInt(route.params.id) }),
        //   meta: {
        //     roles: ["admin"]
        //   }
        // },
        // {
        //   path: "/settings",
        //   component: () =>
        //     import(/* webpackChunkName: "settings" */ "@/views/Settings.vue"),
        //   name: "Settings"
        // },
        // {
        //   path: "/profile",
        //   component: () =>
        //     import(
        //       /* webpackChunkName: "settings" */ "@/views/ProfileForm.vue"
        //     ),
        //   props: () => ({
        //     mode: "edit",
        //     id: parseInt(store.getters.user.profile.id)
        //   }),
        //   name: "MyProfile"
        // },
        // {
        //   path: "/trash",
        //   component: () =>
        //     import(/* webpackChunkName: "trash" */ "@/views/Trash.vue"),
        //   name: "Trash",
        //   meta: {
        //     roles: ["admin"]
        //   }
        // }
      ]
    },
    {
      path: "/login",
      name: "Login",
      component: Login,
      meta: { requiresAnonymous: true }
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
    //   path: "/register",
    //   name: "Register",
    //   component: Register,
    //   meta: { requiresAnonymous: true }
    // },
    // {
    //   path: "/password/forgot",
    //   name: "PasswordForgot",
    //   component: PasswordForgot,
    //   meta: { requiresAnonymous: true }
    // },
    // {
    //   path: "/password/reset/:token",
    //   name: "PasswordReset",
    //   component: PasswordReset,
    //   meta: { requiresAnonymous: true }
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
