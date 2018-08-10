CREATE TABLE social_accounts
(
  id bigserial,
  account_id bigint NOT NULL,
  provider character varying COLLATE pg_catalog."default" NOT NULL,
  social_id character varying COLLATE pg_catalog."default" NOT NULL,
  avatar character varying COLLATE pg_catalog."default",
  created_at timestamp without time zone,
  updated_at timestamp without time zone,
  CONSTRAINT social_accounts_pkey PRIMARY KEY (id),
  CONSTRAINT social_accounts_account_id_fkey FOREIGN KEY (account_id)
  REFERENCES accounts (id) MATCH SIMPLE
  ON UPDATE NO ACTION
  ON DELETE NO ACTION
)
WITH (
OIDS = FALSE
)
TABLESPACE pg_default;
