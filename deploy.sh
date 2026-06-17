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

echo "🚧 Entering maintenance mode..."
php artisan down --retry=15 || true

# ============================================
# 5. DATABASE BACKUP (before any schema change)
# ============================================
read_env() {
    # Read a single key's value from .env without word-splitting / quoting issues.
    local key="$1" line
    line=$(grep -E "^${key}=" .env 2>/dev/null | head -n1)
    line="${line#${key}=}"
    line="${line%\"}"; line="${line#\"}"
    line="${line%\'}"; line="${line#\'}"
    printf '%s' "$line"
}

if [ "${SKIP_DB_BACKUP:-0}" != "1" ]; then
    DB_CONNECTION_VAL="${DB_CONNECTION:-$(read_env DB_CONNECTION)}"

    if [ "$DB_CONNECTION_VAL" = "mysql" ]; then
        echo "💾 Backing up database before migration..."
        DB_HOST_VAL="${DB_HOST:-$(read_env DB_HOST)}"
        DB_PORT_VAL="${DB_PORT:-$(read_env DB_PORT)}"
        DB_NAME_VAL="${DB_DATABASE:-$(read_env DB_DATABASE)}"
        DB_USER_VAL="${DB_USERNAME:-$(read_env DB_USERNAME)}"
        DB_PASS_VAL="${DB_PASSWORD:-$(read_env DB_PASSWORD)}"

        BACKUP_DIR="storage/backups"
        mkdir -p "$BACKUP_DIR"
        BACKUP_FILE="$BACKUP_DIR/pre-deploy-$(date +%Y%m%d-%H%M%S).sql"

        if ! command -v mysqldump >/dev/null 2>&1; then
            echo "❌ mysqldump not found — cannot back up the database."
            echo "   Install a mysql client, or set SKIP_DB_BACKUP=1 to deploy without a backup (NOT recommended)."
            php artisan up || true
            exit 1
        fi

        # Pass the password via MYSQL_PWD so it never appears in the process list.
        if MYSQL_PWD="$DB_PASS_VAL" mysqldump \
                --host="${DB_HOST_VAL:-127.0.0.1}" --port="${DB_PORT_VAL:-3306}" \
                --user="$DB_USER_VAL" --single-transaction --quick --no-tablespaces \
                "$DB_NAME_VAL" > "$BACKUP_FILE"; then
            echo "✅ Database backup saved to $BACKUP_FILE"
        else
            echo "❌ Database backup failed — aborting deploy (no migration was run)."
            rm -f "$BACKUP_FILE"
            php artisan up || true
            exit 1
        fi
    else
        echo "ℹ️  DB_CONNECTION='$DB_CONNECTION_VAL' (not mysql) — skipping mysqldump backup."
    fi
else
    echo "⚠️  SKIP_DB_BACKUP=1 — skipping database backup (not recommended)."
fi

# ============================================
# 6. MIGRATIONS
# ============================================
echo "📊 Running migrations..."
if ! php artisan migrate --force; then
    echo "❌ Migration failed. The app is kept in maintenance mode for safety."
    echo "   Restore from the latest dump in storage/backups if needed, then run 'php artisan up'."
    exit 1
fi

# Seed database (only first time!)
# Uncomment the next line for first deployment
# php artisan db:seed --force

# ============================================
# 7. OPTIMIZE FOR PRODUCTION
# ============================================
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Bringing application back online..."
php artisan up

echo "✅ Deployment complete!"
