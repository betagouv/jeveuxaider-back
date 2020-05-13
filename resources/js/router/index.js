import Vue from "vue";
// import store from "../store";
import Router from "vue-router";
import qs from 'qs';
/* Layouts */
import Layout2Cols from "@/layout/Layout2Cols";
import LayoutRegisterSteps from "@/layout/LayoutRegisterSteps";
import LayoutSNU from "@/layout/LayoutSNU";
/* Pages */
import Login from "@/views/Login.vue";
import Logout from "@/views/Logout.vue";
import Register from "@/views/Register.vue";
import RegisterResponsable from "@/views/RegisterResponsable.vue";
import RegisterVolontaire from "@/views/RegisterVolontaire.vue";
import RegisterInvitation from "@/views/RegisterInvitation.vue";
import PasswordForgot from "@/views/PasswordForgot.vue";
import PasswordReset from "@/views/PasswordReset.vue";
import NotFound from "@/views/NotFound.vue";
import Forbidden from "@/views/Forbidden.vue";
import Maintenance from "@/views/Maintenance.vue";
import BrowserOutdated from "@/views/BrowserOutdated.vue";

import FrontHomepage from "@/views/Front/Homepage";
import FrontAbout from "@/views/Front/About";
import FrontSecurityRules from "@/views/Front/SecurityRules";
import FrontPolitiqueConfidentialite from "@/views/Front/PolitiqueConfidentialite";
import FrontProfile from "@/views/Front/Profile";
import FrontSettings from "@/views/Front/Settings";
import FrontUserMissions from "@/views/Front/UserMissions";
import FrontFaq from "@/views/Front/Faq";
import FrontPage from "@/views/Front/Page";

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
            name: "Homepage",
            component: FrontHomepage,
            meta: { requiresAnonymous: true }
        },
        {
            path: "/regles-de-securite",
            name: "SecurityRules",
            component: FrontSecurityRules,
        },
        {
            path: "/politique-de-confidentialite",
            name: "PolitiqueConfidentialite",
            component: FrontPolitiqueConfidentialite,
        },
        {
            path: "/a-propos",
            name: "About",
            component: FrontAbout,
        },
        {
            path: "/faq",
            name: "Faq",
            component: FrontFaq,
        },
        {
            path: "/mentions-legales",
            name: "PageMentionsLegales",
            component: FrontPage,
            props: { id: 1 },
        },
        {
            path: "/conditions-generales-d-utilisation",
            name: "PageCGU",
            component: FrontPage,
            props: { id: 2 },
        },
        {
            path: "/politique-de-confidentialite2",
            name: "PagePolitiqueConfidentialite",
            component: FrontPage,
            props: { id: 3 },
        },
        {
            path: "/charte-reserve-civique",
            name: "PageCharteReserveCivique",
            component: FrontPage,
            props: { id: 4 },
        },
        {
            path: "/user",
            component: Layout2Cols,
            redirect: "/login",
            children: [
                {
                    path: "/maintenance",
                    name: "Maintenance",
                    component: Maintenance,
                },
                {
                    path: "/browser-outdated",
                    name: "BrowserOutdated",
                    component: BrowserOutdated
                },
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
                    path: "/register/volontaire",
                    name: "RegisterVolontaire",
                    component: RegisterVolontaire,
                    meta: { requiresAnonymous: true }
                },
                {
                    path: "/register/responsable",
                    name: "RegisterResponsable",
                    component: RegisterResponsable,
                    meta: { requiresAnonymous: true }
                },
                {
                    path: "/register/invitation",
                    name: "RegisterInvitation",
                    component: RegisterInvitation,
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
                },
                {
                    path: "/logout",
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
              /* webpackChunkName: "assets/js/no-role-step" */ "@/views/RegisterSteps/NoRoleStep.vue"
                        ),
                    name: "NoRoleStep"
                },
                {
                    path: "/register/step/profile",
                    component: () =>
                        import(
              /* webpackChunkName: "assets/js/profile-step" */ "@/views/RegisterSteps/ProfileStep.vue"
                        ),
                    name: "ProfileStep"
                },
                {
                    path: "/register/step/structure",
                    component: () =>
                        import(
              /* webpackChunkName: "assets/js/structure-step" */ "@/views/RegisterSteps/StructureStep.vue"
                        ),
                    name: "StructureStep"
                },
                {
                    path: "/register/step/address",
                    component: () =>
                        import(
              /* webpackChunkName: "assets/js/address-step" */ "@/views/RegisterSteps/AddressStep.vue"
                        ),
                    name: "AddressStep"
                },
                {
                    path: "/register/step/other",
                    component: () =>
                        import(
                      /* webpackChunkName: "assets/js/no-role-step" */ "@/views/RegisterSteps/OtherStep.vue"
                        ),
                    name: "OtherStep"
                },
            ]
        },
        {
            path: "/dashboard",
            component: LayoutSNU,
            meta: { requiresAuth: true },
            children: [
                {
                    path: "/dashboard",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard" */ "@/views/SNU/Dashboards/Main.vue"),
                    name: "Dashboard",
                    meta: {
                        roles: ["admin", "referent","referent_regional","superviseur", "responsable", "analyste"]
                    }
                },
                {
                    path: "/dashboard/stats/structures",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-stats-structures" */ "@/views/SNU/Dashboards/Structures.vue"),
                    name: "StatsStructures",
                    meta: {
                        roles: ["admin", "referent","referent_regional","superviseur", "analyste"]
                    }
                },
                {
                    path: "/dashboard/stats/missions",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-stats-missions" */ "@/views/SNU/Dashboards/Missions.vue"),
                    name: "StatsMissions",
                    meta: {
                        roles: ["admin", "referent","referent_regional","superviseur", "analyste", "responsable"]
                    }
                },
                {
                    path: "/dashboard/stats/participations",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-stats-participations" */ "@/views/SNU/Dashboards/Participations.vue"),
                    name: "StatsParticipations",
                    meta: {
                        roles: ["admin", "referent","referent_regional","superviseur", "analyste", "responsable"]
                    }
                },
                {
                    path: "/dashboard/stats/profiles",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-stats-profiles" */ "@/views/SNU/Dashboards/Profiles.vue"),
                    name: "StatsProfiles",
                    meta: {
                        roles: ["admin", "referent","referent_regional","superviseur", "analyste"]
                    }
                },
                {
                    path: "/dashboard/stats/departments",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-stats-departments" */ "@/views/SNU/Dashboards/Departments.vue"),
                    name: "StatsDepartments",
                    meta: {
                        roles: ["admin", "referent","referent_regional","superviseur", "analyste"]
                    }
                },
                {
                    path: "/dashboard/missions",
                    component: () =>
                        import(
                    /* webpackChunkName: "assets/js/dashboard-missions" */ "@/views/SNU/Missions.vue"
                        ),
                    name: "DashboardMissions",
                    meta: {
                        roles: ["admin", "referent","referent_regional", "superviseur", "responsable"]
                    }
                },
                {
                    path: "/dashboard/structure/:structureId/missions/add",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-mission-add" */ "@/views/SNU/MissionForm.vue"),
                    name: "MissionFormAdd",
                    props: route => ({ structureId: parseInt(route.params.structureId) }),
                    meta: {
                        roles: ["admin", "referent","referent_regional", "superviseur", "responsable"]
                    }
                },
                {
                    path: "/dashboard/mission/:id/edit",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-mission-edit" */ "@/views/SNU/MissionForm.vue"),
                    name: "MissionFormEdit",
                    props: route => ({ mode: "edit", id: parseInt(route.params.id) }),
                    meta: {
                        roles: ["admin", "referent","referent_regional", "superviseur", "responsable"]
                    }
                },
                {
                    path: "/dashboard/structures",
                    component: () =>
                        import(
                    /* webpackChunkName: "assets/js/dashboard-structures" */ "@/views/SNU/Structures.vue"
                        ),
                    name: "DashboardStructures",
                    meta: {
                        roles: ["admin", "referent","referent_regional", "superviseur"]
                    }
                },
                {
                    path: "/dashboard/structure/add",
                    component: () =>
                        import(
                    /* webpackChunkName: "assets/js/dashboard-structure-add" */ "@/views/SNU/StructureForm.vue"
                        ),
                    name: "StructureFormAdd",
                    props: { mode: "add" },
                    meta: {
                        roles: ["admin", "referent","referent_regional", "superviseur"]
                    }
                },
                {
                    path: "/dashboard/structure/:id",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-structure-view" */ "@/views/SNU/Structure.vue"),
                    name: "Structure",
                    props: route => ({ id: parseInt(route.params.id) }),
                    meta: {
                        roles: ["admin", "referent","referent_regional", "superviseur", "responsable"]
                    }
                },
                {
                    path: "/dashboard/structure/:id/edit",
                    component: () =>
                        import(
                    /* webpackChunkName: "assets/js/dashboard-structure-edit" */ "@/views/SNU/StructureForm.vue"
                        ),
                    name: "StructureFormEdit",
                    props: route => ({ mode: "edit", id: parseInt(route.params.id) }),
                    meta: {
                        roles: ["admin", "referent","referent_regional", "superviseur", "responsable"]
                    }
                },
                {
                    path: "/dashboard/structure/:id/members",
                    component: () =>
                        import(
                    /* webpackChunkName: "assets/js/dashboard-structure-members" */ "@/views/SNU/StructureMembers.vue"
                        ),
                    name: "StructureMembers",
                    props: route => ({ id: parseInt(route.params.id) }),
                    meta: {
                        roles: ["admin", "referent","referent_regional", "superviseur", "responsable"]
                    }
                },
                {
                    path: "/dashboard/structure/:id/members/add",
                    component: () =>
                        import(
                    /* webpackChunkName: "assets/js/dashboard-structure-members-add" */ "@/views/SNU/StructureMembersAdd.vue"
                        ),
                    name: "StructureMembersAdd",
                    props: route => ({ id: parseInt(route.params.id) }),
                    meta: {
                        roles: ["admin", "referent","referent_regional", "superviseur", "responsable"]
                    }
                },
                {
                    path: "/dashboard/profiles",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-profiles" */ "@/views/SNU/Profiles.vue"),
                    name: "DashboardProfiles",
                    meta: {
                        roles: ["admin", "referent", "referent_regional"]
                    }
                },
                {
                    path: "/dashboard/profile/:id",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-profile-view" */ "@/views/SNU/Profile.vue"),
                    name: "Profile",
                    props: route => ({ id: parseInt(route.params.id) }),
                    meta: {
                        roles: ["admin", "referent","referent_regional"]
                    }
                },
                {
                    path: "/dashboard/profile/:role/add",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-profile-role-add" */ "@/views/SNU/ProfileForm.vue"),
                    name: "ProfileFormAdd",
                    props: route => ({ mode: "add", role: route.params.role }),
                    meta: {
                        roles: ["admin"]
                    }
                },
                {
                    path: "/dashboard/profile/:id/edit",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-profile-edit" */ "@/views/SNU/ProfileForm.vue"),
                    name: "ProfileFormEdit",
                    props: route => ({ mode: "edit", id: parseInt(route.params.id) }),
                    meta: {
                        roles: ["admin"]
                    }
                },
                {
                    path: "/dashboard/participations",
                    component: () =>
                        import(
                    /* webpackChunkName: "assets/js/dashboard-participations" */ "@/views/SNU/Participations.vue"
                        ),
                    name: "DashboardParticipations",
                    meta: {
                        roles: ["admin", "referent","referent_regional", "superviseur", "responsable"]
                    }
                },
                {
                    path: "/dashboard/trash",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-trash" */ "@/views/SNU/Trash.vue"),
                    name: "Trash",
                    meta: {
                        roles: ["admin"]
                    }
                },
                {
                    path: "/dashboard/faq/add",
                    component: () =>
                        import(
                    /* webpackChunkName: "assets/js/dashboard-faq-add" */ "@/views/SNU/FaqForm.vue"
                        ),
                    name: "FaqFormAdd",
                    props: { mode: "add" },
                    meta: {
                        roles: ["admin"]
                    }
                },
                {
                    path: "/dashboard/faq/:id/edit",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-faq-edit" */ "@/views/SNU/FaqForm.vue"),
                    name: "FaqFormEdit",
                    props: route => ({ mode: "edit", id: parseInt(route.params.id) }),
                    meta: {
                        roles: ["admin"]
                    }
                },
                {
                    path: "/dashboard/news",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-news" */ "@/views/SNU/News.vue"),
                    name: "News",
                    meta: {
                        roles: ["admin", "referent","referent_regional", "superviseur", "responsable"]
                    }
                },
                {
                    path: "/dashboard/contents",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-contents" */ "@/views/SNU/Contents.vue"),
                    name: "Contents",
                    meta: {
                        roles: ["admin"]
                    }
                },
                {
                    path: "/dashboard/release/add",
                    component: () =>
                        import(
                    /* webpackChunkName: "assets/js/dashboard-release-add" */ "@/views/SNU/ReleaseForm.vue"
                        ),
                    name: "ReleaseFormAdd",
                    props: { mode: "add" },
                    meta: {
                        roles: ["admin"]
                    }
                },
                {
                    path: "/dashboard/release/:id/edit",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-release-edit" */ "@/views/SNU/ReleaseForm.vue"),
                    name: "ReleaseFormEdit",
                    props: route => ({ mode: "edit", id: parseInt(route.params.id) }),
                    meta: {
                        roles: ["admin"]
                    }
                },
                {
                    path: "/dashboard/page/add",
                    component: () =>
                        import(
                    /* webpackChunkName: "assets/js/dashboard-page-add" */ "@/views/SNU/PageForm.vue"
                        ),
                    name: "PageFormAdd",
                    props: { mode: "add" },
                    meta: {
                        roles: ["admin"]
                    }
                },
                {
                    path: "/dashboard/page/:id/edit",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-page-edit" */ "@/views/SNU/PageForm.vue"),
                    name: "PageFormEdit",
                    props: route => ({ mode: "edit", id: parseInt(route.params.id) }),
                    meta: {
                        roles: ["admin"]
                    }
                },
                {
                    path: "/dashboard/collectivity/add",
                    component: () =>
                        import(
                    /* webpackChunkName: "assets/js/dashboard-collectivity-add" */ "@/views/SNU/CollectivityForm.vue"
                        ),
                    name: "CollectivityFormAdd",
                    props: { mode: "add" },
                    meta: {
                        roles: ["admin"]
                    }
                },
                {
                    path: "/dashboard/collectivity/:id/edit",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-collectivity-edit" */ "@/views/SNU/CollectivityForm.vue"),
                    name: "CollectivityFormEdit",
                    props: route => ({ mode: "edit", id: parseInt(route.params.id) }),
                    meta: {
                        roles: ["admin"]
                    }
                },
                {
                    path: "/dashboard/document/add",
                    component: () =>
                        import(
                    /* webpackChunkName: "assets/js/dashboard-document-add" */ "@/views/SNU/DocumentForm.vue"
                        ),
                    name: "DocumentFormAdd",
                    props: { mode: "add" },
                    meta: {
                        roles: ["admin"]
                    }
                },
                {
                    path: "/dashboard/document/:id/edit",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-document-edit" */ "@/views/SNU/DocumentForm.vue"),
                    name: "DocumentFormEdit",
                    props: route => ({ mode: "edit", id: parseInt(route.params.id) }),
                    meta: {
                        roles: ["admin"]
                    }
                },
            ]
        },
        {
            path: '/user/profile',
            component: FrontProfile,
            name: 'FrontProfile'
        },
        {
            path: '/user/settings',
            component: FrontSettings,
            name: 'FrontSettings'
        },
        {
            path: '/user/missions',
            component: FrontUserMissions,
            name: 'FrontUserMissions'
        },
        {
            path: '/missions',
            component: () =>
                import(
                /* webpackChunkName: "assets/js/front-missions" */ "@/views/Front/Missions.vue"
                ),
            name: 'FrontMissions'
        },
        {
            path: "/missions/:id",
            component: () =>
                import(
            /* webpackChunkName: "assets/js/mission" */ "@/views/Mission.vue"
                ),
            name: "Mission",
            props: route => ({ id: parseInt(route.params.id) }),
        },
        {
            path: "/territoires/:slug",
            component: () =>
                import(/* webpackChunkName: "assets/js/collectivites-slug" */ "@/views/Front/Collectivity.vue"),
            name: "CollectivitySlug",
            props: route => ({ slug: route.params.slug })
        },
        { path: "/403", component: Forbidden, name: 'Forbidden' },
        { path: "*", component: NotFound, name: 'NotFound' }
    ],
    scrollBehavior(to, from, savedPosition) {
        return { x: 0, y: 0 }
    },
    parseQuery(query) {
      return qs.parse(query);
    },
    stringifyQuery(query) {
      const result = qs.stringify(query);

      return result ? '?' + result : '';
    },
});
