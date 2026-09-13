#!/usr/bin/env bash

set -euo pipefail


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
#
# migrate --force is idempotent:
# only migrations that have not yet been executed are applied.
#

php artisan migrate --force


# ============================================================
# Public storage symlink
# ============================================================

php artisan storage:link || true


# ============================================================
# Production config cache
# ============================================================

php artisan config:cache


# ============================================================
# Start PHP-FPM + Nginx
# ============================================================

exec /usr/bin/supervisord \
    -c /etc/supervisor/supervisord.conf