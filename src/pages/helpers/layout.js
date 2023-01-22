module.exports = (layout, htmlWebpackPlugin, options) => {
    return require(`../layouts/${layout}.html.hbs`)({
        htmlWebpackPlugin,
        content: options.fn(this),
    })
}
