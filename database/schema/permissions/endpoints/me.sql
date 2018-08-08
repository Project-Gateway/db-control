SELECT revoke_all_function_permissions('me');

\set _user :db_name'_user'

-- only logged users can retrieve profile information
GRANT EXECUTE ON FUNCTION me() TO :_user;
