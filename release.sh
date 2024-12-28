#!/bin/bash

# Define directories
PLUGIN_DIR="./public/plugins/accessibility-enhancer"
BUILD_DIR="./build/accessibility-enhancer"
PHPCS_BIN="$PLUGIN_DIR/vendor/bin/phpcs" # Path to phpcs inside your plugin's vendor directory

# Ensure necessary tools are installed
if [ ! -f "$PHPCS_BIN" ]; then
  echo "PHP CodeSniffer is not installed in $PLUGIN_DIR/vendor. Please run 'composer install' first."
  exit 1
fi
command -v npm >/dev/null 2>&1 || { echo >&2 "npm is not installed. Aborting."; exit 1; }

# Clean up any old build
echo "Cleaning up old builds..."
rm -rf $BUILD_DIR
rm -f accessibility-enhancer.zip

# Run PHP CodeSniffer
echo "Checking PHP coding standards..."
$PHPCS_BIN --standard=WordPress --extensions=php $PLUGIN_DIR/includes $PLUGIN_DIR/accessibility-enhancer.php --ignore="node_modules,vendor,templates" $PLUGIN_DIR || { echo "PHP CodeSniffer found issues. Fix them before releasing."; exit 1; }

# Check for JavaScript and CSS linting
echo "Checking JavaScript and CSS standards..."
cd $PLUGIN_DIR
npm install # Ensure dependencies are installed
npm run lint || { echo "Linting failed. Fix errors before release."; exit 1; }

# Build assets
echo "Building assets..."
npm run build || { echo "Asset build failed. Fix errors before release."; exit 1; }
cd -

# Create build directory
echo "Creating build directory..."
mkdir -p $BUILD_DIR
cp -r $PLUGIN_DIR/* $BUILD_DIR

# Remove development files
echo "Removing development files..."
rm -rf $BUILD_DIR/node_modules
rm -rf $BUILD_DIR/vendor
rm -rf $BUILD_DIR/assets
rm -rf $BUILD_DIR/.vscode
rm -f $BUILD_DIR/package.json
rm -f $BUILD_DIR/package-lock.json
rm -f $BUILD_DIR/webpack.config.js
rm -f $BUILD_DIR/composer.json
rm -f $BUILD_DIR/composer.lock
rm -f $BUILD_DIR/phpcs.xml
rm -f $BUILD_DIR/.eslint* $BUILD_DIR/.babelrc $BUILD_DIR/.editorconfig

# Check for README.txt
if [ ! -f "$BUILD_DIR/README.txt" ]; then
  echo "README.txt is missing. Aborting."
  exit 1
fi

# Zip the plugin
echo "Creating plugin zip file..."
cd $BUILD_DIR/..
zip -r ../accessibility-enhancer.zip accessibility-enhancer >/dev/null
rm -rf accessibility-enhancer
mv ../accessibility-enhancer.zip accessibility-enhancer.zip
cd -

# Final message
echo "Plugin is ready for release: accessibility-enhancer.zip"
