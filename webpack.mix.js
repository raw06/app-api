const mix = require('laravel-mix');
const path = require('path');

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

mix
  .js('resources/js/index.js', 'public/js')
  .sourceMaps(false)
  .postCss('resources/css/app.css', 'public/css');

mix.webpackConfig({
  module: {
    rules: [
      {
        enforce: 'pre',
        exclude: /node_modules/,
        loader: 'eslint-loader',
        test: /\.(js)?$/
      },
      {
        test: /\.less$/,
        loader: 'less-loader' // compiles Less to CSS
      },
      {
        test: /\.mjs$/,
        include: /node_modules/,
        type: 'javascript/auto'
      }
    ]
  },
  resolve: {
    extensions: ['.js', '.jsx'],
    alias: {
      // '@': path.resolve('resources/js'),
      // '@components': path.resolve('resources/js/components'),
      // '@constants': path.resolve('resources/js/constants'),
      // '@providers': path.resolve('resources/js/providers'),
      // '@hooks': path.resolve('resources/js/hooks'),
      // '@utils': path.resolve('resources/js/utils')
    }
  }
});
