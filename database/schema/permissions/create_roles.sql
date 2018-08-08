
CREATE FUNCTION create_or_replace_role(rolename text)
  RETURNS VOID
  LANGUAGE 'plpgsql'
AS $function$
BEGIN
  EXECUTE $$DROP ROLE IF EXISTS $$ || rolename;
  EXECUTE $$
    CREATE ROLE $$ || rolename || $$ WITH
      NOLOGIN
      NOSUPERUSER
      INHERIT
      NOCREATEDB
      NOCREATEROLE
      NOREPLICATION
    $$;

END;
$function$;

\set role_guest :db_name'_guest'
\set role_user :db_name'_user'


SELECT create_or_replace_role(:'role_guest');
GRANT :role_guest TO :db_name;
SELECT create_or_replace_role(:'role_user');
GRANT :role_user TO :db_name;

DROP FUNCTION IF EXISTS create_or_replace_role(text);
