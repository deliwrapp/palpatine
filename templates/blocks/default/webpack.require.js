const Encore = require('@symfony/webpack-encore');

Encore
  .addEntry('default-block-css', './templates/blocks/post/assets/css/main.css')
  .addEntry('default-block-js', './templates/blocks/post/assets/js/app.js')
;
