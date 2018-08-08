-- Be sure that anyone can connect on the database to be dropped
UPDATE pg_database SET datallowconn = 'false' WHERE datname = :'db_name';

-- Drop all the connections to the database
SELECT pg_terminate_backend(pid)
FROM pg_stat_activity
WHERE datname = :'db_name';

-- Drop the database
DROP DATABASE IF EXISTS :db_name;

-- Create a fresh database
CREATE DATABASE :db_name;
