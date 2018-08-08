DROP FUNCTION IF EXISTS current_user_id();

CREATE OR REPLACE FUNCTION current_user_id(
)
  RETURNS integer
LANGUAGE 'sql'

COST 100
STABLE
AS $BODY$

  SELECT NULLIF(current_setting('user.id', TRUE), '')::INTEGER;

$BODY$;
