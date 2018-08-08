CREATE TABLE account_emails
(
  id bigserial,
  email character varying COLLATE pg_catalog."default" NOT NULL,
  account_id bigint NOT NULL,
  CONSTRAINT emails_pkey PRIMARY KEY (id),
  CONSTRAINT email UNIQUE (email)
)
WITH (
OIDS = FALSE
)
TABLESPACE pg_default;
