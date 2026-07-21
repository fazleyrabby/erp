FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    redis-tools

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Copy Nginx and Supervisor configs
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php-local.ini /usr/local/etc/php/conf.d/php-local.ini

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application
COPY . /var/www/html/

# Create cache/storage directories BEFORE composer install.
# Laravel's post-autoload-dump (package:discover) compiles views and needs
# a valid cache path, otherwise it fails with "Please provide a valid cache path".
RUN mkdir -p /var/www/html/storage/app/public \
    /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs \
    /var/www/html/storage/tmp \
    /var/www/html/bootstrap/cache \
    && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Install PHP dependencies (no dev for production)
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Install Node.js 18 LTS via NodeSource (apt default is too old for webpack 5 / laravel-mix 6)
# NODE_OPTIONS=--openssl-legacy-provider is required because laravel-mix 6 uses webpack 4
# which relies on OpenSSL legacy routines removed in Node 17+
RUN apt-get update && apt-get install -y curl ca-certificates \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g pnpm \
    && pnpm install \
    && NODE_OPTIONS=--openssl-legacy-provider pnpm run production \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Prepare storage & cache directories with correct permissions
RUN mkdir -p /var/www/html/storage/logs \
    /var/www/html/storage/framework/cache/data \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/tmp \
    /var/www/html/bootstrap/cache \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && chmod +x /var/www/html/artisan \
    && chmod 1777 /tmp

# Expose port 80
EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
