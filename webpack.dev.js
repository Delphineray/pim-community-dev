const config = require('./webpack.config.js')
const path = require('path')
const webpack = require('webpack')
const WatchIgnorePlugin = require('webpack').WatchIgnorePlugin;

config.entry = [
    'webpack-dev-server/client?http://localhost:8081',
    'webpack/hot/only-dev-server',
    path.resolve(__dirname, './src/Pim/Bundle/EnrichBundle/Resources/public/js/index.js')
]

config.output.publicPath = 'http://localhost:8081/'
config.output.hotUpdateChunkFilename = 'hot/hot-update.js'
config.output.hotUpdateMainFilename = 'hot/hot-update.json'

config.devServer = {
    hot: true,
    inline: true,
    publicPath: 'http://localhost:8081/',
    headers: {
        'Access-Control-Allow-Origin': '*'
    },
    watchOptions: {
        ignored: /node_modules/
    }
}

config.devtool = 'inline-source-map'

config.plugins.push(new webpack.HotModuleReplacementPlugin())
config.plugins.push(new WatchIgnorePlugin([
    path.resolve(__dirname, './node_modules'),
    path.resolve(__dirname, './app'),
    path.resolve(__dirname, './vendor'),
    path.resolve(__dirname, './web'),
]))
module.exports = config;
