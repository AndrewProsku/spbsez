// DEPENDENCIES
const path = require('path');
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const config = require('./tasks/config');
const rev = require('./tasks/rev');

rev([
    './www/local/templates/kelnik/inc_footer.php'
]);

// CONFIG
module.exports = {
    target : 'web',
    context: path.resolve(__dirname, './src'),
    entry  : {
        app       : ['babel-polyfill', './common/scripts/app'],
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
        },{
            test: /\.(png|jpg|gif)$/,
            use: [
                {
                    loader: 'file-loader?name=/images/[name].[ext]',
                    options: {
                        name: '/images/[name].[ext]'
                    }
                }
            ]
        }
        ]
    },
    plugins: [
        new CleanWebpackPlugin([
            path.resolve(__dirname, config.scripts.output)
        ])
      ],
    resolve: {
        alias: {
            components: path.resolve(__dirname, './src/components'),
            common    : path.resolve(__dirname, './src/common')
        }
    },
    devtool: 'source-map',
    optimization: {
        minimizer: [
            new UglifyJSPlugin({
                sourceMap: true,
                uglifyOptions: {
                    compress: true,
                    ecma: 6,
                    output: {
                        comments: false
                    },
                    compress: {
                        dead_code: true,
                        drop_console: true
                    },
                }
            })
        ]
    }
};
