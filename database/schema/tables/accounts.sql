CREATE TABLE accounts
(
  id bigserial,
  password character varying COLLATE pg_catalog."default",
  name character varying COLLATE pg_catalog."default" NULL,
  fields jsonb,
  role character varying COLLATE pg_catalog."default" NOT NULL DEFAULT 'user'::character varying,
  created_at timestamp without time zone,
  updated_at timestamp without time zone,
  CONSTRAINT users_pkey PRIMARY KEY (id)
)
WITH (
OIDS = FALSE
)
TABLESPACE pg_default;

GRANT ALL ON TABLE accounts TO postgres;
