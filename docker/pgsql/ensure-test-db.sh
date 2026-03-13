#!/usr/bin/env sh
# When Postgres shows "Database directory appears to contain a database; Skipping initialization",
# init scripts are NOT run. Run these manually once from project root:
#
# 1) Create test DB (for phpunit):
#    docker exec -i onlineschoolmultitenant-pgsql psql -U onlineschoolmultitenant -d onlineschoolmultitenant -f - < docker/pgsql/init/02-create-test-db.sql
#
# 2) Create netdata role (stops "Role netdata does not exist" in logs):
#    docker exec -i onlineschoolmultitenant-pgsql psql -U onlineschoolmultitenant -d onlineschoolmultitenant -f - < docker/pgsql/init/03-create-netdata-user.sql
