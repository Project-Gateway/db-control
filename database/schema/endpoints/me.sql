DROP FUNCTION IF EXISTS me();

CREATE OR REPLACE FUNCTION me()
  RETURNS SETOF user_profile
  LANGUAGE 'sql'

  COST 100
  STABLE STRICT SECURITY DEFINER
  ROWS 1000
AS $BODY$

    SELECT u.name, json_agg(e.email)
    FROM accounts u
      INNER JOIN account_emails e
        ON u.id = e.account_id
    WHERE u.id = current_user_id()
    GROUP BY u.name;
$BODY$;
