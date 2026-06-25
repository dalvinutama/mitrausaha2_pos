#!/bin/bash
set -e

# Wait a moment for DB to be ready if it's starting up simultaneously
echo "Starting Laravel initialization..."

# Wait for DB and run migrations
MAX_RETRIES=30
RETRIES=0
echo "Running migrations..."
until php artisan migrate --force; do
  if [ $RETRIES -ge $MAX_RETRIES ]; then
    echo "Migrations failed after $MAX_RETRIES attempts."
    exit 1
  fi
  echo "Database might not be ready. Retrying in 3 seconds..."
  sleep 3
  RETRIES=$((RETRIES+1))
done

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
