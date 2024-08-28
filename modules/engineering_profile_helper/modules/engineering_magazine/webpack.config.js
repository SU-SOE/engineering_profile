const path = require("path");
const Webpack = require("webpack");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");
const FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries");
const FileManagerPlugin = require('filemanager-webpack-plugin');
const autoprefixer = require('autoprefixer')({ grid: true });

const config = {
  isProd: process.env.NODE_ENV === "production",
  hmrEnabled: process.env.NODE_ENV !== "production" && !process.env.NO_HMR,
  distFolder: path.resolve(__dirname, "./dist/css"),
  wdsPort: 3001,
};

// /////////////////////////////////////////////////////////////////////////////
// Paths ///////////////////////////////////////////////////////////////////////
// /////////////////////////////////////////////////////////////////////////////

const npmPackage = path.resolve(__dirname, 'node_modules');
const srcDir = path.resolve(__dirname, "lib");
const distDir = path.resolve(__dirname, "dist");
const srcSass = path.resolve(srcDir, 'scss');
const distSass = path.resolve(distDir, 'css');
const srcJS = path.resolve(srcDir, 'js');
const distJS = path.resolve(distDir, 'js');

// /////////////////////////////////////////////////////////////////////////////
// Functions ///////////////////////////////////////////////////////////////////
// /////////////////////////////////////////////////////////////////////////////

// /////////////////////////////////////////////////////////////////////////////
// Config //////////////////////////////////////////////////////////////////////
// /////////////////////////////////////////////////////////////////////////////

// Start configuring webpack.
var webpackConfig = {

    // What build?
    entry: {
        "engineering_magazine.script": "/lib/js/magazine_navigation.js",
        "engineering_magazine_curtain.script": "/lib/js/magazine_curtain.js",
        "engineering_magazine.styles": "/lib/scss/index.scss",
    },
    // Where put build?
    output: {
        filename: "[name].js",
        path: distJS
    },
    // Additional module rules.
    module: {
      rules: [
        {
          test: /\.behavior.js$/,
          exclude: /node_modules/,
          options: {
            enableHmr: false
          },
          loader: 'drupal-behaviors-loader'
        },
        {
          test: /\.m?js$/,
          exclude: /(node_modules)/,
          use: {
            loader: 'babel-loader',
            options: {
              presets: ['@babel/preset-env']
            }
          },
        },
        {
          test: /\.(sa|sc|c)ss$/,
          use: [
            config.isProd ? { loader: MiniCssExtractPlugin.loader } : 'style-loader',
            {loader:'css-loader', options: {}},
            {
              loader: 'postcss-loader',
              options: {
                postcssOptions: {
                  sourceMap: true,
                  plugins: [autoprefixer],
                },
              }
            },
            {loader:'sass-loader', options: {}}
          ]
        },
        {
          test: /\.(png|jpg|gif|svg)$/i,
          type: "asset"
        },
        {
          test: /\.(woff|woff2|eot|ttf)$/i,
          type: "asset",
          generator: {
            filename: '../assets/fonts/[name][ext][query]'
          }
        }
      ]
    },
    // Build optimizations.
    optimization: {

        minimizer: [
          new OptimizeCSSAssetsPlugin(),
        ]
    },
    // Plugin configuration.
    plugins: [
      new FixStyleOnlyEntriesPlugin(),
      new MiniCssExtractPlugin({
        filename: '../css/[name].css',
      }),
      new FileManagerPlugin({
        events: {
          onStart: {
            delete: ["dist"]
          }
        }
      }),
    ]
};

// Add the configuration.
module.exports = [webpackConfig];
