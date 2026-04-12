process.env.DISABLE_WEBPACKBAR = true;

let mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 */

mix.js('resources/assets/js/app.js', 'public/js')
    .vue()
    .sass('resources/assets/sass/app.scss', 'public/css')
    .options({
        processCssUrls: false,
        terser: {
            extractComments: false
        }
    });

mix.webpackConfig({
    stats: 'minimal',
    plugins: [],

    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/assets/js'),
        },
    },
});

if (mix.inProduction()) {
    mix.sourceMaps(false);
    mix.version();
} else {
    mix.sourceMaps();
}

mix.override(config => {
    config.plugins = config.plugins.filter(
        plugin => plugin.constructor.name !== 'ProgressPlugin'
    );
});
