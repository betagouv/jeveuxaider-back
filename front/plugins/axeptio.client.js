/* eslint-disable */

export default ({ app, $config }) => {
  document.addEventListener('DOMContentLoaded', function () {

    let cookieDomains = [
      document.domain,
      document.domain.replace('www', '')
    ]
    // Remove duplicates
    cookieDomains = Array.from(new Set(cookieDomains));
    console.log(cookieDomains)

    const el = document.createElement('script')
    const crispWebsiteId = '4b843a95-8a0b-4274-bfd5-e81cbdc188ac'

    el.setAttribute('src', 'https://static.axept.io/sdk.js')
    el.setAttribute('type', 'text/javascript')
    el.setAttribute('async', true)
    el.setAttribute('data-id', '606dd246669e09466761ef93')
    el.setAttribute('data-cookies-version', 'jeveuxaider-base')
    if (document.body !== null) {
      document.body.appendChild(el)
    }

    // eslint-disable-next-line no-void
    void 0 === window._axcb && (window._axcb = [])
    window._axcb.push(function (axeptio) {
      axeptio.on('cookies:complete', function (choices) {
        if (choices.google_analytics) {
          launchGoogleAnalytics()
        } else {
          removeGoogleAnalytics()
        }

        if (choices.crisp) {
          launchCrisp()
        } else {
          removeCrisp()
        }
      })
    })

    const launchGoogleAnalytics = () => {
      console.log('launchGoogleAnalytics')

      if (!$config.googleAnalytics.id) {
        return
      }
      ; (function (i, s, o, g, r, a, m) {
        i.GoogleAnalyticsObject = r
          ; (i[r] =
            i[r] ||
            function () {
              ; (i[r].q = i[r].q || []).push(arguments)
            }),
            (i[r].l = 1 * new Date())
          ; (a = s.createElement(o)), (m = s.getElementsByTagName(o)[0])
        a.async = 1
        a.src = g
        m.parentNode.insertBefore(a, m)
      })(
        window,
        document,
        'script',
        'https://www.google-analytics.com/analytics.js',
        'ga'
      )

      /*
       ** Set the current page
       */
      ga('create', $config.googleAnalytics.id, 'auto')
      /*
       ** Every time the route changes (fired on initialization too)
       */
      app.router.afterEach((to, from) => {
        /*
         ** We tell Google Analytics to add a `pageview`
         */
        ga('set', 'page', to.fullPath)
        ga('send', 'pageview')
      })
    }

    const removeGoogleAnalytics = () => {
      console.log('removeGoogleAnalytics')

      if (window.ga) window.ga('remove');
      if (document.ga) document.ga('remove');

      cookieDomains.forEach((cookieDomain) => {
        app.$cookies.remove('_ga', {
          path: '/',
          domain: cookieDomain
        })
        app.$cookies.remove('_gid', {
          path: '/',
          domain: cookieDomain
        })
        app.$cookies.remove('_gat', {
          path: '/',
          domain: cookieDomain
        })
      })
    }

    const launchCrisp = () => {
      console.log('launchCrisp')

      window.$crisp = []
      window.CRISP_WEBSITE_ID = crispWebsiteId
        ; (function () {
          const d = document
          const s = d.createElement('script')

          s.src = 'https://client.crisp.chat/l.js'
          s.async = 1
          d.getElementsByTagName('head')[0].appendChild(s)
        })()
    }

    const removeCrisp = () => {
      console.log('launchCrisp')

      if (window.$crisp) {
        delete (window.$crisp)
      }

      cookieDomains.forEach((cookieDomain) => {
        app.$cookies.remove(`crisp-client%2Fsession%2F${crispWebsiteId}`, {
          path: '/',
          domain: cookieDomain
        })
        app.$cookies.remove(`crisp-client%2Fsocket%2F${crispWebsiteId}`, {
          path: '/',
          domain: cookieDomain
        })
      })

      const el = document.getElementById('crisp-chatbox')
      if (el) {
        el.remove()
      }
    }
  })
}
