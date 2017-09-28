var config = require('../config')
var webpack = require('webpack')
var merge = require('webpack-merge')
var utils = require('./utils')
var baseWebpackConfig = require('./webpack.base.conf')
var HtmlWebpackPlugin = require('html-webpack-plugin')

// 自定义模板文件目录
var HTMLTEMPLATEDIR = 'template/'

// add hot-reload related code to entry chunks
Object.keys(baseWebpackConfig.entry).forEach(function (name) {
  baseWebpackConfig.entry[name] = ['./build/dev-client'].concat(baseWebpackConfig.entry[name])
})

module.exports = merge(baseWebpackConfig, {
  module: {
    loaders: utils.styleLoaders({ sourceMap: config.dev.cssSourceMap })
  },
  // eval-source-map is faster for development
  devtool: '#eval-source-map',
  plugins: [
    new webpack.DefinePlugin({
      'process.env': config.dev.env
    }),
    // https://github.com/glenjamin/webpack-hot-middleware#installation--usage
    new webpack.optimize.OccurrenceOrderPlugin(),
    new webpack.HotModuleReplacementPlugin(),
    new webpack.NoErrorsPlugin(),
    // https://github.com/ampedandwired/html-webpack-plugin
    new HtmlWebpackPlugin({
      filename: 'index.html',
      template: HTMLTEMPLATEDIR + 'index.html',
      inject: true,
      chunks: ['index']
    }),
    new HtmlWebpackPlugin({
      filename: 'help.html',
      template: HTMLTEMPLATEDIR + 'help.html',
      inject: true,
      chunks: ['help']
    }),
    new HtmlWebpackPlugin({
      filename: 'pubsell.html',
      template: HTMLTEMPLATEDIR + 'pubsell.html',
      inject: true,
      chunks: ['pubsell']
    }),
    new HtmlWebpackPlugin({
      filename: 'market.html',
      template: HTMLTEMPLATEDIR + 'market.html',
      inject: true,
      chunks: ['market']
    }),
     new HtmlWebpackPlugin({
      filename: 'buy.html',
      template: HTMLTEMPLATEDIR + 'buy.html',
      inject: true,
      chunks: ['buy']
    })
  ]
})
