const HtmlWebpackPlugin = require('html-webpack-plugin')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const Path = require('path')

const distPath = Path.resolve(__dirname, 'dist')
const srcPath = Path.resolve(__dirname, 'src')

const isProductionMode = process.env.NODE_ENV === 'production'

module.exports = {
    mode: isProductionMode ? 'production' : 'development',
    devtool: isProductionMode ? false : 'source-map',
    entry: {
        main: Path.join(srcPath, 'scripts', 'main.js'),
    },
    output: {
        path: distPath,
        filename: '[name].[contenthash].js',
        assetModuleFilename: 'assets/[name].[hash][ext]',
    },
    devServer: {
        static: distPath,
        port: 8080,
        hot: true,
        client: {
            overlay: {
                errors: true,
                warnings: false,
            },
        },
    },
    module: {
        rules: [
            {
                mimetype: 'image/svg+xml',
                scheme: 'data',
                type: 'asset/resource',
                generator: {
                    filename: 'assets/icons/[hash][ext]',
                },
            },
            {
                test: /\.(jpe?g|png|svg|gif)$/i,
                type: 'asset/resource',
                generator: {
                    filename: 'assets/images/[name].[hash][ext]',
                },
            },
            {
                test: /\.js$/i,
                exclude: /node_modules/,
                use: [
                    {
                        loader: 'babel-loader',
                        options: {
                            presets: [
                                '@babel/preset-env',
                            ],
                        },
                    },
                ],
            },
            {
                test: /\.scss$/i,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader
                    },
                    {
                        loader: 'css-loader',
                    },
                    {
                        loader: 'postcss-loader',
                        options: {
                            postcssOptions: {
                                plugins: [
                                    'autoprefixer',
                                ],
                            },
                        },
                    },
                    {
                        loader: 'sass-loader',
                    },
                ],
            },
            {
                test: /\.hbs$/i,
                loader: 'handlebars-loader',
                options: {
                    inlineRequires: '/images/',
                },
            },
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '[name].[contenthash].css',
        }),
        new HtmlWebpackPlugin({
            title: 'Strona główna',
            template: Path.join(srcPath, 'pages', 'index.html.hbs'),
            filename: 'index.html',
        }),
    ],
}
