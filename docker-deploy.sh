#!/bin/bash
# ============================================
# Docker Deployment Script
# Run this on your server after cloning
# ============================================

set -e

echo "🚀 Starting Empapy Caffe Deployment..."

# Check if .env exists
if [ ! -f .env ]; then
    echo "📋 Creating .env from template..."
    cp .env.docker .env
    echo "⚠️  Please edit .env with your actual values!"
    echo "    nano .env"
    exit 1
fi

# Create required directories
echo "📁 Creating directories..."
mkdir -p docker/nginx/ssl
mkdir -p docker/mysql/init
mkdir -p storage/app/public
mkdir -p storage/logs

# Build and start containers
echo "🐳 Building Docker containers..."
docker-compose build --no-cache

echo "🚀 Starting containers..."
docker-compose up -d

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 15

# Run migrations
echo "📊 Running migrations..."
docker-compose exec -T app php artisan migrate --force

# Seed database (first time only)
read -p "🌱 Run database seeder? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    docker-compose exec -T app php artisan db:seed --force
fi

# Optimize
echo "⚡ Optimizing for production..."
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache

# Show status
echo ""
echo "✅ Deployment complete!"
echo ""
docker-compose ps
echo ""
echo "🌐 Your app is running at: http://$(hostname -I | awk '{print $1}')"
