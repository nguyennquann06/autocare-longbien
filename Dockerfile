# syntax=docker/dockerfile:1

# ============================================================
# Stage 1: Build Vite assets
# ============================================================

FROM node:22-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci

COPY resources ./resources
COPY vite.config.js ./

RUN npm run build


# ============================================================
# Stage 2: Install PHP dependencies
# ============================================================

FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts


# ============================================================
# Stage 3: Laravel runtime
# ============================================================

FROM php:8.2-fpm-bookworm

ENV APP_ENV=production
ENV APP_DEBUG=false

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        nginx \
        supervisor \
        gettext-base \
        ca-certificates \
        curl \
        unzip \
        libonig-dev \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        opcache \
    && rm -rf /var/lib/apt/lists/* \
    && rm -f /etc/nginx/sites-enabled/default

WORKDIR /var/www/html


# PHP dependencies
COPY --from=vendor /app/vendor ./vendor


# Application source
COPY . .


# Compiled Vite assets
COPY --from=frontend /app/public/build ./public/build


# Render/Nginx configuration
COPY docker/nginx.conf.template \
    /etc/nginx/templates/default.conf.template

COPY docker/supervisord.conf \
    /etc/supervisor/conf.d/autocare.conf

COPY docker/start.sh \
    /usr/local/bin/autocare-start


# Laravel writable directories
RUN chmod +x /usr/local/bin/autocare-start \
    && mkdir -p \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data \
        storage \
        bootstrap/cache


EXPOSE 10000


CMD ["/usr/local/bin/autocare-start"]