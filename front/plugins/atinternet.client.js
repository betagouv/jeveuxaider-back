/* eslint-disable */
export default () => {
  document.addEventListener('DOMContentLoaded', function () {
    window.ATInternet = {}
    ;(function () {
      const d = document
      const s = d.createElement('script')

      s.src = '//tag.aticdn.net/610648/smarttag.js'
      s.async = 1
      d.getElementsByTagName('head')[0].appendChild(s)
    })()

    window.ATInternet.onTrackerLoad = () => {
      const tag = new ATInternet.Tracker.Tag()
      tag.page.set({})
      tag.dispatch()
    }
  })
}
