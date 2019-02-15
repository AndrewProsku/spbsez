// DEPENDENCIES
const path = require('path');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const config = require('./tasks/config');
const autoprefixer = require('autoprefixer');
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
            use : [
                MiniCssExtractPlugin.loader,
                "css-loader"
            ]
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
            test   : /\.scss$/,
            exclude: /(bower_components)/,
            use    : [
                MiniCssExtractPlugin.loader,
                {
                    loader: 'css-loader',
                    options: {
                        importLoaders: 2,
                        sourceMap: true
                    }
                }, {
                    loader: 'postcss-loader',
                    options: {
                        plugins  : [autoprefixer()],
                        sourceMap: true
                    }
                }, {
                    loader: 'sass-loader',
                    options: {
                        sourceMap: true
                    }
                }
            ]
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
        new MiniCssExtractPlugin({
          filename: './../styles/[name].css'
        }),
        new CleanWebpackPlugin([
            path.resolve(__dirname, config.scripts.output),
            path.resolve(__dirname, config.styles.output)
        ])
      ],
    resolve: {
        alias: {
            components: path.resolve(__dirname, './src/components'),
            common    : path.resolve(__dirname, './src/common')
        }
    },
    devtool: 'inline-cheap-module-source-map'
};
