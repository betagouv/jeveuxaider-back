export default () => {
  document.addEventListener('DOMContentLoaded', function () {
    window.$crisp = []
    window.CRISP_WEBSITE_ID = '4b843a95-8a0b-4274-bfd5-e81cbdc188ac'
    ;(function () {
      const d = document
      const s = d.createElement('script')

      s.src = 'https://client.crisp.chat/l.js'
      s.async = 1
      d.getElementsByTagName('head')[0].appendChild(s)
    })()
  })
}
