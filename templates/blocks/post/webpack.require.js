const Encore = require('@symfony/webpack-encore');

Encore
.addEntry('post-css', './templates/blocks/post/assets/css/main.css')
.addEntry('post-js', './templates/blocks/post/assets/js/app.js')
;
