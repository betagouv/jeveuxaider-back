/* eslint-disable */
export default ({app}) => {
  if(process.env.NODE_ENV !== 'production') {
    return;
  }
  document.addEventListener('DOMContentLoaded', function () {
    window.ATInternet = {}
    ;(function () {
      const d = document
      const s = d.createElement('script')

      s.src = '//tag.aticdn.net/610648/smarttag.js'
      s.async = 1
      d.getElementsByTagName('head')[0].appendChild(s)
    })()

    app.router.afterEach((to, from) => {
      const tag = new window.ATInternet.Tracker.Tag()

      tag.page.set({
        name: to.name
      })
      tag.dispatch()
    })
  })
}
