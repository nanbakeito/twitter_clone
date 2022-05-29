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

mix.js('resources/js/app.js', 'public/js').vue()
    // .js('resources/js/favorite.js', 'public/js')
    // .js('resources/js/follow.js', 'public/js')
    // .sass('resources/sass/app.scss', 'public/css')
    .autoload({
        "jquery": ['$', 'window.jQuery'],
    })
    ;
    // import './bootstrap';
    // import './favorite';
    // import './follow';
    // import '../sass/app.scss';
    // import '../css/app.css';