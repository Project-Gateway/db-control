CREATE TABLE account_emails
(
  id bigserial,
  email character varying COLLATE pg_catalog."default" NOT NULL,
  account_id bigint NOT NULL,
  created_at timestamp without time zone,
  updated_at timestamp without time zone,
  CONSTRAINT emails_pkey PRIMARY KEY (id),
  CONSTRAINT email UNIQUE (email)
)
WITH (
OIDS = FALSE
)
TABLESPACE pg_default;
