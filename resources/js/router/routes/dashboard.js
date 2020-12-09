const routeOptions = [
  {
    path: '/dashboard',
    name: 'Dashboard',
    meta: {
      roles: [
        'admin',
        'referent',
        'referent_regional',
        'superviseur',
        'responsable',
        'analyste',
      ],
    },
  },
  {
    path: '/dashboard/stats/structures',
    name: 'DashboardStatsStructures',
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
    path: '/dashboard/stats/missions',
    name: 'DashboardStatsMissions',
    meta: {
      roles: [
        'admin',
        'referent',
        'referent_regional',
        'superviseur',
        'analyste',
        'responsable',
      ],
    },
  },
  {
    path: '/dashboard/stats/participations',
    name: 'DashboardStatsParticipations',
    meta: {
      roles: [
        'admin',
        'referent',
        'referent_regional',
        'superviseur',
        'analyste',
        'responsable',
      ],
    },
  },
  {
    path: '/dashboard/collectivity/stats/missions',
    name: 'DashboardStatsMissions',
    singleName: 'DashboardCollectivityStatsMissions',
    meta: {
      roles: [
        'admin',
        'referent',
        'referent_regional',
        'superviseur',
        'analyste',
        'responsable',
      ],
    },
  },
  {
    path: '/dashboard/collectivity/stats/participations',
    name: 'DashboardStatsParticipations',
    singleName: 'DashboardCollectivityStatsParticipations',
    meta: {
      roles: [
        'admin',
        'referent',
        'referent_regional',
        'superviseur',
        'analyste',
        'responsable',
      ],
    },
  },
  {
    path: '/dashboard/stats/profiles',
    name: 'DashboardStatsProfiles',
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
    path: '/dashboard/stats/departments',
    name: 'DashboardStatsDepartments',
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
    name: 'DashboardStatsCollectivities',
    meta: {
      roles: ['admin', 'analyste', 'referent', 'referent_regional'],
    },
  },
  {
    path: '/dashboard/stats/domaines',
    name: 'DashboardStatsDomaines',
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/missions',
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
    name: 'DashboardMission',
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
    path: '/dashboard/mission/:id/history',
    name: 'DashboardMission',
    singleName: 'DashboardMissionHistory',
    props: (route) => ({
      id: parseInt(route.params.id),
      tab: 'history',
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
    name: 'DashboardMission',
    singleName: 'DashboardMissionParticipations',
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
    name: 'DashboardMissionFormAdd',
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
    name: 'DashboardMissionFormEdit',
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
    name: 'DashboardStructures',
    meta: {
      roles: ['admin', 'referent', 'referent_regional', 'superviseur'],
    },
  },
  {
    path: '/dashboard/structure/add',
    name: 'DashboardStructureForm',
    singleName: 'DashboardStructureFormAdd',
    props: { mode: 'add' },
    meta: {
      roles: ['admin', 'referent', 'referent_regional', 'superviseur'],
    },
  },
  {
    path: '/dashboard/structure/:id',
    name: 'DashboardStructure',
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
    path: '/dashboard/structure/:id/history',
    name: 'DashboardStructure',
    singleName: 'DashboardStructureHistory',
    props: (route) => ({
      id: parseInt(route.params.id),
      tab: 'history',
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
    name: 'DashboardStructure',
    singleName: 'DashboardStructureMissions',
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
    name: 'DashboardStructureForm',
    singleName: 'DashboardStructureFormEdit',
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
    name: 'DashboardStructureMembers',
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
    name: 'DashboardStructureMembersAdd',
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
    name: 'DashboardProfiles',
    meta: {
      roles: ['admin', 'referent', 'referent_regional'],
    },
  },
  {
    path: '/dashboard/profiles/referents-departements',
    name: 'DashboardProfilesReferentsDepartements',
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/profiles/referents-regions',
    name: 'DashboardProfilesReferentsRegions',
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/profiles/responsables',
    name: 'DashboardProfilesResponsables',
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/profile/:id',
    name: 'DashboardProfile',
    props: (route) => ({ id: parseInt(route.params.id) }),
    meta: {
      roles: ['admin', 'referent', 'referent_regional'],
    },
  },
  {
    path: '/dashboard/profile/:id/activities',
    name: 'DashboardProfile',
    singleName: 'DashboardProfileActivities',
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
    path: '/dashboard/profile/:id/history',
    name: 'DashboardProfile',
    singleName: 'DashboardProfileHistory',
    props: (route) => ({
      id: parseInt(route.params.id),
      tab: 'history',
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
    name: 'DashboardProfileForm',
    singleName: 'DashboardProfileFormAdd',
    props: (route) => ({ mode: 'add', role: route.params.role }),
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/profile/:id/edit',
    name: 'DashboardProfileForm',
    singleName: 'DashboardProfileFormEdit',
    props: (route) => ({ mode: 'edit', id: parseInt(route.params.id) }),
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/participations',
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
    path: '/dashboard/mission/:id/trouver-des-benevoles',
    name: 'DashboardMissionTrouverDesBenevoles',
    props: (route) => ({ id: parseInt(route.params.id) }),
    meta: {
      roles: ['admin', 'responsable'],
    },
  },
  {
    path: '/dashboard/participation/:id',
    name: 'DashboardParticipation',
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
    path: '/dashboard/participation/:id/history',
    name: 'DashboardParticipation',
    singleName: 'DashboardParticipationHistory',
    props: (route) => ({
      id: parseInt(route.params.id),
      tab: 'history',
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
    name: 'DashboardTrashStructures',
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/trash/missions',
    name: 'DashboardTrashMissions',
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/trash/participations',
    name: 'DashboardTrashParticipations',
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/ressources',
    name: 'DashboardRessources',
    meta: {
      roles: ['referent', 'responsable'],
    },
  },
  {
    path: '/dashboard/faq/add',
    name: 'DashboardFaqForm',
    singleName: 'DashboardFaqFormAdd',
    props: { mode: 'add' },
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/faq/:id/edit',
    name: 'DashboardFaqForm',
    singleName: 'DashboardFaqFormEdit',
    props: (route) => ({ mode: 'edit', id: parseInt(route.params.id) }),
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/news',
    name: 'DashboardNews',
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
    name: 'DashboardFaqs',
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/contents/releases',
    name: 'DashboardReleases',
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/contents/pages',
    name: 'DashboardPages',
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/collectivities',
    name: 'DashboardCollectivities',
    meta: {
      roles: ['admin', 'referent', 'referent_regional'],
    },
  },
  {
    path: '/dashboard/collectivity',
    name: 'Dashboard',
    singleName: 'DashboardCollectivityMain',
    meta: {
      roles: ['responsable'],
    },
  },
  {
    path: '/dashboard/collectivity/:id',
    name: 'DashboardCollectivity',
    props: (route) => ({ id: parseInt(route.params.id) }),
    meta: {
      roles: ['admin', 'referent', 'referent_regional'],
    },
  },
  {
    path: '/dashboard/collectivity/:id/history',
    name: 'DashboardCollectivity',
    singleName: 'DashboardCollectivityHistory',
    props: (route) => ({
      id: parseInt(route.params.id),
      tab: 'history',
    }),
    meta: {
      roles: ['admin', 'referent', 'referent_regional'],
    },
  },
  {
    path: '/dashboard/contents/departments',
    name: 'DashboardDepartments',
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/contents/documents',
    name: 'DashboardDocuments',
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/contents/thematiques',
    name: 'DashboardThematiques',
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/contents/mission-templates',
    name: 'DashboardMissionTemplates',
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/contents/tags',
    name: 'DashboardTags',
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/activities',
    name: 'DashboardActivities',
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/release/add',
    name: 'DashboardReleaseForm',
    singleName: 'DashboardReleaseFormAdd',
    props: { mode: 'add' },
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/release/:id/edit',
    name: 'DashboardReleaseForm',
    singleName: 'DashboardReleaseFormEdit',
    props: (route) => ({ mode: 'edit', id: parseInt(route.params.id) }),
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/page/add',
    name: 'DashboardPageForm',
    singleName: 'DashboardPageFormAdd',
    props: { mode: 'add' },
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/page/:id/edit',
    name: 'DashboardPageForm',
    singleName: 'DashboardPageFormEdit',
    props: (route) => ({ mode: 'edit', id: parseInt(route.params.id) }),
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/collectivity/add',
    name: 'DashboardCollectivityForm',
    singleName: 'DashboardCollectivityFormAdd',
    props: { mode: 'add' },
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/collectivity/:id/edit',
    name: 'DashboardCollectivityForm',
    singleName: 'DashboardCollectivityFormEdit',
    props: (route) => ({ mode: 'edit', id: parseInt(route.params.id) }),
    meta: {
      roles: ['admin', 'responsable'],
    },
  },
  {
    path: '/dashboard/document/add',
    name: 'DashboardDocumentForm',
    singleName: 'DashboardDocumentFormAdd',
    props: { mode: 'add' },
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/document/:id/edit',
    name: 'DashboardDocumentForm',
    singleName: 'DashboardDocumentFormEdit',
    props: (route) => ({ mode: 'edit', id: parseInt(route.params.id) }),
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/thematique/add',
    name: 'DashboardThematiqueForm',
    singleName: 'DashboardThematiqueFormAdd',
    props: { mode: 'add' },
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/thematique/:id/edit',
    name: 'DashboardThematiqueForm',
    singleName: 'DashboardThematiqueFormEdit',
    props: (route) => ({ mode: 'edit', id: parseInt(route.params.id) }),
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/mission-template/add',
    name: 'DashboardMissionTemplateForm',
    singleName: 'DashboardMissionTemplateFormAdd',
    props: { mode: 'add' },
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/mission-template/:id/edit',
    name: 'DashboardMissionTemplateForm',
    singleName: 'DashboardMissionTemplateFormEdit',
    props: (route) => ({ mode: 'edit', id: parseInt(route.params.id) }),
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/tag/add',
    name: 'DashboardTagForm',
    singleName: 'DashboardTagFormAdd',
    props: { mode: 'add' },
    meta: {
      roles: ['admin'],
    },
  },
  {
    path: '/dashboard/tag/:id/edit',
    name: 'DashboardTagForm',
    singleName: 'DashboardTagFormEdit',
    props: (route) => ({ mode: 'edit', id: parseInt(route.params.id) }),
    meta: {
      roles: ['admin'],
    },
  },
]

export default routeOptions.map((route) => {
  return {
    ...route,
    name: route.singleName ? route.singleName : route.name,
    meta: { ...route.meta, layout: 'dashboard', requiresAuth: true },
    component: () =>
      import(
        /* webpackChunkName: "assets/js/[request]" */ `@/views/dashboard/${route.name}.vue`
      ),
  }
})
