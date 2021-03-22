export default ({ app, store }) => {
  app.router.afterEach((to, from) => {
    console.log(to)
    store.commit('volet/hide')
  })
}
