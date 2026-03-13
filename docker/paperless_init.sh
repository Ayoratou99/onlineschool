#!/bin/sh
# -----------------------------------------------------------------------------
# Ensure Paperless DB exists on the same PostgreSQL server (for Paperless-ngx).
# Uses DB_* from env (e.g. DB_HOST=pgsql, DB_USERNAME=..., DB_PASSWORD=...).
# Idempotent: creates only when the database does not exist.
# -----------------------------------------------------------------------------
if [ -n "${DB_HOST}" ] && [ -n "${DB_USERNAME}" ]; then
    export PGPASSWORD="${DB_PASSWORD:-}"
    _db_port="${DB_PORT:-5432}"
    _count=$(psql -h "$DB_HOST" -p "$_db_port" -U "$DB_USERNAME" -d postgres -tAc "SELECT 1 FROM pg_database WHERE datname = 'paperless'" 2>/dev/null || true)
    if [ "$_count" != "1" ]; then
        echo "Creating database 'paperless' on $DB_HOST..."
        psql -h "$DB_HOST" -p "$_db_port" -U "$DB_USERNAME" -d postgres -c "CREATE DATABASE paperless; GRANT ALL PRIVILEGES ON DATABASE paperless TO $DB_USERNAME;" 2>/dev/null || true
    fi
    unset PGPASSWORD
fi
