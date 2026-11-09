
#!/usr/bin/env bash

set -eu

cd /var/www/html

# ============================================================
# Render port
# ============================================================

export PORT="${PORT:-10000}"

# ============================================================
# Laravel writable directories
# ============================================================

mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

chown -R www-data:www-data \
    storage \
    bootstrap/cache

# ============================================================
# Generate Nginx configuration with Render PORT
# ============================================================

envsubst '${PORT}' \
    < /etc/nginx/templates/default.conf.template \
    > /etc/nginx/conf.d/default.conf

# ============================================================
# Laravel startup preparation
# ============================================================

php artisan package:discover --ansi

php artisan config:clear
php artisan route:clear
php artisan view:clear

# ============================================================
# Database migrations
# ============================================================
#
# Never use migrate:fresh here.
# Only migrations not yet executed are applied.
#

php artisan migrate --force

# Always ensure required user roles exist.
php artisan db:seed --class=RoleSeeder --force

# ============================================================
# Initialize all catalog seeders when enabled
# ============================================================
#
# Set RUN_CATALOG_SEED=true only for initial setup.
# Disable it after successful seeding to avoid overwriting
# existing catalog values or inventory quantities.
#

if [ "${RUN_CATALOG_SEED:-false}" = "true" ]; then
    php artisan db:seed --force
fi

# ============================================================
# Public storage symlink
# ============================================================

php artisan storage:link || true

# ============================================================
# Production config cache
# ============================================================

php artisan config:cache

# ============================================================
# Demo data (first deployment only)
# ============================================================

if [ "${RUN_DEMO_SEED:-false}" = "true" ]; then
    php artisan demo:seed

    # Run embedding in background to avoid blocking startup.
    if [ "${AI_MODE:-}" = "live" ] && [ -n "${GEMINI_API_KEY:-}" ]; then
        (
            php artisan knowledge:embed \
                || echo "knowledge:embed failed" >&2
        ) &
    fi
fi

# ============================================================
# Start PHP-FPM + Nginx
# ============================================================

exec /usr/bin/supervisord \
    -c /etc/supervisor/supervisord.conf
