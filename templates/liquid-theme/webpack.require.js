const path = require('path');
const Encore = require('@symfony/webpack-encore');
/* const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin'); */

Encore
  .addEntry('liquid-app', './templates/liquid-theme/assets/js/app.js')
  .addEntry('liquid-css', './templates/liquid-theme/assets/css/app.css')


  .copyFiles({
    from: './templates/liquid-theme/assets/img',
    to: 'images/skeleton/[path][name].[ext]',
    pattern: /\.(png|jpg|jpeg|ico)$/,
  })

  /* .addPlugin(new SVGSpritemapPlugin(
    path.join('templates','liquid-theme', 'assets', 'svg', '**', '*.svg')
    )
  ) */
;
