var webpack = require('webpack');

module.exports = {
	entry: "./resources/app/app.jsx",
	output: {
		path: __dirname,
		filename: "public/static/bundle.js"
	},
	module: {
		loaders: [
			{
				test: /\.jsx?$/,
				exclude: /(node_modules|bower_components)/,
				loader: "babel",
				query: {
					presets: ["react", "es2015"]
				}
			},
			{
				test: /\.woff(2)?(\?v=[0-9]\.[0-9]\.[0-9])?$/,
				loader: "url-loader",
				query: {
					name: "public/static/[name].[ext]",
					mimetype: "application/font-woff",
					limit: 10000
				}
			},
			{
				test: /\.(ttf|eot|svg)(\?v=[0-9]\.[0-9]\.[0-9])?$/,
        loader: 'file-loader?name=public/static/[name].[ext]'
      }
		]
	},
	plugins: [
		new webpack.ProvidePlugin({
			 $: "jquery",
			 jQuery: "jquery"
	 })
	]
}
