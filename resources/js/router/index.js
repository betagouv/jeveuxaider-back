import Vue from "vue";
// import store from "../store";
import Router from "vue-router";
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
// import Releases from "@/views/Releases.vue";
import FrontHomepage from "@/views/Front/Homepage";
import FrontAbout from "@/views/Front/About";
import FrontSecurityRules from "@/views/Front/SecurityRules";
import FrontPolitiqueConfidentialite from "@/views/Front/PolitiqueConfidentialite";
import FrontProfile from "@/views/Front/Profile";
import FrontSettings from "@/views/Front/Settings";
import FrontUserMissions from "@/views/Front/UserMissions";
import FrontFaq from "@/views/Front/Faq";
import FrontLegalNotice from "@/views/Front/LegalNotice";
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
            name: "Mentions légales",
            component: FrontLegalNotice,
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
                        import(/* webpackChunkName: "assets/js/dashboard" */ "@/views/SNU/Dashboard.vue"),
                    name: "Dashboard",
                    meta: {
                        roles: ["admin", "referent", "superviseur", "responsable"]
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
                        roles: ["admin", "referent", "superviseur", "responsable"]
                    }
                },
                {
                    path: "/dashboard/structure/:structureId/missions/add",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-mission-add" */ "@/views/SNU/MissionForm.vue"),
                    name: "MissionFormAdd",
                    props: route => ({ structureId: parseInt(route.params.structureId) }),
                    meta: {
                        roles: ["admin", "referent", "superviseur", "responsable"]
                    }
                },
                {
                    path: "/dashboard/mission/:id/edit",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-mission-edit" */ "@/views/SNU/MissionForm.vue"),
                    name: "MissionFormEdit",
                    props: route => ({ mode: "edit", id: parseInt(route.params.id) }),
                    meta: {
                        roles: ["admin", "referent", "superviseur", "responsable"]
                    }
                },
                {
                    path: "/dashboard/structures",
                    component: () =>
                        import(
                    /* webpackChunkName: "assets/js/dashboard-structures" */ "@/views/SNU/Structures.vue"
                        ),
                    name: "Structures",
                    meta: {
                        roles: ["admin", "referent", "superviseur"]
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
                        roles: ["admin", "referent", "superviseur"]
                    }
                },
                {
                    path: "/dashboard/structure/:id",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-structure-view" */ "@/views/SNU/Structure.vue"),
                    name: "Structure",
                    props: route => ({ id: parseInt(route.params.id) }),
                    meta: {
                        roles: ["admin", "referent", "superviseur", "responsable"]
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
                        roles: ["admin", "referent", "superviseur", "responsable"]
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
                        roles: ["admin", "referent", "superviseur", "responsable"]
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
                        roles: ["admin", "referent", "superviseur", "responsable"]
                    }
                },
                {
                    path: "/dashboard/profiles",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-profiles" */ "@/views/SNU/Profiles.vue"),
                    name: "Profiles",
                    meta: {
                        roles: ["admin", "referent"]
                    }
                },
                {
                    path: "/dashboard/profile/:id",
                    component: () =>
                        import(/* webpackChunkName: "assets/js/dashboard-profile-view" */ "@/views/SNU/Profile.vue"),
                    name: "Profile",
                    props: route => ({ id: parseInt(route.params.id) }),
                    meta: {
                        roles: ["admin", "referent"]
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
                        roles: ["admin", "referent", "superviseur", "responsable"]
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
                }
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
        { path: "/403", component: Forbidden, name: 'Forbidden' },
        { path: "*", component: NotFound, name: 'NotFound' }
    ],
    scrollBehavior(to, from, savedPosition) {
        return { x: 0, y: 0 }
    }
});
