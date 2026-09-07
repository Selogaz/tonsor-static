const mix = require("laravel-mix");
const SVGSpritemapPlugin = require("svg-spritemap-webpack-plugin");

mix.disableNotifications();
mix.setPublicPath("src");

mix.webpackConfig({
  plugins: [
    new SVGSpritemapPlugin("src/icons-to-sprite/*.svg", {
      output: {
        filename: "img/sprite.svg",
        svg: {
          sizes: false,
        },
      },
      sprite: {
        prefix: false,
        generate: {
          use: true,
          view: true,
          symbol: "-sym",
        },
      },
    }),
  ],
});
