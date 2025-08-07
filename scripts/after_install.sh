#!/bin/bash
# This script runs after the new application files have been copied to the server.

# Navigate to the application directory
cd /var/www/html

# IMPORTANT: Set ownership of all files to the web server user (apache)
chown -R apache:apache .

# Install Composer dependencies for production
# --no-dev: Skips development packages
# --no-interaction: Prevents interactive prompts
# --optimize-autoloader: Creates a faster class loader
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# -----------------------------------------------------------------
# --- CRITICAL: SECURELY HANDLE YOUR .env ENVIRONMENT FILE ---
# --- DO NOT COMMIT YOUR .env FILE TO GITHUB. ---
# --- Instead, pull it from a secure location like AWS Parameter Store or S3. ---

# --- Option 1: Using AWS Systems Manager Parameter Store (Recommended) ---
# aws ssm get-parameter --name "/laravel/dev/env" --with-decryption --query "Parameter.Value" --output text > .env

# --- Option 2: Using a secure S3 bucket ---
# aws s3 cp s3://your-secure-config-bucket/development/.env .
# -----------------------------------------------------------------

# Generate the Laravel application key
php artisan key:generate

# Run database migrations (the --force flag is needed for non-interactive environments)
php artisan migrate --force

# Cache configuration, routes, and views for optimal performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set the correct permissions on the storage and cache directories
chmod -R 775 storage bootstrap/cache
