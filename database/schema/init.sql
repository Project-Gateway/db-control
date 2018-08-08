-- Ensure the existence of plpgsql language
CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;

-- Ensure the existence of pgcrypt password
CREATE EXTENSION IF NOT EXISTS pgcrypto WITH SCHEMA pg_catalog;

-- Creates the user to for the graphql application (Postgraphile) if it don't exists.
-- The user and password is equal to the db name by default.
CREATE FUNCTION create_user_if_not_exists(username text, pw text)
  RETURNS void
  LANGUAGE 'plpgsql'
AS $$
BEGIN
  IF NOT EXISTS (
      SELECT
      FROM   pg_catalog.pg_roles
      WHERE  rolname = username
  ) THEN
    EXECUTE $a$
    CREATE USER $a$ || username || $a$ WITH
      LOGIN
      NOSUPERUSER
      NOINHERIT
      NOCREATEDB
      CREATEROLE
      NOREPLICATION
      PASSWORD '$a$ || pw || $a$'
    $a$;
    COMMENT ON ROLE username IS 'Login role created to be used by the graphql application.';
  END IF;
END
$$;

SELECT create_user_if_not_exists(:'db_name'::text, :'db_name'::text);

DROP FUNCTION IF EXISTS create_user_if_not_exists(text, text);
