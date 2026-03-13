-- Create test database for PHPUnit (same PostgreSQL server).
-- Idempotent: safe to re-run (CREATE only runs when DB does not exist).
SELECT 'CREATE DATABASE onlineschoolmultitenant_test' WHERE NOT EXISTS (SELECT 1 FROM pg_database WHERE datname = 'onlineschoolmultitenant_test')\gexec
GRANT ALL PRIVILEGES ON DATABASE onlineschoolmultitenant_test TO onlineschoolmultitenant;
