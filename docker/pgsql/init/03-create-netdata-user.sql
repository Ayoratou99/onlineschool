-- Netdata PostgreSQL plugin expects a role "netdata" with monitoring rights.
-- Idempotent: safe to run on existing data dir (e.g. after "Skipping initialization").
DO $$
BEGIN
  IF NOT EXISTS (SELECT 1 FROM pg_roles WHERE rolname = 'netdata') THEN
    CREATE ROLE netdata WITH LOGIN PASSWORD 'netdata';
    GRANT pg_monitor TO netdata;
  END IF;
END
$$;
-- Ensure password is 'netdata' (plugin often tries this); safe to re-run.
ALTER ROLE netdata PASSWORD 'netdata';
