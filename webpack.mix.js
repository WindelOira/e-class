const path = require('path');
const mix = require('laravel-mix');

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

mix.browserSync('dev.e-class');

mix.webpackConfig({
    resolve     : {
        extensions  : ['.js', '.json', '.vue'],
        alias       : {
            CSS         : path.resolve(__dirname, './resources/css/'),
            JS          : path.resolve(__dirname, './resources/js/'),
            Partials    : path.resolve(__dirname, './resources/js/components/partials/'),
            Views       : path.resolve(__dirname, './resources/js/components/views/')
        }
    }
})

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');
