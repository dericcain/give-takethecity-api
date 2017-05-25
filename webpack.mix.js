const mix = require('laravel-mix').mix;

mix.js('resources/assets/js/public/public.js', 'public/js')
    .sass('resources/assets/sass/public/public.sass', 'public/css')
    .extract(['vue', 'lodash', 'axios'])
    .disableNotifications()
    .browserSync('give.takethecity.dev');
