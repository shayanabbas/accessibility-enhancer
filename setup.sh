#!/bin/bash

echo "Starting DDEV..."
ddev start

echo "Downloading WordPress..."
ddev wp core download --path=wordpress

echo "Installing WordPress..."
ddev wp core install --path=wordpress --url=http://accessibility-enhancer.ddev.site --title="Accessibility Enhancer" --admin_user=admin --admin_password=admin --admin_email=admin@example.com

echo "Setup Complete! Visit: http://accessibility-enhancer.ddev.site"
