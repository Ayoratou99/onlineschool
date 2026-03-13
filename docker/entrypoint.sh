#!/bin/sh
set -e

cd /var/www

# Ensure Paperless DB exists (same pgsql server)
if [ -f /entrypoint-paperless_init.sh ]; then
    /entrypoint-paperless_init.sh
fi

# Auto migrations: central DB first, then all tenant DBs
if [ "$AUTO_MIGRATE" = "true" ]; then
    php artisan migrate --force
    php artisan tenants:migrate
fi

# Auto seed: main (central) and/or tenants
if [ "$AUTO_SEED" = "true" ]; then
    php artisan db:seed --force
fi
if [ "$AUTO_SEED_TENANT" = "true" ]; then
    php artisan tenants:seed --force
fi

# Start Supervisor (PHP-FPM + Horizon)
exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
