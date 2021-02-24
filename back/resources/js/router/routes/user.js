const routeOptions = [
  {
    path: '/login',
    name: 'Login',
    meta: { requiresAnonymous: true },
  },
  {
    path: '/logout',
    name: 'Logout',
    meta: { requiresAuth: true },
  },
  {
    path: '/password/forgot',
    name: 'PasswordForgot',
    meta: { requiresAnonymous: true },
  },
  {
    path: '/password/reset/:token',
    name: 'PasswordReset',
    meta: { requiresAnonymous: true },
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
  {
    path: '/messages',
    name: 'UserMessages',
    meta: { requiresAuth: true, layout: 'messages' },
  },
  {
    path: '/messages/:id',
    name: 'UserMessages',
    singleName: 'UserMessage',
    meta: { requiresAuth: true, layout: 'messages' },
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
