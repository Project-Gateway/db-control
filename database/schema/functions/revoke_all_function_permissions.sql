DROP FUNCTION IF EXISTS revoke_all_function_permissions(text, text[]);
CREATE FUNCTION revoke_all_function_permissions(func text, params text[] default ARRAY[]::text[] )
  RETURNS VOID
LANGUAGE 'plpgsql'
AS $$
DECLARE
  role CHARACTER VARYING;
BEGIN
  FOR role IN
  SELECT distinct grantee
  FROM information_schema.role_routine_grants
  where routine_name = func
  LOOP
    IF role = 'PUBLIC' THEN
      EXECUTE 'REVOKE ALL ON FUNCTION '||func||'('||array_to_string(params, ',')||') FROM PUBLIC;';
    ELSE
      EXECUTE 'REVOKE ALL ON FUNCTION '||func||'('||array_to_string(params, ',')||') FROM "'||role||'";';
    END IF;
  END LOOP;

END;
$$;
