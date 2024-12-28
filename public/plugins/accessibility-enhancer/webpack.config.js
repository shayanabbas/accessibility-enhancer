const path = require('path');

module.exports = {
  entry: './src/App.js',
  output: {
    path: path.resolve(__dirname, 'assets/js'),
    filename: 'toolbar.js'
  },
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader'
        }
      }
    ]
  },
  resolve: {
    extensions: ['.js', '.jsx']
  },
  devServer: {
    static: {
        directory: path.join(__dirname, 'assets/js'),
    },
    compress: true,
    port: 3000,
    hot: true,
    devMiddleware: {
        writeToDisk: true, 
    },
    allowedHosts: 'all',
    client: {
        webSocketURL: {
            hostname: 'localhost', 
            port: 3000, 
            protocol: 'ws', 
        },
    },
  }
};
