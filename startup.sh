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

# Run migrations (safe - won't re-run existing ones)
php /home/site/wwwroot/artisan migrate --force
echo "✅ Migrations complete"

# Clear and cache config for performance
php /home/site/wwwroot/artisan config:cache
php /home/site/wwwroot/artisan route:cache
php /home/site/wwwroot/artisan view:cache
echo "✅ Caches warmed up"

echo "🎉 Startup complete!"
