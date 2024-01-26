const path = require("path");
//const CopyPlugin = require("copy-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const babelConfig = require("./babel.config.json");
const WebpackMessages = require('webpack-messages');
const TerserPlugin = require("terser-webpack-plugin");
// const CompressionPlugin = require("compression-webpack-plugin");

const config = (dir = __dirname) => ({
    stats: "summary", // sets console output
    entry: {
        main: "./src/main.js",
        // more entry points if you want to manually split JS into chunks
    },
    output: {
        path: path.resolve(dir, "./dist"),
        filename: "[name].min.js",
        chunkFilename: "[name].min.js"
    },
    resolve: {
        extensions: [".js"],
        alias: {
            "@": path.resolve(dir, "./js"),
        },
    },
    externals: {
        $: "jQuery",
        jquery: "jQuery",
        "window.jQuery": "jQuery",
        jQuery:"jQuery"
    },
    performance: {
        hints: false,
    },
    optimization: {
        minimize: true,
        minimizer: [new TerserPlugin({
            extractComments: false,
        })],
        splitChunks: {
            cacheGroups: {
                commons: {
                    test: /[\\/]node_modules[\\/]/,
                    name: 'vendors',
                    chunks: 'all'
                }
            }
        },
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "[name].min.css",
            chunkFilename: "[id].css",
        }),
        // new CopyPlugin([
        //     {
        //         from: path.resolve(dir, "./src/img/**/*"),
        //         context: path.resolve(dir, "./src/img"),
        //         to: "./img",
        //     },
        // ]),
        // new CompressionPlugin({
        //     algorithm: "brotliCompress",
        //     test: /\.js(\?.*)?$/i,
        //   }),
        new WebpackMessages({
            name: 'production',
            logger: str => console.log(`>> ${str}`)
          }),
    ],
    module: {
        rules: [
            {
                oneOf: [
                    {
                        test: /\.js$/,
                        exclude: /node_modules/,
                        include: path.resolve(dir, "./src/js"),
                        use: {
                            loader: "babel-loader",
                            options: babelConfig,
                        },
                    },
                    {
                        test: /\.css$/i,
                        use: ["style-loader", "css-loader"],
                    },
                    {
                        test: /\.s[ac]ss$/i,
                        use: [
                            MiniCssExtractPlugin.loader,
                            {
                                loader: 'css-loader',
                                options: {
                                    url: false,
                                },
                            },
                            // Allow loading SCSS from NPM packages
                            {
                                loader: "sass-loader",
                                options: {
                                    sassOptions: {
                                        includePaths: [
                                            "./src/sass",
                                            "./node_modules",
                                        ],
                                    },
                                },
                            },
                        ],
                    },
                    // {
                    //     include: /\.(jpg|png|gif|svg)$/,
                    //     loader: require.resolve("file-loader"),
                    //     options: {
                    //         name: "[path][name].[ext]",
                    //         context: path.resolve("./src/img"),
                    //     },
                    //     exclude: /src\/font/,
                    // },
                    // {
                    //     test: /\.(otf|ttf|woff2?|svg|eot)$/,
                    //     loader: require.resolve("file-loader"),
                    //     options: {
                    //         name: "./fonts/[name].[ext]",
                    //     },
                    //     exclude: /src\/img/,
                    // },
                    // {
                    //     test: /\.svg$/,
                    //     include: path.resolve(dir, "./assets/images/icons"),
                    //     use: ['svg-sprite-loader', 'svgo-loader']
                    // },
                ],
            },
        ],
    },
});

module.exports = config;
