// DEPENDENCIES
const path = require('path');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const config = require('./tasks/config');
const webpack = require('webpack');
const rev = require('./tasks/rev');

rev([
    './www/local/templates/kelnik/inc_footer.php'
]);

// CONFIG
module.exports = {
    target : 'web',
    context: path.resolve(__dirname, './src'),
    entry  : {
        app : ['babel-polyfill', './common/scripts/app']
    },
    output: {
        filename: '[name].js',
        path    : path.resolve(__dirname, config.scripts.output)
    },
    module: {
        rules: [{
            test: /\.css/,
            use : ['style-loader', 'css-loader']
        }, {
            test   : /\.js$/,
            exclude: /(bower_components)/,
            use    : {
                loader : 'babel-loader',
                options: {
                    presets       : ['@babel/preset-env'],
                    plugins       : ['@babel/plugin-proposal-object-rest-spread'],
                    cacheDirectory: true
                }
            }
        }, {
            test   : /\.twig$/,
            exclude: /(node_modules|bower_components)/,
            use    : {
                loader: 'twig-loader'
            }
        }
        ]
    },
    plugins: [
        new CleanWebpackPlugin([
            path.resolve(__dirname, config.scripts.output)
        ]),
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery'
        })
      ],
    resolve: {
        alias: {
            components: path.resolve(__dirname, './src/components'),
            common    : path.resolve(__dirname, './src/common'),
            jquery    : 'jquery'
        }
    },
    devtool: 'inline-cheap-module-source-map'
};
