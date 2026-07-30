#!/bin/sh

# Ensure storage directories are writable
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Wait for DB if needed, and run migrations
php artisan migrate --force

# Run seeders if this is the first deployment (Optional, commented out by default)
# php artisan db:seed --force

# Clear and cache configurations for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground
nginx -g "daemon off;"
