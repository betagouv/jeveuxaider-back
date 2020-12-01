const routeOptions = [
  { path: '/', name: 'Homepage' },
  { path: '/missions', name: 'Missions' },
  { path: '/regles-de-securite', name: 'SecurityRules' },
  { path: '/politique-de-confidentialite', name: 'PolitiqueConfidentialite' },
  { path: '/faq', name: 'Faq' },
  { path: '/mentions-legales', name: 'Page', props: { id: 1 } },
  {
    path: '/conditions-generales-d-utilisation',
    name: 'Page',
    singleName: 'PageConditionsGeneralesUtilisation',
    props: { id: 2 },
  },
  {
    path: '/politique-de-confidentialite',
    name: 'Page',
    singleName: 'PagePolitiqueConfidentialite',
    props: { id: 3 },
  },
  {
    path: '/charte-reserve-civique',
    name: 'Page',
    singleName: 'PageCharteReserveCivique',
    props: { id: 4 },
  },
  { path: '/browser-outdated', name: 'BrowserOutdated', layout: 'two-cols' },
  { path: '/maintenance', name: 'Maintenance', layout: 'two-cols' },
  {
    path: '/missions/:id/:slug',
    name: 'Mission',
    props: (route) => ({
      id: parseInt(route.params.id),
      slug: parseInt(route.params.slug),
    }),
  },
  { path: '/collectivite', name: 'CollectivityLandingPage' },
  { path: '/territoires', name: 'Territoires' },
  {
    path: '/territoires/:slug',
    name: 'Collectivity',
    props: (route) => ({ slug: route.params.slug }),
    meta: { layout: 'no-header' },
  },
  {
    path: '/thematiques/:slug',
    name: 'Thematique',
    props: (route) => ({ slug: route.params.slug }),
    meta: { layout: 'no-header' },
  },
  { path: '/403', name: 'Forbidden' },
  { path: '*', name: 'NotFound' },
]

export default routeOptions.map((route) => {
  return {
    ...route,
    name: route.singleName ? route.singleName : route.name,
    component: () =>
      import(
        /* webpackChunkName: "assets/js/[request]" */ `@/views/public/${route.name}.vue`
      ),
  }
})
