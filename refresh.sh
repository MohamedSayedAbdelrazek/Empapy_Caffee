#!/bin/bash

echo "🚀 Starting Refresh for Empapy Caffee (Docker)..."

# 0. Fix Host Permissions (CRITICAL for Git)
# بناخد الملكية ليك عشان الجيت يعرف يشتغل
echo "🔓 Unlocking files for Git..."
sudo chown -R $USER:$USER .

# 1. Pull latest code (Force Update)
echo "📥 Pulling latest code..."
git fetch origin
git reset --hard origin/main

# 2. Rebuild Containers (The Docker Way)
echo "🏗️ Rebuilding containers..."
sudo docker compose down
sudo docker compose up -d --build

# 3. Setup Environment File (Inject .env)
echo "🔑 Copying .env file..."
sudo docker compose cp .env app:/var/www/html/.env
sudo docker compose exec app chown www-data:www-data /var/www/html/.env

# 4. Fix Container Permissions
# بنرجع الملكية للدوكر عشان الموقع يشتغل
echo "🔒 Fixing container permissions..."
sudo docker compose exec app chown -R www-data:www-data /var/www/html/storage
sudo docker compose exec app chmod -R 775 /var/www/html/storage

# 5. Clear & Cache (Laravel Cleanup)
echo "🧹 Optimizing Laravel..."
sudo docker compose exec app php artisan optimize:clear
sudo docker compose exec app php artisan config:cache
sudo docker compose exec app php artisan route:cache
sudo docker compose exec app php artisan view:cache

echo "✅ Refresh Finished Successfully! 🎉"
