#!/bin/bash
# ============================================
# Azure App Service Startup Script
# This runs when the container starts
# ============================================

echo "🚀 Starting Empapy Caffe..."

# Copy custom nginx config
if [ -f /home/site/wwwroot/nginx.conf ]; then
    cp /home/site/wwwroot/nginx.conf /etc/nginx/sites-available/default
    service nginx reload
    echo "✅ Nginx configured to use /public folder"
fi

# Create storage link if not exists
if [ ! -L /home/site/wwwroot/public/storage ]; then
    php /home/site/wwwroot/artisan storage:link --force
    echo "✅ Storage link created"
fi

# NOTE: migrations are intentionally NOT run on container boot.
# Schema changes run once per deployment in deploy.sh, wrapped in
# maintenance mode + a database backup. Running migrate on every boot
# risks an unattended, un-backed-up schema change on autoscale/restart.

# Clear and cache config for performance
php /home/site/wwwroot/artisan config:cache
php /home/site/wwwroot/artisan route:cache
php /home/site/wwwroot/artisan view:cache
echo "✅ Caches warmed up"

echo "🎉 Startup complete!"
