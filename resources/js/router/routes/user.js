const routeOptions = [
  {
    path: '/login',
    name: 'Login',
    meta: { layout: 'two-cols', requiresAnonymous: true },
  },
  {
    path: '/logout',
    name: 'Logout',
    meta: { layout: 'two-cols', requiresAuth: true },
  },
  {
    path: '/password/forgot',
    name: 'PasswordForgot',
    meta: { layout: 'two-cols', requiresAnonymous: true },
  },
  {
    path: '/password/reset/:token',
    name: 'PasswordReset',
    meta: { layout: 'two-cols', requiresAnonymous: true },
  },
  {
    path: '/user/infos',
    name: 'UserInfos',
    meta: { layout: 'profile', requiresAuth: true },
  },
  {
    path: '/user/preferences',
    name: 'UserPreferences',
    meta: { layout: 'profile', requiresAuth: true },
  },
  {
    path: '/user/settings',
    name: 'UserSettings',
    meta: { layout: 'profile', requiresAuth: true },
  },
  {
    path: '/user/missions',
    name: 'UserMissions',
    meta: { requiresAuth: true },
  },
  { path: '/messages', name: 'UserMessages', meta: { requiresAuth: true } },
  {
    path: '/messages/:id',
    name: 'UserMessages',
    singleName: 'UserMessage',
    meta: { requiresAuth: true },
    props: (route) => ({ id: route.params.id }),
  },
]

export default routeOptions.map((route) => {
  return {
    ...route,
    name: route.singleName ? route.singleName : route.name,
    component: () =>
      import(
        /* webpackChunkName: "assets/js/[request]" */ `@/views/user/${route.name}.vue`
      ),
  }
})
