const routeOptions = [
  {
    path: '/register/volontaire',
    name: 'RegisterVolontaire',
    meta: { requiresAnonymous: true },
  },
  {
    path: '/register/volontaire/step/preferences',
    name: 'RegisterVolontaireStepPreferences',
    meta: { requiredAuth: true, layout: 'register-steps' },
  },
  {
    path: '/register/volontaire/step/infos',
    name: 'RegisterVolontaireStepInfos',
    meta: { requiredAuth: true, layout: 'register-steps' },
  },
  {
    path: '/register/responsable',
    name: 'RegisterResponsable',
    meta: { requiresAnonymous: true },
  },
  {
    path: '/register/responsable/step/norole',
    name: 'RegisterResponsableStepNoRole',
    meta: { requiresAuth: true, layout: 'register-steps' },
  },
  {
    path: '/register/responsable/step/profile',
    name: 'RegisterResponsableStepProfile',
    meta: { requiresAuth: true, layout: 'register-steps' },
  },
  {
    path: '/register/responsable/step/structure',
    name: 'RegisterResponsableStepStructure',
    meta: { requiresAuth: true, layout: 'register-steps' },
  },
  {
    path: '/register/responsable/step/address',
    name: 'RegisterResponsableStepAddress',
    meta: { requiresAuth: true, layout: 'register-steps' },
  },
  {
    path: '/register/responsable/step/other',
    name: 'RegisterResponsableStepOther',
    meta: { requiresAuth: true, layout: 'register-steps' },
  },
  {
    path: '/register/invitation',
    name: 'RegisterInvitation',
    meta: { requiresAnonymous: true, layout: 'two-cols' },
  },
]

export default routeOptions.map((route) => {
  return {
    ...route,
    component: () =>
      import(
        /* webpackChunkName: "assets/js/[request]" */ `@/views/register/${route.name}.vue`
      ),
  }
})
