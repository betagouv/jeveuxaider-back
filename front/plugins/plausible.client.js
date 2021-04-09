// import Plausible from 'plausible-tracker'

// export default (context, inject) => {
//   const options = {
//     domain: 'jeveuxaider.gouv.fr',
//     hashMode: false,
//     trackLocalhost: false,
//     apiHost: 'https://plausible.io',
//   }
//   const plausible = Plausible(options)
//   plausible.enableAutoPageviews()
//   inject('plausible', plausible)
// }

export default () => {
  document.addEventListener('DOMContentLoaded', function () {
    // eslint-disable-next-line prettier/prettier
    ;(function () {
      console.log('plausible load')
      const d = document
      const s = d.createElement('script')

      s.defer = 1
      s.dataDomain = 'jeveuxaider.gouv.fr'
      s.src = 'https://plausible.io/js/plausible.js'
      s.async = 1
      d.getElementsByTagName('head')[0].appendChild(s)
      window.plausible =
        window.plausible ||
        function () {
          ;(window.plausible.q = window.plausible.q || []).push(arguments)
        }
    })()
  })
}
