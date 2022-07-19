const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
    ]);

mix.styles([
    'resources/front/css/bootstrap.min.css',
    'resources/front/css/style.css'
], 'public/css/style.css');

mix.scripts([
    'resources/front/js/script.js',
    'resources/front/js/jquery.min.js',
    'resources/front/js/bootstrap.min.js',
], 'public/js/script.js');

mix.copyDirectory('resources/front/images', 'public/images');