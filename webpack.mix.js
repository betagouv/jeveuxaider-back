const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

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

mix.js('resources/js/app.js', 'public/assets/js').version();

mix.js('resources/js/app-blade.js', 'public/assets/js').version();

mix.sass('resources/sass/app.sass', 'public/assets/css')
   .options({
      processCssUrls: false,
      postCss: [ tailwindcss('tailwind.config.js') ],
}).version();

/*
if (mix.inProduction()) {
    mix.version();
}
*/

mix.webpackConfig({
    resolve: {
      alias: {
        '@': path.resolve(
          __dirname,
          'resources/js'
        )
      }
    }
  })
