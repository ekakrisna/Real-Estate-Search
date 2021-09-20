// --------------------------------------------------------------------------
const mix = require('laravel-mix');
const io = require('lodash');
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// Mix Asset Management
// --------------------------------------------------------------------------
// Mix provides a clean, fluent API for defining some Webpack build steps
// for your Laravel application. By default, we are compiling the Sass
// file for the application as well as bundling up all the JS files.
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
// mix.js('resources/js/app.js', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css');
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
// Backend (Admin panel) assets
// --------------------------------------------------------------------------
mix.js('resources/js/backend/app.js', 'public/backend/dist/js')
    .js('resources/js/backend/vue.js', 'public/backend/dist/js')
    .js('resources/js/backend/parsley.js', 'public/backend/dist/js')
    .sass('resources/sass/backend/app.scss', 'public/backend/dist/css');
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
// Frontend (Front site) assets
// --------------------------------------------------------------------------
var destination = 'public/frontend/assets/css/';
var partials = [ 'base', 'banner', 'contents', 'elements', 'footer', 'header', 'main', 'sidebar' ];
io.each( partials, function( partial ){
    var source = `public/frontend/source/scss/${partial}.scss`
    mix.sass( source, destination );
});
// --------------------------------------------------------------------------