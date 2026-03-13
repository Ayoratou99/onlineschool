-- Create Paperless-ngx database (same PostgreSQL server, separate database).
-- Idempotent: safe to re-run (CREATE only when DB does not exist).
SELECT 'CREATE DATABASE paperless' WHERE NOT EXISTS (SELECT 1 FROM pg_database WHERE datname = 'paperless')\gexec
GRANT ALL PRIVILEGES ON DATABASE paperless TO onlineschoolmultitenant;
