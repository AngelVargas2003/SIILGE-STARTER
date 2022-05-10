const mix = require('laravel-mix')
const path = require('path')
require('vuetifyjs-mix-extension')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
const webpack = {
  resolve: {
    alias: {
      '@resources': path.resolve(__dirname, 'resources/'),
      '@': path.resolve(__dirname, 'resources/js/src/'),
      '@themeConfig': path.resolve(__dirname, 'resources/js/themeConfig.js'),
      '@core': path.resolve(__dirname, 'resources/js/src/@core'),
      '@axios': path.resolve(__dirname, 'resources/js/src/plugins/axios.js'),
      '@user-variables': path.resolve(__dirname, 'resources/js/src/styles/variables.scss'),
      'apexcharts': path.resolve(__dirname, 'node_modules/apexcharts-clevision'),
    },
    fallback: {
      crypto: require.resolve('crypto-browserify'),
      'crypto-browserify': require.resolve('crypto-browserify'),
      stream: require.resolve('stream-browserify'),
    },
  },
}

mix.webpackConfig(webpack)

mix
  .js('resources/js/app.js', 'public/js')
  .vuetify('vuetify-loader', './resources/sass/styles/variables.scss')
  .vue()
  .copyDirectory('resources/js/src/assets/images', 'public/images')

// ------------------------------------------------
// If you are deploying on subdomain/subfolder. Uncomment below code before running 'yarn prod' or 'npm run production' command.
// Please Change below 'publicPath' and 'setResourceRoot' options as per your sub-directory path. We have kept our current live demo options which is deployed in sub-folder.
// ------------------------------------------------

/*
 if (mix.inProduction()) {
   mix.version()
   mix.webpackConfig({
     output: {
       publicPath: '/demo/materio-vuetify-vuejs-laravel-admin-template/demo-1/',
       chunkFilename: 'js/chunks/[name].[chunkhash].js'
     }
   })
   mix.setResourceRoot('/demo/materio-vuetify-vuejs-laravel-admin-template/demo-1/')
 }
 */

// ------------------------------------------------
// If you are deploying on subdomain/subfolder then comment out below code before running 'yarn prod' or 'npm run production' command.
// ------------------------------------------------

mix.webpackConfig({
  output: {
    chunkFilename: 'js/chunks/[name].[chunkhash].js',
  },
})
