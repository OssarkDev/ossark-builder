const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const babelConfig = require("./babel.config.json");
const WebpackMessages = require('webpack-messages');
const TerserPlugin = require("terser-webpack-plugin");

const config = (dir = __dirname, mode = 'production') => ({
    devtool: mode === 'development' ? 'source-map' : false,
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
                        include: path.resolve(dir, "./src"),
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
                                        loadPaths: [
                                            path.resolve(dir, "./src/scss/include"),
                                            path.resolve(dir, "./node_modules"),
                                        ],
                                    },
                                    // Short import paths — files can @use "shared" as * to get
                                    // variables + mixins without lengthy relative paths.
                                    // additionalData only affects the webpack entry file, not
                                    // files loaded by Sass's own @use system, so each partial
                                    // must have its own @use "shared" as *.
                                },
                            },
                        ],
                    },
                ],
            },
        ],
    },
});

module.exports = config;
