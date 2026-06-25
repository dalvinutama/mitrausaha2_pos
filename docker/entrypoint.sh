#!/bin/bash
set -e

# Wait a moment for DB to be ready if it's starting up simultaneously
echo "Starting Laravel initialization..."

# Run migrations if environment allows
php artisan migrate --force

# Clear caches and optimize
php artisan optimize:clear
php artisan optimize
php artisan view:cache
php artisan config:cache
php artisan route:cache

# Set permissions again just to be safe
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Start Apache in foreground
echo "Starting Apache..."
exec apache2-foreground
