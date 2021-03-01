export default {
  server: {
    host: process.env.HOST || 'localhost', // default: localhost,
  },
  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'JeVeuxAider | Trouver des missions de bénévolat',
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
          'Devenez bénévole et trouvez des missions en quelques clics près de chez vous ou à distance.',
      },
    ],
    link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.png' }],
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: ['@/assets/css/main.sass'],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    '@/plugins/element-ui.js',
    '@/plugins/axios',
    '@/plugins/vue-typed.client.js',
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    '@nuxtjs/eslint-module',
    '@nuxtjs/tailwindcss',
    '@aceforth/nuxt-optimized-images',
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: ['@nuxtjs/axios', 'cookie-universal-nuxt', 'nuxt-lazy-load'],

  optimizedImages: {
    optimizeImages: true,
    optimizeImagesInDev: true,
    svgo: {
      plugins: [{ removeViewBox: false }],
    },
  },

  privateRuntimeConfig: {
    axios: {
      baseURL: `${process.env.API_URL}/api`,
    },
  },

  publicRuntimeConfig: {
    apiUrl: process.env.API_URL,
    axios: {
      browserBaseURL: `${process.env.API_URL}/api`,
    },
    oauth: {
      clientId: process.env.OAUTH_CLIENT_ID,
      clientSecret: process.env.OAUTH_CLIENT_SECRET,
    },
    franceConnect: process.env.FRANCE_CONNECT,
  },

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
    analyze: false,
    extractCSS: process.env.NODE_ENV === 'production',
  },

  render: {
    bundleRenderer: {
      shouldPreload: (file, type) => {
        return ['script', 'style', 'font'].includes(type)
      },
    },
  },
}
