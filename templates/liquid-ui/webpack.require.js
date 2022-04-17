const path = require('path');
const Encore = require('@symfony/webpack-encore');
/* const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin'); */

Encore
  .addEntry('liquid-app', './templates/liquid-ui/assets/js/app.js')
  .addEntry('liquid-css', './templates/liquid-ui/assets/css/app.css')


  .copyFiles({
    from: './templates/liquid-ui/assets/img',
    to: 'images/skeleton/[path][name].[ext]',
    pattern: /\.(png|jpg|jpeg|ico)$/,
  })

  /* .addPlugin(new SVGSpritemapPlugin(
    path.join('templates','liquid-ui', 'assets', 'svg', '**', '*.svg')
    )
  ) */
;
