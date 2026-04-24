module.exports = (env, argv) => require("./config/webpack.config")(__dirname, argv?.mode);
