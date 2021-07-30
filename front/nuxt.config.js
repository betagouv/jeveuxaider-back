import axios from 'axios'

export default {
  server: {
    host: process.env.HOST || 'localhost', // default: localhost,
  },
  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title:
      'Devenez bénévole dans une association en quelques clics | JeVeuxAider.gouv.fr',
    htmlAttrs: {
      lang: 'fr',
    },
    meta: [
      { charset: 'utf-8' },
      {
        name: 'viewport',
        content: 'width=device-width, initial-scale=1',
      },
      {
        hid: 'description',
        name: 'description',
        content:
          "Trouvez une mission de bénévolat dans une association, organisation publique ou une commune, partout en France, sur le terrain ou à distance. 50 000 places disponibles dans 10 domaines d'action : solidarité, insertion, éducation, environnement, santé, sport, culture ...",
      },
      process.env.API_URL !== 'https://www.jeveuxaider.gouv.fr'
        ? { name: 'robots', content: 'noindex' }
        : {},
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.png' },
      {
        rel: 'preconnect',
        href: 'https://gqlg3qh7po-dsn.algolia.net',
        crossorigin: 'anonymous',
      },
      {
        rel: 'preconnect',
        href: 'https://static.axept.io',
        crossorigin: 'anonymous',
      },
      {
        rel: 'preconnect',
        href: 'https://client.axept.io',
        crossorigin: 'anonymous',
      },
      {
        rel: 'preconnect',
        href: 'https://client.crisp.chat',
        crossorigin: 'anonymous',
      },
    ],
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: ['@/assets/css/main.sass'],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    '@/plugins/element-ui.js',
    '@/plugins/axios',
    '@/plugins/router',
    '@/plugins/vue-libs.client.js',
    '@/plugins/numeral.js',
    '@/plugins/api.js',
    '@/plugins/vue-filters.js',
    // '@/plugins/axeptio.client.js',
    '@/plugins/apiengagement.client.js',
    '@/plugins/atinternet.client.js',
    { src: '~/plugins/vue-cropper.js', mode: 'client' },
    '@/plugins/plausible.client.js',
  ],

  router: {
    middleware: 'set-utm',
  },

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    '@nuxtjs/eslint-module',
    '@nuxtjs/tailwindcss',
    '@aceforth/nuxt-optimized-images',
    'nuxt-compress',
    '@nuxtjs/device',
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    '@nuxtjs/axios',
    '@nuxtjs/sitemap',
    'cookie-universal-nuxt',
    'nuxt-lazy-load',
    'portal-vue/nuxt',
    '@nuxtjs/dayjs',
    '@nuxtjs/redirect-module',
    '@nuxtjs/sentry',
    'cookie-universal-nuxt',
    'vue-scrollto/nuxt',
    '@nuxtjs/gtm',
  ],

  gtm: {
    id: 'GTM-5S3DCV6',
    enabled: true,
  },

  dayjs: {
    locales: ['fr'],
    defaultLocale: 'fr',
    plugins: [
      'relativeTime', // import 'dayjs/plugin/utc'
      'customParseFormat',
    ],
  },

  optimizedImages: {
    optimizeImages: true,
    optimizeImagesInDev: false,
    svgo: {
      plugins: [{ removeViewBox: false }],
    },
  },

  'nuxt-compress': {
    gzip: {
      cache: true,
    },
    brotli: {
      threshold: 10240,
    },
  },

  sentry: {
    dsn: process.env.SENTRY_DSN, // Enter your project's DSN here
    config: {
      lazy: true,
    }, // Additional config
  },

  tailwindcss: {
    config: {
      purge: {
        options: {
          // Whitelisting some classes to avoid purge
          safelist: [
            'bg-green-700',
            'bg-red-600',
            'bg-blue-900',
            'bg-purple-800',
          ],
        },
      },
    },
  },

  redirect: [
    /* eslint-disable */
    {
      from: '^/missions(?!-benevolat|/)(.*)$',
      to: '/missions-benevolat$1',
      statusCode: 301,
    },
    {
      from: '^/missions(?!-benevolat)/(.*)$',
      to: '/missions-benevolat/$1',
      statusCode: 301,
    },
  ],

  privateRuntimeConfig: {
    axios: {
      baseURL: `${process.env.API_URL}/api`,
    },
  },

  publicRuntimeConfig: {
    algolia: {
      placesAppId: process.env.ALGOLIA_PLACES_APP_ID,
      placesApiKey: process.env.ALGOLIA_PLACES_API_KEY,
    },
    apiUrl: process.env.API_URL,
    appUrl: process.env.APP_URL,
    app: {
      modeLight: process.env.MODE_LIGHT,
    },
    axios: {
      browserBaseURL: `${process.env.API_URL}/api`,
    },
    franceConnect: process.env.FRANCE_CONNECT,
    oauth: {
      clientId: process.env.OAUTH_CLIENT_ID,
      clientSecret: process.env.OAUTH_CLIENT_SECRET,
    },
    apieng: {
      key: process.env.APIENG_KEY,
      tracking_key: process.env.APIENG_TRACKING_KEY,
    },
    plausible: {
      site_id: process.env.PLAUSIBLE_SITE_ID,
      base_url: process.env.PLAUSIBLE_BASE_URL,
      token: process.env.PLAUSIBLE_TOKEN,
    },
    google: {
      places: process.env.GOOGLE_PLACE_API_KEY
    }
  },

  env: {
    algolia: {
      appId: process.env.ALGOLIA_APP_ID,
      searchKey: process.env.ALGOLIA_SEARCH_KEY,
      index: process.env.ALGOLIA_INDEX,
      skills: process.env.ALGOLIA_SKILLS_INDEX,
    },
  },

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
    analyze: process.env.NODE_ENV !== 'production',
    extractCSS: process.env.NODE_ENV === 'production',
    transpile: [
      'vue-instantsearch',
      'instantsearch.js/es',
      'numeral',
      'vue-clamp',
      'resize-detector',
      'vue-cropperjs',
    ],
  },

  render: {
    bundleRenderer: {
      shouldPreload: (file, type) => {
        return ['script', 'style', 'font'].includes(type)
      },
    },
    fallback: {
      static: {
        // Avoid sending 404 for these extensions
        // https://github.com/nuxt/nuxt.js/issues/5493
        handlers: {
          '.jpg': false,
          '.jpeg': false,
          '.png': false,
          '.svg': false,
        },
      },
    },
  },

  sitemap: () => {
    return {
      hostname: 'https://www.jeveuxaider.gouv.fr',
      gzip: true,
      exclude: ['/**'],
      routes: async () => {
        const { data } = await axios.get(`${process.env.API_URL}/api/sitemap`)
        return data
      },
    }
  },
}
