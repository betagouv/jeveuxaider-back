const mix = require('laravel-mix')
const tailwindcss = require('tailwindcss')
const path = require('path')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/assets/js')
mix.copy('resources/fonts', 'public/assets/fonts')

mix.sass('resources/sass/app.sass', 'public/assets/css').options({
  processCssUrls: false,
  postCss: [tailwindcss('tailwind.config.js')],
})

if (mix.inProduction()) {
  mix.version()
}

let url = process.env.APP_URL.replace(/(^\w+:|^)\/\//, '')
mix.options({
  hmrOptions: {
    host: url,
    port: 8080, // Can't use 443 here because address already in use
  },
})

mix.webpackConfig({
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js'),
    },
  },
  externals: {
    moment: 'moment',
  },
})
