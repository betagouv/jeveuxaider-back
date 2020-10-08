import Vue from 'vue'
// import store from "../store";
import Router from 'vue-router'
/* Layouts */
import Layout2Cols from '@/layout/Layout2Cols'
import LayoutRegisterSteps from '@/layout/LayoutRegisterSteps'
import LayoutSNU from '@/layout/LayoutSNU'
import LayoutProfile from '@/layout/LayoutProfile.vue'

/* Pages */
import Login from '@/views/Login.vue'
import Logout from '@/views/Logout.vue'
import Register from '@/views/Register.vue'
import RegisterResponsable from '@/views/RegisterResponsable.vue'
import RegisterCollectivity from '@/views/RegisterCollectivity.vue'
import RegisterVolontaire from '@/views/RegisterVolontaire.vue'
import RegisterInvitation from '@/views/RegisterInvitation.vue'
import PasswordForgot from '@/views/PasswordForgot.vue'
import PasswordReset from '@/views/PasswordReset.vue'
import NotFound from '@/views/NotFound.vue'
import Forbidden from '@/views/Forbidden.vue'
import Maintenance from '@/views/Maintenance.vue'
import BrowserOutdated from '@/views/BrowserOutdated.vue'

import FrontHomepage from '@/views/Front/Homepage'
import FrontAbout from '@/views/Front/About'
import FrontSecurityRules from '@/views/Front/SecurityRules'
import FrontPolitiqueConfidentialite from '@/views/Front/PolitiqueConfidentialite'
import FrontUserMissions from '@/views/Front/UserMissions'
import Missions from '@/views/Front/Missions'
import FrontFaq from '@/views/Front/Faq'
import FrontPage from '@/views/Front/Page'
import Messages from '@/views/Front/Messages'

// Fix for NavigationDuplicated error -> need to add catch to push promise.
const originalPush = Router.prototype.push
Router.prototype.push = function push(location) {
  return originalPush.call(this, location).catch((err) => err)
}
Vue.use(Router)
export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: 'Homepage',
      component: FrontHomepage,
    },
    {
      path: '/regles-de-securite',
      name: 'SecurityRules',
      component: FrontSecurityRules,
    },
    {
      path: '/politique-de-confidentialite',
      name: 'PolitiqueConfidentialite',
      component: FrontPolitiqueConfidentialite,
    },
    {
      path: '/a-propos',
      name: 'About',
      component: FrontAbout,
    },
    {
      path: '/faq',
      name: 'Faq',
      component: FrontFaq,
    },
    {
      path: '/mentions-legales',
      name: 'PageMentionsLegales',
      component: FrontPage,
      props: { id: 1 },
    },
    {
      path: '/conditions-generales-d-utilisation',
      name: 'PageCGU',
      component: FrontPage,
      props: { id: 2 },
    },
    {
      path: '/politique-de-confidentialite2',
      name: 'PagePolitiqueConfidentialite',
      component: FrontPage,
      props: { id: 3 },
    },
    {
      path: '/charte-reserve-civique',
      name: 'PageCharteReserveCivique',
      component: FrontPage,
      props: { id: 4 },
    },
    {
      path: '/user',
      component: Layout2Cols,
      redirect: '/login',
      children: [
        {
          path: '/maintenance',
          name: 'Maintenance',
          component: Maintenance,
        },
        {
          path: '/browser-outdated',
          name: 'BrowserOutdated',
          component: BrowserOutdated,
        },
        {
          path: '/login',
          name: 'Login',
          component: Login,
          meta: { requiresAnonymous: true },
        },
        {
          path: '/register',
          name: 'Register',
          component: Register,
          meta: { requiresAnonymous: true },
        },
        {
          path: '/register/volontaire',
          name: 'RegisterVolontaire',
          component: RegisterVolontaire,
          meta: { requiresAnonymous: true },
        },
        {
          path: '/register/responsable',
          name: 'RegisterResponsable',
          component: RegisterResponsable,
          meta: { requiresAnonymous: true },
        },
        {
          path: '/register/collectivity',
          name: 'RegisterCollectivity',
          component: RegisterCollectivity,
          meta: { requiresAnonymous: true },
        },
        {
          path: '/register/invitation',
          name: 'RegisterInvitation',
          component: RegisterInvitation,
          meta: { requiresAnonymous: true },
        },
        {
          path: '/password/forgot',
          name: 'PasswordForgot',
          component: PasswordForgot,
          meta: { requiresAnonymous: true },
        },
        {
          path: '/password/reset/:token',
          name: 'PasswordReset',
          component: PasswordReset,
          meta: { requiresAnonymous: true },
        },
        {
          path: '/logout',
          name: 'Logout',
          component: Logout,
        },
      ],
    },
    {
      path: '/register/responsable/step',
      component: LayoutRegisterSteps,
      redirect: '/register/responsable/step/profile',
      meta: { requiresAuth: true },
      children: [
        {
          path: '/register/responsable/step/norole',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/no-role-step" */ '@/views/ResponsableSteps/NoRoleStep.vue'
            ),
          name: 'NoRoleStep',
        },
        {
          path: '/register/responsable/step/profile',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/profile-step" */ '@/views/ResponsableSteps/ProfileStep.vue'
            ),
          name: 'ProfileStep',
        },
        {
          path: '/register/responsable/step/structure',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/structure-step" */ '@/views/ResponsableSteps/StructureStep.vue'
            ),
          name: 'StructureStep',
        },
        {
          path: '/register/responsable/step/address',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/address-step" */ '@/views/ResponsableSteps/AddressStep.vue'
            ),
          name: 'AddressStep',
        },
        {
          path: '/register/responsable/step/other',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/no-role-step" */ '@/views/ResponsableSteps/OtherStep.vue'
            ),
          name: 'OtherStep',
        },
      ],
    },
    {
      path: '/register/collectivity/step',
      component: LayoutRegisterSteps,
      redirect: '/register/collectivity/step/profile',
      meta: { requiresAuth: true },
      children: [
        {
          path: '/register/collectivity/step/profile',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/collectivity-profile-step" */ '@/views/CollectivitySteps/ProfileStep.vue'
            ),
          name: 'CollectivityProfileStep',
        },
        {
          path: '/register/collectivity/step/infos',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/collectivity-infos-step" */ '@/views/CollectivitySteps/InfosStep.vue'
            ),
          name: 'CollectivityInfoseStep',
        },
        {
          path: '/register/collectivity/step/address',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/collectivity-address-step" */ '@/views/CollectivitySteps/AddressStep.vue'
            ),
          name: 'CollectivityAddressStep',
        },
      ],
    },
    {
      path: '/register/reserviste/step',
      component: LayoutRegisterSteps,
      redirect: '/register/reserviste/step/preferences',
      meta: { requiresAuth: true },
      children: [
        {
          path: '/register/reserviste/step/preferences',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/reserviste-preferences-step" */ '@/views/ReservisteSteps/PreferencesStep.vue'
            ),
          name: 'PreferencesStep',
        },
        {
          path: '/register/reserviste/step/infos',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/reserviste-infos-step" */ '@/views/ReservisteSteps/InfosStep.vue'
            ),
          name: 'InfosStep',
        },
      ],
    },
    {
      path: '/dashboard',
      component: LayoutSNU,
      meta: { requiresAuth: true },
      children: [
        {
          path: '/dashboard',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard" */ '@/views/SNU/Dashboards/Main.vue'
            ),
          name: 'Dashboard',
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'superviseur',
              'responsable',
              'responsable_collectivity',
              'analyste',
            ],
          },
        },
        {
          path: '/dashboard/stats/structures',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-stats-structures" */ '@/views/SNU/Dashboards/Structures.vue'
            ),
          name: 'StatsStructures',
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'responsable_collectivity',
              'superviseur',
              'analyste',
            ],
          },
        },
        {
          path: '/dashboard/stats/missions',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-stats-missions" */ '@/views/SNU/Dashboards/Missions.vue'
            ),
          name: 'StatsMissions',
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'responsable_collectivity',
              'superviseur',
              'analyste',
              'responsable',
            ],
          },
        },
        {
          path: '/dashboard/stats/participations',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-stats-participations" */ '@/views/SNU/Dashboards/Participations.vue'
            ),
          name: 'StatsParticipations',
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'responsable_collectivity',
              'superviseur',
              'analyste',
              'responsable',
            ],
          },
        },
        {
          path: '/dashboard/stats/profiles',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-stats-profiles" */ '@/views/SNU/Dashboards/Profiles.vue'
            ),
          name: 'StatsProfiles',
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'responsable_collectivity',
              'superviseur',
              'analyste',
            ],
          },
        },
        {
          path: '/dashboard/stats/departments',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-stats-departments" */ '@/views/SNU/Dashboards/Departments.vue'
            ),
          name: 'StatsDepartments',
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'superviseur',
              'analyste',
            ],
          },
        },
        {
          path: '/dashboard/stats/collectivities',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-stats-collectivities" */ '@/views/SNU/Dashboards/Collectivities.vue'
            ),
          name: 'StatsCollectivities',
          meta: {
            roles: ['admin', 'analyste'],
          },
        },
        {
          path: '/dashboard/stats/domaines',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-stats-domaines" */ '@/views/SNU/Dashboards/Domaines.vue'
            ),
          name: 'StatsDomaines',
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/missions',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-missions" */ '@/views/SNU/Missions.vue'
            ),
          name: 'DashboardMissions',
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'superviseur',
              'responsable',
            ],
          },
        },
        {
          path: '/dashboard/mission/:id',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-mission-view" */ '@/views/SNU/Mission.vue'
            ),
          name: 'DashboardMissionView',
          props: (route) => ({ id: parseInt(route.params.id) }),
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'superviseur',
              'responsable',
            ],
          },
        },
        {
          path: '/dashboard/mission/:id/activities',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-mission-activities" */ '@/views/SNU/Mission.vue'
            ),
          name: 'DashboardMissionActivities',
          props: (route) => ({
            id: parseInt(route.params.id),
            tab: 'activities',
          }),
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'superviseur',
              'responsable',
            ],
          },
        },
        {
          path: '/dashboard/mission/:id/participations',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-mission-participations" */ '@/views/SNU/Mission.vue'
            ),
          name: 'DashboardMissionParticipations',
          props: (route) => ({
            id: parseInt(route.params.id),
            tab: 'participations',
          }),
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'superviseur',
              'responsable',
            ],
          },
        },
        {
          path: '/dashboard/structure/:structureId/missions/add',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-mission-add" */ '@/views/SNU/MissionAdd.vue'
            ),
          name: 'MissionFormAdd',
          props: (route) => ({
            structureId: parseInt(route.params.structureId),
            mission: { ...route.params.mission },
          }),
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'superviseur',
              'responsable',
            ],
          },
        },
        {
          path: '/dashboard/mission/:id/edit',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-mission-edit" */ '@/views/SNU/MissionEdit.vue'
            ),
          name: 'MissionFormEdit',
          props: (route) => ({ id: parseInt(route.params.id) }),
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'superviseur',
              'responsable',
            ],
          },
        },
        {
          path: '/dashboard/structures',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-structures" */ '@/views/SNU/Structures.vue'
            ),
          name: 'DashboardStructures',
          meta: {
            roles: ['admin', 'referent', 'referent_regional', 'superviseur'],
          },
        },
        {
          path: '/dashboard/structure/add',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-structure-add" */ '@/views/SNU/StructureForm.vue'
            ),
          name: 'StructureFormAdd',
          props: { mode: 'add' },
          meta: {
            roles: ['admin', 'referent', 'referent_regional', 'superviseur'],
          },
        },
        {
          path: '/dashboard/structure/:id',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-structure-view" */ '@/views/SNU/Structure.vue'
            ),
          name: 'Structure',
          props: (route) => ({ id: parseInt(route.params.id) }),
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'superviseur',
              'responsable',
            ],
          },
        },
        {
          path: '/dashboard/structure/:id/activities',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-structure-activities" */ '@/views/SNU/Structure.vue'
            ),
          name: 'Structure',
          props: (route) => ({
            id: parseInt(route.params.id),
            tab: 'activities',
          }),
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'superviseur',
              'responsable',
            ],
          },
        },
        {
          path: '/dashboard/structure/:id/missions',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-structure-missions" */ '@/views/SNU/Structure.vue'
            ),
          name: 'Structure',
          props: (route) => ({
            id: parseInt(route.params.id),
            tab: 'missions',
          }),
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'superviseur',
              'responsable',
            ],
          },
        },
        {
          path: '/dashboard/structure/:id/edit',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-structure-edit" */ '@/views/SNU/StructureForm.vue'
            ),
          name: 'StructureFormEdit',
          props: (route) => ({ mode: 'edit', id: parseInt(route.params.id) }),
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'superviseur',
              'responsable',
            ],
          },
        },
        {
          path: '/dashboard/structure/:id/members',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-structure-members" */ '@/views/SNU/StructureMembers.vue'
            ),
          name: 'StructureMembers',
          props: (route) => ({ id: parseInt(route.params.id) }),
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'superviseur',
              'responsable',
            ],
          },
        },
        {
          path: '/dashboard/structure/:id/members/add',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-structure-members-add" */ '@/views/SNU/StructureMembersAdd.vue'
            ),
          name: 'StructureMembersAdd',
          props: (route) => ({ id: parseInt(route.params.id) }),
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'superviseur',
              'responsable',
            ],
          },
        },
        {
          path: '/dashboard/profiles',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-profiles" */ '@/views/SNU/Profiles.vue'
            ),
          name: 'DashboardProfiles',
          meta: {
            roles: ['admin', 'referent', 'referent_regional'],
          },
        },
        {
          path: '/dashboard/profiles/referents',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-profiles-referents" */ '@/views/SNU/ProfilesReferents.vue'
            ),
          name: 'DashboardProfilesReferent',
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/profiles/responsables',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-profiles-responsables" */ '@/views/SNU/ProfilesResponsables.vue'
            ),
          name: 'DashboardProfilesReferent',
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/profile/:id',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-profile-view" */ '@/views/SNU/Profile.vue'
            ),
          name: 'Profile',
          props: (route) => ({ id: parseInt(route.params.id) }),
          meta: {
            roles: ['admin', 'referent', 'referent_regional'],
          },
        },
        {
          path: '/dashboard/profile/:id/activities',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-profile-activities" */ '@/views/SNU/Profile.vue'
            ),
          name: 'DashboardProfileActivities',
          props: (route) => ({
            id: parseInt(route.params.id),
            tab: 'activities',
          }),
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'superviseur',
              'responsable',
            ],
          },
        },
        {
          path: '/dashboard/profile/:role/add',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-profile-role-add" */ '@/views/SNU/ProfileForm.vue'
            ),
          name: 'ProfileFormAdd',
          props: (route) => ({ mode: 'add', role: route.params.role }),
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/profile/:id/edit',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-profile-edit" */ '@/views/SNU/ProfileForm.vue'
            ),
          name: 'ProfileFormEdit',
          props: (route) => ({ mode: 'edit', id: parseInt(route.params.id) }),
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/participations',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-participations" */ '@/views/SNU/Participations.vue'
            ),
          name: 'DashboardParticipations',
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'superviseur',
              'responsable',
            ],
          },
        },
        {
          path: '/dashboard/participations/trouver-des-benevoles',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/trouver-des-benevoles" */ '@/views/SNU/TrouverDesBenevoles.vue'
            ),
          name: 'TrouverDesBenevoles',
          meta: {
            roles: ['responsable'],
          },
        },
        {
          path: '/dashboard/participation/:id',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-participation-view" */ '@/views/SNU/Participation.vue'
            ),
          name: 'DashboardParticipationView',
          props: (route) => ({ id: parseInt(route.params.id) }),
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'superviseur',
              'responsable',
            ],
          },
        },
        {
          path: '/dashboard/participation/:id/activities',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-participation-activities" */ '@/views/SNU/Participation.vue'
            ),
          name: 'DashboardParticipationActivities',
          props: (route) => ({
            id: parseInt(route.params.id),
            tab: 'activities',
          }),
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'superviseur',
              'responsable',
            ],
          },
        },
        {
          path: '/dashboard/trash/structures',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-trash-structures" */ '@/views/SNU/TrashStructures.vue'
            ),
          name: 'TrashStructures',
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/trash/missions',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-trash-missions" */ '@/views/SNU/TrashMissions.vue'
            ),
          name: 'TrashMissions',
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/trash/participations',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-trash-participations" */ '@/views/SNU/TrashParticipations.vue'
            ),
          name: 'TrashParticipations',
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/ressources',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-ressources" */ '@/views/SNU/Ressources.vue'
            ),
          name: 'Ressources',
          meta: {
            roles: ['referent', 'responsable'],
          },
        },
        {
          path: '/dashboard/faq/add',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-faq-add" */ '@/views/SNU/FaqForm.vue'
            ),
          name: 'FaqFormAdd',
          props: { mode: 'add' },
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/faq/:id/edit',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-faq-edit" */ '@/views/SNU/FaqForm.vue'
            ),
          name: 'FaqFormEdit',
          props: (route) => ({ mode: 'edit', id: parseInt(route.params.id) }),
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/news',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-news" */ '@/views/SNU/News.vue'
            ),
          name: 'News',
          meta: {
            roles: [
              'admin',
              'referent',
              'referent_regional',
              'superviseur',
              'responsable',
            ],
          },
        },
        {
          path: '/dashboard/contents/faqs',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-contents-faqs" */ '@/views/SNU/Faqs.vue'
            ),
          name: 'Faqs',
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/contents/releases',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-contents-releases" */ '@/views/SNU/Releases.vue'
            ),
          name: 'Releases',
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/contents/pages',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-contents-pages" */ '@/views/SNU/Pages.vue'
            ),
          name: 'Pages',
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/contents/collectivities',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-contents-collectivities" */ '@/views/SNU/Collectivities.vue'
            ),
          name: 'Collectivities',
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/contents/documents',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-contents-documents" */ '@/views/SNU/Documents.vue'
            ),
          name: 'Documents',
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/contents/thematiques',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-contents-thematiques" */ '@/views/SNU/Thematiques.vue'
            ),
          name: 'Thematiques',
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/contents/mission-templates',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-contents-mission-templates" */ '@/views/SNU/MissionTemplates.vue'
            ),
          name: 'MissionTemplates',
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/contents/tags',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-contents-tags" */ '@/views/SNU/Tags.vue'
            ),
          name: 'Tags',
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/activities',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-activities" */ '@/views/SNU/Activities.vue'
            ),
          name: 'Activities',
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/release/add',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-release-add" */ '@/views/SNU/ReleaseForm.vue'
            ),
          name: 'ReleaseFormAdd',
          props: { mode: 'add' },
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/release/:id/edit',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-release-edit" */ '@/views/SNU/ReleaseForm.vue'
            ),
          name: 'ReleaseFormEdit',
          props: (route) => ({ mode: 'edit', id: parseInt(route.params.id) }),
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/page/add',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-page-add" */ '@/views/SNU/PageForm.vue'
            ),
          name: 'PageFormAdd',
          props: { mode: 'add' },
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/page/:id/edit',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-page-edit" */ '@/views/SNU/PageForm.vue'
            ),
          name: 'PageFormEdit',
          props: (route) => ({ mode: 'edit', id: parseInt(route.params.id) }),
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/collectivity/add',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-collectivity-add" */ '@/views/SNU/CollectivityForm.vue'
            ),
          name: 'CollectivityFormAdd',
          props: { mode: 'add' },
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/collectivity/:id/edit',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-collectivity-edit" */ '@/views/SNU/CollectivityForm.vue'
            ),
          name: 'CollectivityFormEdit',
          props: (route) => ({ mode: 'edit', id: parseInt(route.params.id) }),
          meta: {
            roles: ['admin', 'responsable_collectivity'],
          },
        },
        {
          path: '/dashboard/document/add',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-document-add" */ '@/views/SNU/DocumentForm.vue'
            ),
          name: 'DocumentFormAdd',
          props: { mode: 'add' },
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/document/:id/edit',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-document-edit" */ '@/views/SNU/DocumentForm.vue'
            ),
          name: 'DocumentFormEdit',
          props: (route) => ({ mode: 'edit', id: parseInt(route.params.id) }),
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/thematique/add',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-thematique-add" */ '@/views/SNU/ThematiqueForm.vue'
            ),
          name: 'ThematiqueFormAdd',
          props: { mode: 'add' },
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/thematique/:id/edit',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-thematique-edit" */ '@/views/SNU/ThematiqueForm.vue'
            ),
          name: 'ThematiqueFormEdit',
          props: (route) => ({ mode: 'edit', id: parseInt(route.params.id) }),
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/mission-template/add',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-mission-template-add" */ '@/views/SNU/MissionTemplateForm.vue'
            ),
          name: 'MissionTemplateFormAdd',
          props: { mode: 'add' },
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/mission-template/:id/edit',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-mission-template-edit" */ '@/views/SNU/MissionTemplateForm.vue'
            ),
          name: 'MissionTemplateFormEdit',
          props: (route) => ({ mode: 'edit', id: parseInt(route.params.id) }),
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/tag/add',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-tag-add" */ '@/views/SNU/TagForm.vue'
            ),
          name: 'TagFormAdd',
          props: { mode: 'add' },
          meta: {
            roles: ['admin'],
          },
        },
        {
          path: '/dashboard/tag/:id/edit',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/dashboard-tag-edit" */ '@/views/SNU/TagForm.vue'
            ),
          name: 'TagFormEdit',
          props: (route) => ({ mode: 'edit', id: parseInt(route.params.id) }),
          meta: {
            roles: ['admin'],
          },
        },
      ],
    },
    {
      path: '/user',
      component: LayoutProfile,
      redirect: '/user/infos',
      children: [
        {
          path: '/user/infos',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/front-user-infos" */ '@/views/Front/UserInfos.vue'
            ),
          name: 'FrontUserInfos',
        },
        {
          path: '/user/preferences',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/front-user-preferences" */ '@/views/Front/UserPreferences.vue'
            ),
          name: 'FrontUserPreferences',
        },
        {
          path: '/user/settings',
          component: () =>
            import(
              /* webpackChunkName: "assets/js/front-user-settings" */ '@/views/Front/UserSettings.vue'
            ),
          name: 'FrontUserSettings',
        },
      ],
    },
    {
      path: '/user/missions',
      component: FrontUserMissions,
      name: 'FrontUserMissions',
    },
    {
      path: '/missions',
      component: Missions,
      name: 'FrontMissions',
    },
    {
      path: '/missions/:id',
      component: () =>
        import(
          /* webpackChunkName: "assets/js/mission" */ '@/views/Mission.vue'
        ),
      name: 'Mission',
      props: (route) => ({ id: parseInt(route.params.id) }),
    },
    {
      path: '/territoires',
      component: () =>
        import(
          /* webpackChunkName: "assets/js/territories" */ '@/views/Front/Territories.vue'
        ),
      name: 'Territories',
    },
    {
      path: '/territoires/:slug',
      component: () =>
        import(
          /* webpackChunkName: "assets/js/collectivites-slug" */ '@/views/Front/Collectivity.vue'
        ),
      name: 'CollectivitySlug',
      props: (route) => ({ slug: route.params.slug }),
    },
    {
      path: '/collectivite',
      component: () =>
        import(
          /* webpackChunkName: "assets/js/collectivite-slug" */ '@/views/Front/CollectivityLandindPage.vue'
        ),
      name: 'CollectiviteLandindPage',
    },
    {
      path: '/thematiques/:slug',
      component: () =>
        import(
          /* webpackChunkName: "assets/js/thematiques-slug" */ '@/views/Front/Thematique.vue'
        ),
      name: 'ThematiqueSlug',
      props: (route) => ({ slug: route.params.slug }),
    },
    { path: '/403', component: Forbidden, name: 'Forbidden' },
    { path: '*', component: NotFound, name: 'NotFound' },
    {
      path: '/messages',
      component: Messages,
      meta: { requiresAuth: true },
    },
    {
      path: '/messages/:id',
      component: Messages,
      name: 'messagesId',
      props: (route) => ({ id: route.params.id }),
      meta: { requiresAuth: true },
    },
  ],
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
})
