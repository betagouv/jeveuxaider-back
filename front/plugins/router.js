export default ({ app, store, $cookies, $nuxt }) => {
  app.router.afterEach((to, from) => {
    // console.log('after each to', to)
    if (to.query.utm_source) {
      // console.log('set cookie')
      $cookies.set('utm_source', to.query.utm_source, {
        path: '/',
        maxAge: 60 * 60 * 24 * 10, // 10 jours,
        domain: '.jeveuxaider.gouv.fr',
      })
      // console.log('cookie set')
    }
    store.commit('volet/hide')
  })

  // Todo: Utiliser dans tous les formulaires après la soumission ?
  app.router.pushPrevious = (fallback) => {
    if (app.context.from) {
      app.router.push(app.context.from)
    } else {
      app.router.push(fallback)
    }
  }
}
