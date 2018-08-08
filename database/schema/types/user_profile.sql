DROP TYPE IF EXISTS user_profile;

CREATE TYPE user_profile AS
(
  name character varying,
  emails json
);
