const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const path = require('path');
let Encore = require('@symfony/webpack-encore');

Encore
  .addEntry('liquid-app', './templates/liquid-theme/js/app.js')
  .addEntry('liquid-css', './templates/liquid-theme/css/app.css')


  .copyFiles({
    from: './templates/liquid-theme/img',
    to: 'images/skeleton/[path][name].[ext]',
    pattern: /\.(png|jpg|jpeg|ico)$/,
  })

  .addPlugin(new SVGSpritemapPlugin(
    path.join('templates','liquid-theme', 'svg', '**', '*.svg')
    )
  )
;
