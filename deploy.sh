#!/bin/bash
# ============================================
# Azure Deployment Script for Empapy Caffe
# Run this after uploading files to Azure
# ============================================

echo "🚀 Starting deployment..."

# ============================================
# 1. BUILD ASSETS (VITE) - CRITICAL!
# ============================================
echo "📦 Installing NPM dependencies..."
npm ci --production=false

echo "🔨 Building Vite assets..."
npm run build

# Remove node_modules after build to save space
rm -rf node_modules

# ============================================
# 2. INSTALL COMPOSER DEPENDENCIES
# ============================================
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# ============================================
# 3. CONFIGURE NGINX (Document Root)
# ============================================
echo "🔧 Configuring Nginx..."
if [ -f /home/site/wwwroot/nginx.conf ]; then
    cp /home/site/wwwroot/nginx.conf /etc/nginx/sites-available/default
    service nginx reload 2>/dev/null || true
fi

# ============================================
# 4. LARAVEL SETUP
# ============================================
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "🔗 Creating storage link..."
php artisan storage:link

echo "📊 Running migrations..."
php artisan migrate --force

# Seed database (only first time!)
# Uncomment the next line for first deployment
# php artisan db:seed --force

echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment complete!"
